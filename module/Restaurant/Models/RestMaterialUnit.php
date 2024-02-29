<?php

namespace Module\Restaurant\Models;

// use Module\Restaurant\Models\Model;
use App\Traits\AutoCreatedUpdated;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RestMaterialUnit extends Model
{
    use HasFactory;
    use AutoCreatedUpdated;

    protected $guarded = [];
    protected $table = 'rest_material_units';


    public function items()
    {
        return $this->hasMany(RestMaterial::class);
    }
}
