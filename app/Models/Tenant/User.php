<?php

namespace App\Models\Tenant;

use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Caffeinated\Shinobi\Concerns\HasRolesAndPermissions;

class User extends Authenticatable
{
    use Notifiable, UsesTenantConnection, HasRolesAndPermissions;

    protected $with = ['establishment'];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'establishment_id', 'admin'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function modules()
    {
        return $this->belongsToMany(Module::class);
    }

    public function authorizeModules($modules)
    {
        if ($this->hasAnyModule($modules)) {
            return true;
        }
        abort(401, 'Esta acción no está autorizada.');
    }

    public function hasAnyModule($modules)
    {
        if (is_array($modules)) {
            foreach ($modules as $module) {
                if ($this->hasModule($module)) {
                    return true;
                }
            }
        } else {
            if ($this->hasModule($modules)) {
                return true;
            }
        }
        return false;
    }

    public function hasModule($module)
    {
        if ($this->modules()->where('name', $module)->first()) {
            return true;
        }
        return false;
    }

    public function establishment()
    {
        return $this->belongsTo(Establishment::class);
    }

    public function pos()
    {
        return $this->hasOne(Pos::class);
    }

    public function user_meta_format () {
        $permissions = collect();
        $roles = $this->roles->map(function($rol) use(&$permissions){
            $permissions = $permissions->merge($rol->permissions);
            return ['name' => $rol->name, 'slug' => $rol->slug, 'special' => $rol->special];
        });
        $permissions = $permissions->merge($this->permissions)->map(function($per) {
            return ['name' => $per->name, 'slug' => $per->slug];
        })->unique();
        return [
            'name' => $this->name,
            'email' => $this->email,
            'permissions' => $permissions,
            'roles' => $roles
        ];        
    }
}
