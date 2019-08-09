<?php
namespace App\Http\Controllers\Tenant;

use App\Models\Tenant\User;
use App\Models\Tenant\Module;
use App\Http\Controllers\Controller;
use App\Models\Tenant\Establishment;
use App\Http\Requests\Tenant\UserRequest;
use Caffeinated\Shinobi\Models\Permission;
use App\Http\Resources\Tenant\UserResource;
use App\Http\Resources\Tenant\UserCollection;

class UserController extends Controller
{
    public function index()
    {
        return view('tenant.users.index');
    }

    public function record($id)
    {
        $record = new UserResource(User::findOrFail($id));

        return $record;
    }

    public function tables()
    {
        // $modules = Module::orderBy('description')->get();
        $modules = Permission::where('slug', 'like', 'tenant.module.%')->orderBy('slug')->get();
        $establishments = Establishment::orderBy('description')->get();

        return compact('modules', 'establishments');
    }

    public function store(UserRequest $request)
    {
        
        $id = $request->input('id');
        $user = User::firstOrNew(['id' => $id]);
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->establishment_id = $request->input('establishment_id');
        $user->admin = $request->input('admin');

        if (!$id)
        {
            $user->api_token = str_random(50);
            $user->password = bcrypt($request->input('password'));
        }
        elseif ($request->input('password') !== '' && !is_null($request->input('password')))
        {
            if (env('PASSWORD_CHANGE'))
            {
                $user->password = bcrypt($request->input('password'));
            }
        }
        
        $user->save();

        $modules = collect($request->input('modules'))->where('checked', true)->pluck('slug')->toArray();
        $user->syncPermissions($modules);

        if ($request->has('target_roles')) {
            $user->syncRoles($request->target_roles);
        }
        
        return [
            'success' => true,
            'message' => ($id)?'Usuario actualizado':'Usuario registrado'
        ];
    }

    public function records()
    {
        $records = User::all();

        return new UserCollection($records);
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return [
            'success' => true,
            'message' => 'Usuario eliminado con éxito'
        ];
    }
}