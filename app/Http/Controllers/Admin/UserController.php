<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Models\Department;


class UserController extends Controller
{
    public function index()
    {
        $projects = Project::orderBy('code', 'asc')->get();

        return view('admin.users.index', compact('projects'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'username' => 'required|unique:users,username',
            'project' => 'required',
            'department_id' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->username = $request->username;
        $user->nik = $request->nik;
        $user->project = $request->project;
        $user->department_id = $request->department_id;
        $user->is_active = 0; //false
        $user->password = bcrypt($request->password);
        $user->save();

        $user->assignRole('user');

        return redirect()->route('admin.users.index')->with('success', 'User created successfully');
    }

    public function activate($id)
    {
        $user = User::find($id);
        $user->is_active = 1; //true
        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'User activated successfully');
    }

    public function deactivate($id)
    {
        $user = User::find($id);

        if ($user->hasRole('superadmin')) {
            return redirect()->route('admin.users.index')->with('error', 'Superadmin user cannot be deactivated');
        }

        $user->is_active = 0; //false
        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'User deactivated successfully');
    }

    public function edit($id)
    {
        $user = User::find($id);
        $userRoles = $user->roles->pluck('name')->toArray();
        $roles = Role::all(); // Add this line to get all roles

        return view('admin.users.edit', compact('user', 'userRoles', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'username' => 'required|unique:users,username,' . $id,
            'project' => 'required',
            'department_id' => 'required',
            'password' => 'nullable|min:6|confirmed',
        ]);

        $user = User::find($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->username = $request->username;
        $user->nik = $request->nik;
        $user->project = $request->project;
        $user->department_id = $request->department_id;

        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        if ($request->has('roles')) {
            $roleNames = Role::whereIn('id', $request->roles)->pluck('name')->toArray();
            $user->syncRoles($roleNames);
        } else {
            $user->syncRoles([]); // Clear roles if none are selected
        }

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully');
    }

    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully');
    }

    public function data()
    {
        $users = User::with('department')->get();

        return datatables()->of($users)
            ->addIndexColumn()
            ->addColumn('department', function ($row) {
                return $row->department ? $row->department->akronim : '';
            })
            ->addColumn('is_active', function ($row) {
                return $row->is_active ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-danger">Inactive</span>';
            })
            ->addColumn('location_code', function ($row) {
                return $row->department ? $row->department->location_code : '';
            })
            ->addColumn('action', function ($row) {
                return view('admin.users.action', ['model' => $row]);
            })
            ->rawColumns(['is_active', 'action'])
            ->make(true);
    }

    public function getDepartmentsByProject(Request $request)
    {
        $project = $request->input('project');

        $departments = Department::where('project', $project)
            ->orderBy('department_name')
            ->get(['id', 'department_name']);

        return response()->json($departments);
    }
}
