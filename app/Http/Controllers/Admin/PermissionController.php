<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    //

    public function index(){
        $permissions = Permission::latest()->paginate(20);
        return view('admin.permissions.index', compact('permissions'));
    }

    public function create(){

    }

    public function store(Request $request){

        $validated = $request->validate([
            'name' => 'required',
            'slug' => 'required | unique:permissions,slug',
        ]);

        if(!$validated){
            return back()->withErrors($validated,'permissionCreate');
        }

        Permission::create([
            'name' => $request->name,
            'slug' => $request->slug,
            'group' => $request->group,
        ]);

        return redirect()->route('permissions.index')->with('status','Permission created successfully');

    }


    public function update(Request $request, Permission $permission){

        $validated = $request->validate([
            'name' => 'required',
        ]);

        $permission->update([
            'name' => $request->name,
            'slug' => $request->slug,
            'group' => $request->group,
        ]);

        return redirect()->route('permissions.index')->with('status','Permission updated successfully');

    }
    public function destroy(Request $request, Permission $permission){

        $permission->delete();

        return redirect()->route('permissions.index')->with('status','Permission deleted successfully');

    }
}
