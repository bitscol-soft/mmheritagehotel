<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;
use Module\HRM\Models\Department;
use Module\HRM\Models\Designation;
use Illuminate\Support\Facades\Cache;
use Illuminate\Notifications\Notifiable;
use Module\Permission\Models\Permission;
use Module\Permission\Models\PermissionUser;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'company_id', 'name', 'email', 'password', 'employee_id', 'status', 'employee_full_id', 'device_token'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];



    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function scopeCompany_id($query)
    {
        if (auth()->id() == 1) {
            return;
        }
        return $query->where('company_id', auth()->user()->company_id);
    }

    // akash methods for companies permission
    public function companies()
    {
        return $this->belongsToMany(Company::class);
    }

    // akash methods for order type permission
    public function order_types()
    {
        return $this->belongsToMany(OrderType::class)->orderBy('name');
    }

    // akash methods for buyers permission
    public function buyers()
    {
        return $this->belongsToMany(Buyer::class);
    }

    // akash methods for departments permission

    public function departments()
    {
        if(class_exists('Module\HRM\Models\Department'))
        {
            return $this->belongsToMany(Department::class);
        }
    }

    // akash methods for designations permission
    public function designations()
    {
        return $this->belongsToMany(Designation::class);
    }

    // akash methods for permission
    public function permissions()
    {
        return $this->belongsToMany(Permission::class)->where('status', 1);
    }

    // end permission methods

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function sample_dispatches()
    {
        return $this->hasMany(SampleDispatch::class, 'created_by');
    }

    public function arp_good_receives()
    {
        return $this->hasMany(ArpGoodReceive::class, 'create_by');
    }

    public static function hasAccess($slug)
    {
        $user_id = auth()->id();
        if ($user_id != 1) {
            $permission = Permission::where('slug', $slug)->first();
            if ($permission) {
                $permission_user = PermissionUser::where('permission_id', $permission->id)->where('user_id', $user_id)->first();
                if (!$permission_user) {
                    return false;
                }
                return true;
            } else {
                return false;
            }
        }
        return true;
    }


    public function isLoggedIn()
    {
        return Cache::has('logged-in-users-' . $this->id) ? '<span><i class="fa fa-circle green"></i></span>' : '';
    }

    public function credential()
    {
        return $this->hasOne(UserCredential::class, 'user_id', 'id');
    }

    public function user_type()
    {
        return $this->belongsTo(UserTypes::class, 'user_type', 'id');
    }
}
