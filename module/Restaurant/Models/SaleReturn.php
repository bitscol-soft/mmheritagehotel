<?php

namespace Module\Restaurant\Models;

use App\Model;
use App\Models\User;
use App\Models\Company;
use Module\Hospital\Models\Patient\Patient;
use Module\Hospital\Models\HospitalTransaction;
use Module\Hotel\Models\Guest;

class SaleReturn extends Model
{


    protected $table = 'rst_sale_returns';




    public static function boot()
    {
        parent::boot();



        static::creating(function ($model) {
            $user = auth()->user();

            $model->fill([
                'company_id' => $user->company_id,
                'created_by' => $user->id,
            ]);
        });


        static::updating(function ($model) {
            $model->fill([
                'updated_by' => auth()->id()
            ]);
        });


        static::deleting(function ($model) {

            $model->items()->delete();
            $model->transactions()->delete();
        });
    }






    protected $casts = [
        'date'  => 'date'
    ];


    public function items()
    {
        return $this->hasMany(SaleReturnDetail::class, 'sale_return_id');
    }


    public function guest()
    {
        return $this->belongsTo(Guest::class, 'customer_id');
    }



    public function user()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }







    public function company()
    {
        return $this->belongsTo(Company::class);
    }






    public function transactions()
    {
        return $this->morphMany(HospitalTransaction::class, 'transactionable');
    }
}
