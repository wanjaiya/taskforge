<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    //
    public function index(){
        $roles = Role::with('permissions')->latest()->paginate(20);
         
        foreach($roles as $role){
            $role->permission_names = $role->permissions->pluck('name')->toArray();
        }


        $permissions = Permission::orderBy('group')->get();
        return view('admin.roles.index', compact('roles', 'permissions'));
    }

    public function create(){

    }

    public function store(Request $request){

        $validated = $request->validate([
            'name' => 'required',
            'slug' => 'required | unique:roles,slug',
            'permissions' => 'required | array'
        ]);

        if(!$validated){
            return back()->withErrors($validated,'roleCreate');
        }

        $role = Role::create([
            'name' => $request->name,
            'slug' => $request->slug,
        ]);

        $role->permissions()->sync($request->permissions ?? []);

        return redirect()->route('roles.index')->with('status','Role created successfully');

    }


    public function update(Request $request, Role $role){

        $validated = $request->validate([
            'name' => 'required',
            'slug' => 'required ',
            'permissions' => 'required | array'
        ]);

        $role->update([
            'name' => $request->name,
            'slug' => $request->slug,
        ]);

        $role->permissions()->sync($request->permissions ?? []);

        return redirect()->route('roles.index')->with('status','Role updated successfully');

    }
    public function destroy(Request $request, Role $role){

        $role->delete();

        return redirect()->route('roles.index')->with('status','Role deleted successfully');

    }
}
