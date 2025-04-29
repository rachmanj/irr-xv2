<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    protected $role;
    protected $permission;

    public function __construct(Role $role, Permission $permission)
    {
        $this->role = $role;
        $this->permission = $permission;
    }

    public function index()
    {
        return view('admin.roles.index');
    }

    public function create()
    {
        $permissions = $this->permission->orderBy('name', 'asc')->get();

        return view('admin.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $this->validateRequest($request);

        $role = $this->role->create($request->only('name', 'guard_name'));

        $this->syncPermissions($role, $request->permissions);

        return redirect()->route('admin.roles.index')->with('success', 'Role created successfully');
    }

    public function edit($id)
    {
        $role = $this->role->findById($id);
        $permissions = $this->permission->orderBy('name', 'asc')->get();

        return view('admin.roles.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, $id)
    {
        $this->validateRequest($request, $id);

        $role = $this->role->findById($id);
        $role->update($request->only('name', 'guard_name'));

        $this->syncPermissions($role, $request->permissions);

        return redirect()->route('admin.roles.index')->with('success', 'Role updated successfully');
    }

    public function destroy($id)
    {
        $role = $this->role->findById($id);
        
        if($role->name === 'superadmin') {
            return redirect()->route('admin.roles.index')->with('error', 'Superadmin role cannot be deleted');
        }
        
        $role->delete();

        return redirect()->route('admin.roles.index')->with('success', 'Role deleted successfully');
    }

    public function data()
    {
        $roles = $this->role->all();

        return DataTables::of($roles)
            ->addIndexColumn()
            ->addColumn('action', function ($row) {
                return view('admin.roles.action', ['model' => $row]);
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    protected function validateRequest(Request $request, $id = null)
    {
        $rules = [
            'name' => 'required|unique:roles,name,' . $id,
            'guard_name' => 'required',
            'permissions' => 'nullable|array',
            'permissions.*' => 'exists:permissions,id'
        ];

        return $request->validate($rules);
    }

    protected function syncPermissions($role, $permissionIds)
    {
        if ($permissionIds) {
            // Get permission names from IDs
            $permissionNames = Permission::whereIn('id', $permissionIds)->pluck('name')->toArray();
            $role->syncPermissions($permissionNames);
        } else {
            $role->syncPermissions([]);
        }
    }
}
