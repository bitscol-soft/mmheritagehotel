<?php

namespace Module\HotelWebsite\Models;

class Page extends Model
{
    protected $table = 'website_pages';

    public function ScopeActive(){
        return $this->where('status', 1);
    }
}

