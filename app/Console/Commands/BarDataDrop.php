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
        $sub_modules = Submodule::where('module_id', 170000)->get();

        DB::statement('SET FOREIGN_KEY_CHECKS=0');

        foreach ($sub_modules as $key => $submodule) {

            foreach ($submodule->parent_permissions as $key => $parent_permission) {
                $parent_permission->permissions()->delete();
            }

            $submodule->parent_permissions()->delete();
            $submodule->delete();
        }
        \Module\Permission\Models\Module::find(170000)->delete();

        DB::statement('SET FOREIGN_KEY_CHECKS=1');

        $this->info('Bar Permission Module tables data successfully droped!');
    }

}
