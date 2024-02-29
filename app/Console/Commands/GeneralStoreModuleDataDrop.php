<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class GeneralStoreModuleDataDrop extends Command
{
    protected $signature = 'gs:drop';

    protected $description = 'Drop All General Store Module Tables';

    public function handle()
    {
        $tables = [
            'table_name',
        ];

        foreach ($tables as $table) {
            Schema::dropIfExists($table);

            DB::table('migrations')->where('migration', 'like', '%_create_'.$table.'_table')->delete();
        }

        $this->info('General Store Module tables successfully droped!');
    }
}
