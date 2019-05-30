<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;

//Importing laravel-permission models
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

use Session;
use Validator;

class PermissionController extends Controller {

    public function __construct() {
        $this->middleware(['auth', 'admin']); //admin middleware lets only users with a //specific permission permission to access these resources

        // for active routing state
        \View::share('is_permission_route', true);
    }

    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index() {
        $permissions = Permission::where('id', '!=', 1)->get(); //Get all permissions
        return view('permissions.index')->with('permissions', $permissions);
    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create() {
        $roles = Role::get(); //Get all roles
        return view('permissions.create')->with('roles', $roles);
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request) {
        $rules = [
          'name'=>'required|unique:permissions',
        ];
        $messages = [
          'name.unique' => 'The permission ' . $request->name . ' is already in our database. ' . 'Please choose another.',
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
        $permission = new Permission();
        $permission->name = $name;

        $roles = $request['roles'];

        $permission->save();

        if (!empty($request['roles'])) { //If one or more role is selected
            foreach ($roles as $role) {
                $r = Role::where('id', '=', $role)->firstOrFail(); //Match input role to db record

                $permission = Permission::where('name', '=', $name)->first(); //Match input //permission to db record
                $r->givePermissionTo($permission);
            }
        }

        $flash_message = [
          'title' => 'Well done!',
          'status' => 'success',
          'message' => $request->name . ' permission has been successfully added into our database.',
        ];

        Session::flash('create_success', $flash_message);

        if ($request->savebtn == 0) {
          return redirect()->route('permissions.create');
        } else {
          return redirect()->route('permissions.index');
        }

    }

    /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function show($id) {
        return redirect('permissions');
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function edit($id) {
        $permission = Permission::where('id', $id)->where('id', '!=', 1)->first();
        return view('permissions.edit', compact('permission'));
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, $id) {
        $permission = Permission::where('id', $id)->where('id', '!=', 1)->first();

        $rules = [
          'name'=>'required|unique:permissions,name,'.$id,
        ];
        $messages = [
          'name.unique' => $request->name . ' permission is already in our database. ' . 'Please choose another.',
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

        $input = $request->all();
        $permission->fill($input)->save();

        $flash_message = [
          'title' => 'Well done!',
          'status' => 'success',
          'message' => 'Existing permission has been successfully updated.',
        ];

        Session::flash('update_success', $flash_message);
        return redirect()->route('permissions.index');

    }

    public function trash ($id) {
      $permission = Permission::findOrFail($id);
      return view('permissions.trash', compact('permission'));
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function destroy($id) {
        $permission = Permission::findOrFail($id);

        //Make it impossible to delete this specific permission: Administer roles & permissions
        if ($permission->id === 1) {
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
          'message' => $permission->name . ' permission has been successfully deleted from our database.',
        ];
        Session::flash('delete_success', $flash_message);

        $permission->delete();
        return redirect()->route('permissions.index');
    }









    // DATATABLES AJAX CRUD
    public function store_ajax (Request $req) {
      $validator = Validator::make($req->all(), [
                'name' => 'required|unique:permissions,name',
            ]);

      if ($validator->fails()) {
        return response()->json(['validator' => $validator->errors()], 422);
      }

      $permission = new Permission;
      $permission->name = $req->name;
      $permission->save();

      // get currently saved data
      $response = Permission::where('id', $permission->id)
                  ->select('id', 'name')->first();

      return response()->json(['response' => $response], 200);
  }
}
