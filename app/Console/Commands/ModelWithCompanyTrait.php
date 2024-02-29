<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;

class ModelWithCompanyTrait extends Command
{

    protected $signature = 'module:advancemodel {name} {path?} {folder?}';

    protected $description = 'Command description';

    public function handle()
    {

        $name = $this->argument('name');
        $path = $this->argument('path');
        $folder = $this->argument('folder');


        if ($path == '') {
            $path = $this->ask('Module Name: ');
        }



        $stub = file_get_contents(base_path('app/Console/stubs/model-with-company-trait.stub'));

        $stub = str_replace('Path', $path, $stub);
        $stub = str_replace('ModelName', $name, $stub);

        if($folder)
        {
            $stub = str_replace('Folder', $folder, $stub);
        }else
        {
            $stub = str_replace('\Folder', '', $stub);
        }

        $dir = "module/$path/Models/$folder";

        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }


        $migration = $this->ask('Need Migration(Y/N): ');


        $this->info("Creating Module $name");

        file_put_contents("$dir/$name.php", $stub);


        $this->info("Created Module $name");




        // make migration
        if (strtolower($migration) == 'y' || strtolower($migration) == 'yes') {

            $migrationDir = "module/$path/database/migrations";

            if (!is_dir($migrationDir)) {
                mkdir($migrationDir, 0755, true);
            }

            $migrationStub = file_get_contents(base_path('app/Console/stubs/migration.stub'));

            $modelPulural = str_plural($name);

            $tableName = snake_case($modelPulural);

            $time = Carbon::parse(now())->format('Y_m_d_u');


            $migrationStub = str_replace('TableName', $tableName, $migrationStub);
            $migrationStub = str_replace('ClassName', "Create" . $modelPulural . "Table", $migrationStub);

            $filename = $time . '_create_' . snake_case($tableName) . '_table.php';

            $this->info("Creating Migration $filename");

            file_put_contents("$migrationDir/$filename", $migrationStub);

            $this->info("Created Migration $filename");
        }
    }
}
