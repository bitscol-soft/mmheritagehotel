<?php

namespace Module\BanquetHall\Models;

use App\Traits\AutoCreatedUpdated;
use App\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class BanquetItemDetails extends Model
{
    use HasFactory, AutoCreatedUpdated;

    protected $table = 'banquetitem_details';

    


}
