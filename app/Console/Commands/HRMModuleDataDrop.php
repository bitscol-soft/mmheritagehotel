<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class HRMModuleDataDrop extends Command
{
    protected $signature = 'hrm:drop';

    protected $description = 'Drop All HRM Module Tables';

    public function handle()
    {
        $tables = [
            'table_name',
        ];

        foreach ($tables as $table) {
            Schema::dropIfExists($table);

            DB::table('migrations')->where('migration', 'like', '%_create_'.$table.'_table')->delete();
        }

        $this->info('HRM Module tables successfully droped!');
    }
}
