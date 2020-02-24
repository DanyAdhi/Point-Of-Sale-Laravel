<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\User;
use DB;

class UserController extends Controller
{
  
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(10);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::orderBy('name', 'asc')->get();
        return view('users.create', compact('roles'));
    }

    
    public function store(Request $request)
    {
        \Validator::make($request->all(),[
            'name'      => 'required|string|min:2|max:50',
            'email'     => 'required|email',
            'role'      => 'required|string'
        ])->validate();

        $user = User::firstOrCreate([
            'email' => $request->email
        ],
        [
            'name'      => $request->name,
            'password'  => bcrypt('password')
        ]);

        $user->assignRole($request->role);
        return redirect()->route('users.index')->with('success', 'User <strong>' . $user->name . '</strong> has been saved');
    }

    
    public function show($id)
    {
        //
    }

    
    public function edit($id)
    {
        $user   = User::findOrFail($id);
        $roles  = Role::orderBy('name', 'asc')->get();

        return view('users.edit', compact('user','roles'));
    }

     
    public function update(Request $request, $id)
    {
        \Validator::make($request->all(),[
            'name'      => 'required|string|min:2|max:50',
            'email'     => 'required|email|unique.users',
            'role'      => 'required|string|exist:roles.name'
        ])->validate();

        $user = User::findOrFail($id);
        $user->update([
            'email' => $request->email
        ],
        [
            'name'  => $request->name
        ]);

        return redirect()->route('users.index')->with('success', 'User <strong>' . $user->name . '</strong> has been updated');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->back()->with('success', 'User <strong>' . $user->name . '</strong> has been deleted');
    }

    public function rolePermission(Request $request)
    {
        $role = $request->get('role');
        
        //Default, set dua buah variable dengan nilai null
        $permissions    = null;
        $hasPermission  = null;
        
        //Mengambil data role
        $roles = Role::all()->pluck('name');
        
        //apabila parameter role terpenuhi
        if (!empty($role)) {
            //select role berdasarkan namenya, ini sejenis dengan method find()
            $getRole = Role::findByName($role);
            
            //Query untuk mengambil permission yang telah dimiliki oleh role terkait
            $hasPermission = DB::table('role_has_permissions')
                ->select('permissions.name')
                ->join('permissions', 'role_has_permissions.permission_id', '=', 'permissions.id')
                ->where('role_id', $getRole->id)->get()->pluck('name')->all();
            
            //Mengambil data permission
            $permissions = Permission::all()->pluck('name');
        }
        return view('users.role_permission', compact('roles', 'permissions', 'hasPermission'));
    }

    public function addPermission(Request $request)
    {
        \Validator::make($request->all(),[
            'name' => 'required|string|unique:permissions'
        ])->validate();

        $permission = Permission::firstOrCreate([
            'name' => $request->name
        ]);
        return redirect()->back();
    }


    public function setRolePermission(Request $request, $role)
    {
        //select role berdasarkan namanya
        $role = Role::findByName($role);
        
        //fungsi syncPermission akan menghapus semua permissio yg dimiliki role tersebut
        //kemudian di-assign kembali sehingga tidak terjadi duplicate data
        $role->syncPermissions($request->permission);
        return redirect()->back()->with(['success' => 'Permission to Role Saved!']);
    }

    public function roles(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $roles = Role::all()->pluck('name');
        return view('users.roles', compact('user', 'roles'));
    }

    public function setRole(Request $request, $id)
    {
        $this->validate($request, [
            'role' => 'required'
        ]);
        $user = User::findOrFail($id);
        //menggunakan syncRoles agar terlebih dahulu menghapus semua role yang dimiliki
        //kemudian di-set kembali agar tidak terjadi duplicate
        $user->syncRoles($request->role);
        return redirect()->back()->with(['success' => 'Role Sudah Di Set']);
    }


}
