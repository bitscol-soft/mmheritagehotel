<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Schema;


trait Activity
{

    // Activity::new("Update the Group info", User::class, auth()->id());
    public static function new($description, $causer_type, $causer_id, $name = null, $subject_type = null, $subject_id = null, $properties = null){
        if (Schema::hasTable('activity_logs')) {

            ActivityLog::create([
                'log_name'          => $name ?? null,
                'description'       => $description ?? null,
                'causer_type'       => $causer_type ?? null,
                'causer_id'         => $causer_id ?? null,
                'subject_type'      => $subject_type,
                'subject_id'        => $subject_id,
                'properties'        => $properties ?? null
            ]);
        }
    }


    public static function list(){
        ActivityLog::all();
    }

}
