<?php

namespace App\Http\Controllers;

use App\Traits\FileSaver;
use Illuminate\Http\Request;
use Spatie\DbDumper\Databases\MySql;

class DatabaseBackupController extends Controller
{

    use FileSaver;


    //--------------------------------------------------------------------------
    //            DB-BACKUP METHOD
    //--------------------------------------------------------------------------
    public function db_backup(Request $request)
    {
        $ds         = DIRECTORY_SEPARATOR;

        $path       = public_path() . $ds . 'app' . $ds . 'backups' . $ds;
        $file       = 'dump.sql';
        $directory  = $path . $file;
        $filename   = 'backup_' . fdate(now(), 'Y_m_d_h_i_s') . '.sql';


        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }


        if ($request->export_type == 'full') {
            MySql::create()
                ->setDbName(env('DB_DATABASE'))
                ->setUserName(env('DB_USERNAME'))
                ->setPassword(env('DB_PASSWORD'))
                ->setHost(env('DB_HOST'))
                ->setPort(env('DB_PORT'))
                ->dumpToFile($directory);
        } else {
            MySql::create()
                ->setDbName(env('DB_DATABASE'))
                ->setUserName(env('DB_USERNAME'))
                ->setPassword(env('DB_PASSWORD'))
                ->setHost(env('DB_HOST'))
                ->setPort(env('DB_PORT'))
                ->doNotCreateTables()
                ->dumpToFile($directory);
        }


        return response()->download($directory, $filename);
    }



    //--------------------------------------------------------------------------
    //            DB-BACKUP TO DRIVE METHOD
    //--------------------------------------------------------------------------
    public function databaseBackupToDrive()
    {

        $messageType = 'message';


        $response = $this->backupSavedToGoogleDrive();


        if (str_contains($response, 'Error')) {
            $messageType = 'error';
        }


        return redirect()->back()->with($messageType, $response);
    }
}
