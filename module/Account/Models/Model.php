<?php


namespace Module\Account\Models;

use Illuminate\Database\Eloquent\Model as BaseModel;

/**
 *
 */
class Model extends BaseModel
{
    protected $guarded = [];


    public function scopeLikeSearch($query, $filed_name)
    {
        $query->when(request()->filled($filed_name), function($qr) use($filed_name) {
           $qr->where($filed_name, 'LIKE', '%' . request()->$filed_name . '%');
        });
    }


    public function scopeSearchByField($query, $filed_name)
    {
        $query->when(request()->filled($filed_name), function($qr) use($filed_name) {
           $qr->where($filed_name, request()->$filed_name);
        });
    }









    /*
     |--------------------------------------------------------------------------
     | SEARCH MULTIPLE DATA BY FIELD NAME
     |--------------------------------------------------------------------------
    */
    public function scopeSearchByFields($query, $filed_names)
    {
        foreach ($filed_names as $key => $filed_name) {

            $query->when(request()->filled($filed_name), function($qr) use($filed_name) {
                $qr->where($filed_name, request()->$filed_name);
             });
        }

    }


    public function scopeWhereDateFilter($query, $filed_name = 'date')
    {
        $query->when(request()->filled('from') | request()->filled('from_date'), function($qr) use($filed_name) {
           $qr->whereDate($filed_name, '>=', (request('from') ?? request('from_date')));
        })
        ->when(request()->filled('to') | request()->filled('to_date'), function($qr) use($filed_name) {
           $qr->whereDate($filed_name, '<=', (request('to') ?? request('to_date')));
        });
    }

    

    public function scopeDateFilter($query, $filed_name = 'date')
    {
        $query->when(request()->filled('from') | request()->filled('from_date'), function($qr) use($filed_name) {
           $qr->where($filed_name, '>=', (request('from') ?? request('from_date')));
        })
        ->when(request()->filled('to') | request()->filled('to_date'), function($qr) use($filed_name) {
           $qr->where($filed_name, '<=', (request('to') ?? request('to_date')));
        });
    }


    public function scopeFilterDate($query, $filed_name = 'date')
    {
        $query->when(request()->filled('from') | request()->filled('from_date'), function ($qr) use ($filed_name) {
            $qr->where($filed_name, '>=', (request('from') ?? request('from_date')));
        })
            ->when(request()->filled('to') | request()->filled('to_date'), function ($qr) use ($filed_name) {
                $qr->where($filed_name, '<=', (request('to') ?? request('to_date')));
            });
    }


    public function scopeSearchDateFrom($query, $filed_name, $from = null)
    {
        if($from == null) {
            $from = 'from';
        }

        $query->when(request()->filled($from), function($qr) use($filed_name, $from) {
           $qr->where($filed_name, '>=', request($from));
        });
    }


    public function scopeSearchDateTo($query, $filed_name, $to = null)
    {
        if($to == null) {
            $to = 'to';
        }

        $query->when(request()->filled($to), function($qr) use($filed_name, $to) {
           $qr->where($filed_name, '<=', request($to));
        });
    }


    public function scopeLikeSearchRelation($query, $relation, $filed_name)
    {
        $query->when(request()->filled($filed_name), function($qr) use($relation, $filed_name) {
           $qr->whereHas($relation, function ($q) use ($filed_name) {
                $q->where($filed_name, 'LIKE', '%' . request($filed_name) . '%');
            });
        });
    }


    public function scopeSearchFromRelation($query, $relation, $filed_name)
    {
        $query->when(request()->filled($filed_name), function($qr) use($relation, $filed_name) {
           $qr->whereHas($relation, function ($q) use ($filed_name) {
                $q->where($filed_name, request($filed_name));
            });
        });
    }

    public function scopeSortby($query)
    {
        $query->when(request()->filled('sort_by_key'), function ($query) {

            $order_by = 'asc';

            if (strpos(request()->sort_by_key, 'desc') !== false) {
                $order_by = 'desc';
            }

            $filed_name = str_replace('_desc', '', request()->sort_by_key);

            return $query->orderBy($filed_name, $order_by);
        });
    }
}
