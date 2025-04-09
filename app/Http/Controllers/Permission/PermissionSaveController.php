<?php

declare(strict_types=1);

namespace App\Http\Controllers\Permission;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSaveController extends Controller
{
    public function save(Request $request): RedirectResponse
    {
        $roles = $request->roles;
        //dd($roles);

        foreach ($roles as $role_id => $permissions) {
            $permissionsCollection = Permission::whereIn('id', $permissions)->get(); 
            Role::findById($role_id)->syncPermissions($permissionsCollection);
        }

        return redirect('/permission');
    }
}
