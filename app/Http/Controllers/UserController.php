<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

use App\User;
use App\Company;
use App\FileSetting;

use Session;
use Validator;
use Excel;

class UserController extends Controller
{

    CONST CACHE_KEY = 'USERS';

    public function __construct () {
        $this->middleware(['auth', 'user_clearance']);

        // for active routing state
        \View::share('is_user_route', true);
    }

    public function index () {
        $companies = Company::select('id', 'name')->orderBy('name', 'asc')->get();
        return view('users.index', compact('companies'));
    }

    public function create () {
        return view('users.create');
    }

    public function store (Request $req) {
        $validator = Validator::make($req->all(), [
                'first_name' => 'required',
                'last_name' => 'required',
                'username' => 'required|unique:users,username',
                'password' => 'required',
            ]);

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

      	$user = new User;
      	$user->first_name = $req->first_name;
      	$user->last_name = $req->last_name;
      	$user->username = $req->username;
      	$user->password = bcrypt($req->password);
      	$user->save();

        // forget users cache after store
        Cache::forget('USERS');

      	$flash_message = [
        		'title' => 'Well done!',
        		'status' => 'success',
        		'message' => 'New record has been successfully added to our database.',
      	];
      	Session::flash('create_success', $flash_message);
      	if ($req->savebtn == 0) {
      		  return redirect()->route('user.create');
      	} else {
      		  return redirect()->route('users.index');
      	}
    }

    public function edit ($id) {
        if ($id == 1) { // avoid delete first account
            abort('403');
        } else {
            $companies = Company::select('id', 'name')->orderBy('name', 'asc')->get();
          	$user = User::where('id', $id)
                    ->select('id', 'company_id', 'first_name', 'last_name', 'username')
                    ->with(['company' => function ($qry) {
                      $qry->select('id', 'name');
                    }])
                    ->first();
          	return view('users.edit', compact('user', 'companies'));
        }
    }

    public function update ($id, Request $req) {
        if ($id == 1) {
            abort('403');
        } else {
            $validator = Validator::make($req->all(), [
                    'first_name' => 'required',
                    'last_name' => 'required',
                    'username' => 'required|unique:users,username,'.$id,
                ]);

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

          	$user = User::find($id);
            $user->company_id = $req->company;
          	$user->first_name = $req->first_name;
          	$user->last_name = $req->last_name;
          	$user->username = $req->username;
          	$user->update();

            // forget users cache after update
            Cache::forget('USERS');

          	$flash_message = [
        			'title' => 'Well done!',
        			'status' => 'success',
        			'message' => 'One (1) record has been successfully updated.',
        		];
        		Session::flash('update_success', $flash_message);
        		return redirect()->route('users.index');
        }
    }

    public function password_reset ($id, Request $req) {
      	$validator = Validator::make($req->all(), [
      			'password' => 'required',
      		]);
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

      	$user = User::find($id);
      	$user->password = bcrypt($req->password);
      	$user->update();

      	$flash_message = [
    			'title' => 'Well done!',
    			'status' => 'success',
    			'message' => 'Password has been successfully updated.',
    		];
    		Session::flash('update_success', $flash_message);
    		return redirect()->route('users.index');
    }

    public function trash ($id) {
        if ($id == 1) {
            abort('403');
        } else {
            $user = User::find($id);
            return view('users.trash', compact('user'));
        }
    }

    public function delete ($id) {
        if ($id == 1) {
            abort('403');
        } else {
            User::find($id)->delete();
            $flash_message = [
                'title' => 'Well done!',
                'status' => 'success',
                'message' => 'One (1) record has been successfully deleted.',
            ];
            Session::flash('delete_success', $flash_message);

            // forget users cache after delete
            Cache::forget('USERS');
            return redirect()->route('users.index');
        }
    }

    public function profile () {
        $profile = User::select('id',
                                'company_id',
                                'extn_email1',
                                'extn_email2',
                                'extn_email3')
                   ->with(['company' => function ($qry) {
                      $qry->select('id', 'address');
                   }])
                   ->where('id', \Auth::user()->id)
                   ->first();
        return view('users.profile', compact('profile'));
    }

    public function profile_update (Request $req) {
        $validator = Validator::make($req->all(), [
            'company_address' => 'required',
          ]);

        if ($validator->fails()) {
          $flash_message = [
            'title' => 'Oops!',
            'message' => 'Please correct all the errors below.',
            'status' => 'danger',
          ];
          Session::flash('update_fail', $flash_message);
          return redirect()->back()
                           ->withErrors($validator);
        }

        // update company address
        if (count(\Auth::user()->company) > 0) {
          $company = Company::find(\Auth::user()->company->id);
          $company->address = $req->company_address;
          $company->update();
        } else {
          $company = new Company;
          $company->name = 'New Company' . \Auth::user()->id;
          $company->address = $req->company_address;
          $company->save();
        }

        // update profile
        $profile = User::find(\Auth::user()->id);
        $profile->company_id = $company->id;
        $profile->extn_email1 = $req->extn_email1;
        $profile->extn_email2 = $req->extn_email2;
        $profile->extn_email3 = $req->extn_email3;
        $profile->update();

        $extn_email = User::where('id', \Auth::user()->id)->select('extn_email1', 'extn_email2', 'extn_email3')->first();
        $extn_email = collect($extn_email->toArray())->flatten()->all();

        if (empty(array_filter($extn_email))) {
          // delete file setting of user when no extn. email
          FileSetting::where('user_id', \Auth::user()->id)->delete();
        }

        $flash_message = [
          'title' => 'Well Done!',
          'message' => 'Your profile has been successfully updated.',
          'status' => 'success',
        ];
        Session::flash('update_success', $flash_message);
        return redirect()->route('user.profile');
    }

    public function changepass (Request $req) {
        $rules = [
            'currentpassword' => 'required|max:255',
            'newpassword' => 'required|min:6|max:255|confirmed',
        ];

        $message = [
            'currentpassword.required' => 'The current password field is required.',
            'newpassword.required' => 'The new password field is required.',
            'newpassword.confirmed' => 'The new password confirmation does not match. ',
            'newpassword.min' => 'The new password must be at least 6 characters. ',
        ];
        $validator = Validator::make($req->all(), $rules, $message);
        $validator->after(function($validator) use($req) {
            if (!\Hash::check($req->get('currentpassword'), \Auth::user()->password)) {
                $validator->errors()->add('currentpassword', 'Current password don\'t match in our database.');
            }
        });
        if ($validator->fails()) {
            $flash_message = [
                'title' => 'Oops!',
                'status' => 'danger',
                'message' => 'Please correct all the errors below.',
            ];
            Session::flash('update_fail', $flash_message);
            return redirect()->back()
                             ->withErrors($validator);
        }

        $user = User::find(\Auth::user()->id);
        $user->password = bcrypt($req->newpassword);
        $user->update();

        // logout user after password change
        \Auth::logout();
        $flash_message = [
            'title' => 'Well done!',
            'status' => 'success',
            'message' => 'Your password has already been changed. Please sign in.',
        ];
        Session::flash('update_success', $flash_message);
        return redirect()->route('home');
    }













    // AJAX INDEX
    public function index_ajax () {
        $users = \DB::table('users')
                 ->select('users.id AS id',
                          \DB::raw('CONCAT(first_name," ",last_name) AS name'),
                          'users.username',
                          \DB::raw('(SELECT name FROM companies WHERE id=users.company_id) AS company'),
                          'users.company_id AS company_id')
                 ->where('username', '<>', 'alexela8882')
                 ->get();

        return response()->json(['data' => $users], 200);
        
    }

    // AJAX STORE
    public function store_ajax (Request $req) {
        // return response()->json(['response' => $req->all()], 200);
        $rules = [
          'first_name' => 'required',
          'last_name' => 'required',
          'username' => 'required|unique:users,username',
          'password' => 'required',
        ];
        $messages = [
          'username.required' => 'The username field is required.',
        ];
        $validator = Validator::make($req->all(), $rules, $messages);

        if (!$req->company) {
          // COMPANY DUPLICATE
          $check_company = Company::where('name', $req->company_input)->first();
          if (count($check_company) > 0) {
              $duplicate = 'Duplicate! Please choose another name.';
              $validator->after(function ($validator) use ($duplicate) {
                  $validator->getMessageBag()->add('company_input', $duplicate);
              });
          }

          // COMPANY EMPTY
          if (!$req->company_input) {
            $empty = 'This field is required.';
            $validator->after(function ($validator) use ($empty) {
                $validator->getMessageBag()->add('company_input', $empty);
            });
          }

          // COMPANY ADDRESS EMPTY
          if (!$req->address_input) {
            $empty = 'This field is required.';
            $validator->after(function ($validator) use ($empty) {
                $validator->getMessageBag()->add('address_input', $empty);
            });
          }
        }

        if ($validator->fails()) {
          // COMPANY
          if ($req->company) {
              Session::flash('company_select', 1);
          } else {
              Session::flash('company_select', 0);
          }
          return response()->json(['validator' => $validator->errors()], 422);
        }

        // NEW COMPANY
        if ($req->company_input) {
            $company = new Company;
            $company->name = $req->company_input;
            $company->address = $req->address_input;
            $company->save();
        }

        $user = new User;
        // PUT COMPANY
        if ($req->company) {
            $user->company_id = $req->company;
        } else {
            $user->company_id = $company->id;
        }
        $user->first_name = $req->first_name;
        $user->last_name = $req->last_name;
        $user->username = $req->username;
        $user->password = bcrypt($req->password);
        $user->save();

        // get currently saved data
        $response = \DB::table('users')
                    ->select('users.id AS id',
                             'users.username',
                             'users.company_id AS company_id',
                             \DB::raw('CONCAT(first_name," ",last_name) AS name'),
                             \DB::raw('(SELECT name FROM companies WHERE id=users.company_id) AS company'))
                    ->where('id', $user->id)
                    ->first();

        return response()->json(['response' => $response], 200);
    }
}
