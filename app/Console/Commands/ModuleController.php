<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ModuleController extends Command
{


    protected $signature = 'module:controller {name} {path?}';




    public function handle()
    {

        $name = $this->argument('name');
        $path = $this->argument('path');


        if ($path == '') {
            $path = $this->ask('Module Name: ');
        }


        $stub = file_get_contents(base_path('app/Console/stubs/controller.stub'));


        $parts = explode('/', $name);
        $className = array_pop($parts);
        $dir = implode('/', $parts);

        if (!is_dir("module/{$path}/Controllers/$dir")) {
            mkdir("module/{$path}/Controllers/$dir", 0755, true);
            $stub = str_replace("ModuleName", "$path" . "\Controllers\\" . $dir, $stub);
        } else {
            $stub = str_replace("ModuleName", "$path" . "\Controllers", $stub);
        }
        $stub = str_replace('DummyClass', $className, $stub);

        if (!file_exists("module/{$path}/Controllers/$name" . '.php')) {
            file_put_contents("module/{$path}/Controllers/$name" . '.php', $stub);
        }

        $this->info('Controller created success !');
    }
}
