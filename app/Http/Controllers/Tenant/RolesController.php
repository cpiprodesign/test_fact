<?php

namespace App\Http\Controllers\Tenant;
use Illuminate\Http\Request;
use App\Models\Tenant\Module;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Caffeinated\Shinobi\Models\Role;
use Caffeinated\Shinobi\Models\Permission;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('tenant.roles.index');
    }

    public function datatable () {
        $all = Role::all();
        return response()->json($all);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    public function tables () {
        $modules = Module::orderBy('description')->get();
        return compact('modules'); 
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request);
        $res = ['success' => false];
        if ($request->has(['name', 'description', 'slug', 'permisos', 'special', 'target_permisos'])) {
            // dd($request);
            DB::beginTransaction();
            $role = Role::firstOrNew(['id' => $request->id]);
            $role->name = $request->name;
            $role->description = $request->description;
            $role->slug = $request->slug;
            if ($request->special != 'custom')
                $role->special = $request->special;
            if ($role->save()) {
                $role->syncPermissions($request->target_permisos);
                // $role->syncPermissions(collect($request->permisos)->filter(function($permiso) {
                //    return $permiso['checked'] == true;
                // })->map(function($permiso) {
                //     return $permiso['slug'];
                // }));
                $res['success'] = true;
                DB::commit();
            }
        }
        return response()->json($res);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }
    
    public function record($id)
    {
        return Role::with(['permissions'])->findOrFail($id); 
    }
    
    public function records(Request $request)
    {
        return Role::orderBy('slug')->get(); 
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        
        $res = ['success' => false];
        $rol = Role::findOrFail($id);
        if ($rol) {
            DB::beginTransaction();
            if ($rol->delete()) {
                $res['success'] = true;
                DB::commit();
            } else DB::rollback();
        }
        return response()->json($res);
    }
}
