<?php

namespace App\Models\Machine;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class AttendanceDevice extends Model
{
    protected $guarded = ['id'];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->fill([
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        });
        static::updating(function ($model) {
            $model->fill([
                'updated_by' => auth()->id(),
                'updated_at' => Carbon::now(),
            ]);
        });
    }
}
