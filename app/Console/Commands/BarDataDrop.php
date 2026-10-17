<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Module\Permission\Models\Submodule;

class BarDataDrop extends Command
{
    protected $signature = 'bar-data:drop';

    protected $description = 'Drops Bar Data Tables Data';

    public function handle()
    {
        // Resolve the Bar module by NAME: the hard-coded 170000 pointed at the
        // CRM module's id in the shipped schema, so this command used to delete
        // CRM's permission tree instead of Bar's (BUGS #27).
        $module = \Module\Permission\Models\Module::where('name', 'Bar')->first();

        if (!$module) {
            $this->error('Bar module not found; nothing dropped.');

            return;
        }

        $sub_modules = Submodule::where('module_id', $module->id)->get();

        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        foreach ($sub_modules as $key => $submodule) {

            foreach ($submodule->parent_permissions as $key => $parent_permission) {
                $parent_permission->permissions()->delete();
            }

            $submodule->parent_permissions()->delete();
            $submodule->delete();
        }
        $module->delete();

        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $this->info('Bar Permission Module tables data successfully droped!');
    }

}
