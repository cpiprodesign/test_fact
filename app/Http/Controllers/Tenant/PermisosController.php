<?php

namespace App\Http\Controllers\Tenant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Caffeinated\Shinobi\Models\Permission;
use Illuminate\Support\Facades\DB;

class PermisosController extends Controller
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
        $all = Permission::orderBy('name', 'asc')->get();
        return response()->json($all);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storex(Request $request)
    {
        $res = ['success' => false];
        if ($request->has(['name', 'description', 'slug'])) {
            $permiso = new Permission();
            $permiso->name = $request->name;
            $permiso->description = $request->description;
            $permiso->slug = $request->slug;
            $res['success'] = $permiso->save();
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
        // return Permission::findOrFail($id); 
    }

    public function record($id)
    {
        return Permission::findOrFail($id); 
    }

    public function records(Request $request)
    {
        return Permission::all(); 
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
        $permiso = Permission::findOrFail($id);
        if ($permiso) {
            DB::beginTransaction();
            if ($permiso->delete()) {
                $res['success'] = true;
                DB::commit();
            } else DB::rollback();
        }
        return response()->json($res);

        

    }
}
