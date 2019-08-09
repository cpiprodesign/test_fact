<?php

namespace App\Http\Resources\Tenant;

use App\Models\Tenant\Module;
use Caffeinated\Shinobi\Models\Permission;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $all_modules = Permission::where('slug', 'like', 'tenant.module.%')->orderBy('slug')->get();
        $modules_in_user = $this->permissions->pluck('slug')->toArray();
        $modules = [];
        
        foreach ($all_modules as $module)
        {
            $modules[] = [
                'id' => $module->id,
                'slug' => $module->slug,
                'description' => $module->description,
                'checked' => (bool) in_array($module->slug, $modules_in_user)
            ];
        }

        return [
            'id' => $this->id,
            'email' => $this->email,
            'name' => $this->name,
            'admin' => $this->admin,
            'api_token' => $this->api_token,
            'establishment_id' => $this->establishment_id,
            'modules' => $modules,
            'target_roles' => $this->roles->map(function($role) {
                    return $role->slug;
                    
            })
        ];
    }
}