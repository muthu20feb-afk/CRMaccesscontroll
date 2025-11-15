<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;
use Yajra\DataTables\Facades\DataTables;
class PermissionController extends Controller
{
    public function manage()
{
    $permissions = Permission::all();
    return view('permissions.manage', compact('permissions'));
}

public function update(Request $request, $id)
{
    dd('');
    $permission = Permission::findOrFail($id);
    $request->validate(['name' => 'required|string|max:255']);
    
    $permission->update(['name' => $request->name]);

    return redirect()->back()->with('success', 'Permission updated successfully!');
}

}