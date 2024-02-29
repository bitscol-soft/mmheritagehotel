<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as BaseModel;

/**
 *
 */
class Model extends BaseModel
{

    protected $prefix;

    protected $guarded = [];


    public function scopeSearchInField($query, $filter, $field_name)
    {
        $query->when(request()->filled($filter), function ($qr) use ($filter, $field_name) {
            $qr->where($field_name, request()->$filter);
        });
    }


    public function scopeSearchByField($query, $filed_name)
    {
        $query->when(request()->filled($filed_name), function ($qr) use ($filed_name) {
            $qr->where($filed_name, request()->$filed_name);
        });
    }


    public function scopeQueryLike($query, $filed_name)
    {
        $query->when(request()->filled($filed_name), function ($qr) use ($filed_name) {
            $qr->where($filed_name, 'like', '%' . request()->$filed_name . '%');
        });
    }



    public function scopeLikeSearch($query, $filed_name)
    {
        $query->when(request()->filled($filed_name), function ($qr) use ($filed_name) {
            $qr->where($filed_name, 'like', '%' . request()->$filed_name . '%');
        });
    }



    public function scopeQuerySum($query, $relation, $as, $field_name)
    {
        $query->withCount(["{$relation} as {$as}" => function ($q)  use ($field_name) {
            $q->select(\DB::raw("SUM({$field_name})"));
        }]);
    }




    public function scopeDateFilter($query, $filed_name = 'date')
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
        if ($from == null) {
            $from = 'from';
        }

        $query->when(request()->filled($from), function ($qr) use ($filed_name, $from) {
            $qr->where($filed_name, '>=', request()->$from);
        });
    }


    public function scopeSearchDateTo($query, $filed_name, $to = null)
    {
        if ($to == null) {
            $to = 'to';
        }

        $query->when(request()->filled($to), function ($qr) use ($filed_name, $to) {
            $qr->where($filed_name, '<=', request()->$to);
        });
    }


    public function scopeSearchDateRange($query, $field_name, $from = null, $to = null)
    {
        if ($from == null) {
            $from = date('Y-m-d');
        }


        if ($to == null) {
            $to = date('Y-m-d');
        }


        $query->when(request()->filled('from') || request()->filled('to'), function ($qr) use ($field_name, $from, $to) {
            $qr->whereBetween($field_name, [$from, $to]);
        });
    }


    public function scopeSearchFromRelation($query, $relation, $filed_name)
    {
        $query->when(request()->filled($filed_name), function ($qr) use ($relation, $filed_name) {
            $qr->whereHas($relation, function ($q) use ($filed_name) {
                $q->where($filed_name, request()->$filed_name);
            });
        });
    }



    public function scopeSearchInRelation($query, $relation, $filter, $field_name)
    {
        $query->when(request()->filled($filter), function ($qr) use ($relation, $field_name, $filter) {
            $qr->whereHas($relation, function ($q) use ($filter, $field_name) {
                $q->where($field_name, request()->$filter);
            });
        });
    }



    public function scopeSearchInsideRelation($query, $relation, $field_name = null, $value)
    {
        $query->when(isset($field_name), function ($qr) use ($relation, $field_name, $value) {
            $qr->whereHas($relation, function ($q) use ($field_name, $value) {
                $q->where($field_name, $value);
            });
        });
    }



    public function scopeSearchDeeperInRelation($query, $parent_relation, $child_relation, $filter, $field_name)
    {
        $query->when(request()->filled($filter), function ($qr) use ($parent_relation, $child_relation, $field_name, $filter) {
            $qr->whereHas($parent_relation, function ($q) use ($filter, $child_relation, $field_name) {
                $q->whereHas($child_relation, function ($q1) use ($filter, $field_name) {
                    $q1->where($field_name, request()->$filter);
                });
            });
        });
    }


    public function scopeCompanies($query)
    {
        if (auth()->id() == 1) {
            return;
        }
        return $query->where('company_id', auth()->user()->company_id);
    }






    public function scopeActive($query)
    {

        return $query->where('status', 1);
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



    /* ------------------------------------------
     |  Getters & Setters
     | ------------------------------------------
     */
    public function getTable()
    {
        return $this->getPrefix() . parent::getTable();
    }

    public function getPrefix()
    {
        return is_null($this->prefix) ? '' : $this->prefix;
    }

    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;

        return $this;
    }
}
