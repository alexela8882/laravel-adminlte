<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use Session;
use Validator;

//Importing laravel-permission models
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AuthorizationController extends Controller
{

    public function __construct () {
      $this->middleware(['auth', 'user_auth_clearance']);
      // for active routing state
      \View::share('is_auth_route', true);
    }

    public function index () {
      $users = User::orderBy('first_name', 'asc')
                      ->select(['users.id',
                                'users.first_name',
                                'users.last_name',
                                'users.username'])
                      ->where('id', '!=', 1)
                      ->get();
      return view('users.authorizations.index', compact('users'));
    }

    public function edit($id) {
        if ($id == 1) {
            abort('403');
        } else {
            $user = User::findOrFail($id);
            $roles = Role::get();
            return view('users.authorizations.edit', compact('user', 'roles'));
        }
    }

    public function update (Request $request, $id) {
        if ($id == 1) {
            abort('403');
        } else {
            $user = User::findOrFail($id);
            $roles = $request['roles'];
            if (isset($roles)) {
                $user->roles()->sync($roles);  //If one or more role is selected associate user to roles
            }
            else {
                $user->roles()->detach(); //If no role is selected remove exisiting role associated to a user
            }

            $flash_message = [
              'title' => 'Well done!',
              'status' => 'success',
              'message' => 'User authorization has been successfully updated.',
            ];
            Session::flash('update_success', $flash_message);

            return redirect()->route('authorizations.index');
        }
    }
}
