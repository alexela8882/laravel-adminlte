<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
//Importing laravel-permission models
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use Session;
use Validator;

class RoleController extends Controller {

    public function __construct() {
        $this->middleware(['auth', 'admin']);//isAdmin middleware lets only users with a //specific permission permission to access these resources

        // for active routing state
        \View::share('is_role_route', true);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $roles = Role::all();//Get all roles

        return view('roles.index')->with('roles', $roles);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create() {
        $permissions = Permission::all();//Get all permissions

        return view('roles.create', ['permissions'=>$permissions]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store (Request $request) {
        //Validate name and permissions field
        $rules = [
          'name'=>'required|unique:roles',
          'permissions' =>'required',
        ];
        $messages = [
          'name.unique' => 'The role ' . $request->name . ' is already in our database. ' . 'Please choose another.',
          'permissions.required' => 'You must select at least one (1) permission.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
      	if ($validator->fails()) {
      		$flash_message = [
      			'title' => 'Oops!',
      			'status' => 'danger',
      			'message' => 'Please correct all the errors below.',
      		];
      		Session::flash('create_fail', $flash_message);
      		return redirect()->back()
      						 ->withErrors($validator)
      						 ->withInput();
      	}

        $name = $request['name'];
        $role = new Role();
        $role->name = $name;

        $permissions = $request['permissions'];

        $role->save();
        //Looping thru selected permissions
        foreach ($permissions as $permission) {
          $p = Permission::where('id', '=', $permission)->firstOrFail();
          //Fetch the newly created role and assign permission
          $role = Role::where('name', '=', $name)->first();
          $role->givePermissionTo($p);
        }

        $flash_message = [
          'title' => 'Well done!',
          'status' => 'success',
          'message' => $request->name . ' role has been successfully added into our database.',
        ];

        Session::flash('create_success', $flash_message);

        if ($request->savebtn == 0) {
          return redirect()->route('roles.create');
        } else {
          return redirect()->route('roles.index');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id) {
        return redirect('roles');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id) {
        $role = Role::findOrFail($id);
        $permissions = Permission::all();

        return view('roles.edit', compact('role', 'permissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) {
        $role = Role::findOrFail($id);//Get role with the given id
        //Validate name and permission fields
        $rules = [
          'name'=>'required|unique:roles,name,'.$id,
          'permissions' =>'required',
        ];
        $messages = [
          'name.unique' => 'The role ' . $request->name . ' is already in our database. ' . 'Please choose another.',
          'permissions.required' => 'You must select at least one (1) permission.',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
      	if ($validator->fails()) {
      		$flash_message = [
      			'title' => 'Oops!',
      			'status' => 'danger',
      			'message' => 'Please correct all the errors below.',
      		];
      		Session::flash('update_fail', $flash_message);
      		return redirect()->back()
      						 ->withErrors($validator)
      						 ->withInput();
      	}

        $input = $request->except(['permissions', 'savebtn']);
        $permissions = $request['permissions'];
        $role->fill($input)->save();

        $p_all = Permission::all();//Get all permissions

        foreach ($p_all as $p) {
            $role->revokePermissionTo($p); //Remove all permissions associated with role
        }

        foreach ($permissions as $permission) {
            $p = Permission::where('id', '=', $permission)->firstOrFail(); //Get corresponding form //permission in db
            $role->givePermissionTo($p);  //Assign permission to role
        }

        $flash_message = [
          'title' => 'Well done!',
          'status' => 'success',
          'message' => 'Existing role has been successfully updated.',
        ];

        Session::flash('update_success', $flash_message);
        return redirect()->route('roles.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function trash ($id) {
      $role = Role::where('id', $id)->with('permissions')->first();
      return view('roles.trash', compact('role'));
    }

    public function destroy($id) {
        $role = Role::findOrFail($id);

        //Make it impossible to delete this specific role: Super Admin
        if ($role->id === 1) {
          $flash_message = [
            'title' => 'Oops!',
            'status' => 'danger',
            'message' => 'Cannot delete this permission!',
          ];
          Session::flash('delete_fail', $flash_message);
          return redirect()->route('permissions.index');
        }

        $flash_message = [
          'title' => 'Well done!',
          'status' => 'success',
          'message' => 'Role' . $role->name . ' has been successfully deleted from our database.',
        ];
        Session::flash('delete_success', $flash_message);
        $role->delete();

        return redirect()->route('roles.index');
    }
}
