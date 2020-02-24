<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::orderBy('created_at', 'DESC')->paginate(10);
        return view('role.index', compact('roles'));
    }

    public function store(Request $request){
        \Validator::make($request->all(),[
            'name' => 'required|string|min:2|max:50'
        ])->validate();

        $role = Role::firstOrCreate(['name'=>$request->name]);
        return redirect()->back()->with('success', 'Role <strong>' . $role->name . '</strong> has been saved');
    }

    public function destroy($id){
        $role = Role::findOrFail($id);
        $role->delete();
        return redirect()->back()->with('success', 'Role <strong>' . $role->name . '</strong> has been deleted');
    }
}
