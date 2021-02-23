<?php

namespace App\Http\Controllers;

use App\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use DB;
class RolePermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::all();
        $permissions = Permission::all();
        $role_has_permissions = DB::table('role_has_permissions')->get()->toArray();
        return view('settings.roles-permissions.index')->with('roles', $roles);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('settings.roles-permissions.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        Role::create($request->all());
        return redirect()->route('roles-permissions.index')->with('message','Successfully Saved!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user, $id)
    {
        //
        $role = Role::find($id);
        $permissions = Permission::all();
        $role_has_permissions = DB::table('role_has_permissions')->get()->toArray();
        return view('settings.roles-permissions.show')
            ->with([
                'role' => $role,
                'permissions' => $permissions,
                'role_has_permissions' => $role_has_permissions
            ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user, $id)
    {
        //
        $role = Role::find($id);
        $permissions = Permission::all();
        $role_has_permissions = DB::table('role_has_permissions')->get()->toArray();
        return view('settings.roles-permissions.edit')
            ->with([
                'role' => $role,
                'permissions' => $permissions,
                'role_has_permissions' => $role_has_permissions
            ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // 
        $role = Role::find($id);
        $role->update($request->all());
        return redirect()->back()->with('message','Successfully Updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        DB::table('model_has_roles')->where('role_id', $id)->delete();
        DB::table('role_has_permissions')->where('role_id', $id)->delete();
        DB::table('roles')->where('id', $id)->delete();
        return redirect()->back();
    }


    public function assignpermissiontorole(Request $request) {
        $post_role = $request->input('role');
        $post_permission = $request->input('permission');
        $action = $request->input('action');

        $role = Role::findById($post_role);
        $permission = Permission::findById($post_permission);
        if($action) {
            $role->givePermissionTo($permission);
            return response()->json(['success' => 'Permission added!']); 
        } else {
            $role->revokePermissionTo($permission);
            return response()->json(['success' => 'Permission removed!']);
        }
    }


    public function seedpermission() {
        $permission_id = DB::table('permissions')->get();
        $role = Role::findById(1);
        foreach($permission_id as $id) {
            $permission = Permission::findById($id->id);
            $role->givePermissionTo($permission);
        }
        

    }

}
