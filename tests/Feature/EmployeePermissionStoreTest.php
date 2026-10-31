<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Tests\Concerns\CreatesAccSchema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

/**
 * docs/BUGS.md #74 - the employee-profile permission screen could not save.
 *
 * The GET guarded its table with hasTable(); the store did not, so on the
 * shipped schema every save failed and the catch turned the SQL error into a
 * flash that reads like advice. Fixing the guard is not enough on its own: the
 * "uncheck everything" request carries no field at all, and array_filter(null)
 * is a TypeError on PHP 8 - an Error, not an Exception, so it walked straight
 * past the same catch as a 500.
 */
class EmployeePermissionStoreTest extends TestCase
{
    use CreatesAccSchema;

    private const URL = '/setting/permission-access-employee';

    protected function setUp(): void
    {
        parent::setUp();
        $this->createMinimalSchema();

        // only the Permission module matters here; the rest of the app would need
        // a module table and a dump to even register
        Route::group(['middleware' => ['web', 'auth']], function () {
            Route::namespace('\\')->group(base_path('module/Permission/routes/web_permission.php'));
        });
        app('router')->getRoutes()->refreshNameLookups();

        DB::table('groups')->insert(['name' => 'MM Heritage', 'created_at' => now(), 'updated_at' => now()]);

        $this->withoutMiddleware(\App\Http\Middleware\VerifyCsrfToken::class);

        // user id 1 is the system admin and bypasses hasAccess()
        $this->actingAs(User::forceCreate(['id' => 1, 'name' => 'Mr. Admin', 'email' => 'admin@test', 'password' => 'x']));
    }

    private function createEmployeePermissionsTable(): void
    {
        Schema::create('employee_permissions', function (Blueprint $t) {
            $t->id();
            $t->unsignedBigInteger('permission_id');
            $t->timestamps();
        });
    }

    public function test_saving_is_reported_honestly_when_the_table_is_not_installed(): void
    {
        $this->assertFalse(Schema::hasTable('employee_permissions'), 'this test is about the table being absent');

        $response = $this->post(self::URL, ['employee_permissions' => [5, 7]]);

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertStringContainsString('employee_permissions', session('error'));
        $this->assertStringNotContainsString('no such table', session('error'), 'the operator should not be shown raw SQL');
        $response->assertSessionMissing('message');
    }

    public function test_unchecking_every_box_clears_the_assignments_instead_of_500ing(): void
    {
        $this->createEmployeePermissionsTable();
        DB::table('employee_permissions')->insert([
            ['permission_id' => 5],
            ['permission_id' => 7],
        ]);

        // an all-unchecked form posts no employee_permissions field at all
        $response = $this->post(self::URL, []);

        $response->assertRedirect();
        $response->assertSessionHas('message');
        $response->assertSessionMissing('error');
        $this->assertSame(0, DB::table('employee_permissions')->count());
    }

    public function test_a_normal_save_replaces_the_assignment_without_duplicating(): void
    {
        $this->createEmployeePermissionsTable();
        DB::table('employee_permissions')->insert([['permission_id' => 5]]);

        // 5 survives, 9 is added, 11 comes in on a second save and 5 is dropped
        $this->post(self::URL, ['employee_permissions' => [5, 9]])->assertSessionHas('message');
        $this->assertSame([5, 9], DB::table('employee_permissions')->orderBy('permission_id')->pluck('permission_id')->map(fn ($id) => (int) $id)->all());

        $this->post(self::URL, ['employee_permissions' => [9, 11]])->assertSessionHas('message');
        $this->assertSame([9, 11], DB::table('employee_permissions')->orderBy('permission_id')->pluck('permission_id')->map(fn ($id) => (int) $id)->all());
    }
}
