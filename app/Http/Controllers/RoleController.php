<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller {

    public function logged_user()
    {
        $user = new User();
        return  $user->logged_user();
    }
	public function index() {
		return Role::all();
	}

	public function permissions($guard_name)
	{
		return Permission::where('guard_name', $guard_name)->get();
	}

	public function store(Request $request)
	{
        // return $request->all();
        $role = Role::create(['guard_name' => 'web','name' => $request->form['name']]);
        $role->givePermissionTo($request->selected);
		return $role;
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \App\Role  $role
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id) {
		// return $request->all();
		$role = Role::find($id);
		$role->name = $request->form['name'];
		$role->save();
		$role->syncPermissions($request->selected);
		return $role;
	}

	public function destroy(Role $role) {
		// return $role->id;
		Role::find($role->id)->delete();
	}


	public function getRolesPerm($guard, $id)
	{
		// dd($guard, $id);
		// return $request->all();
		return Role::find($id)->permissions->pluck('name');
	}
}
