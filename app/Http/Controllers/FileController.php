<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\File;
use App\FileSetting;
use App\Company;

use Mail;
use Session;
use Validator;

class FileController extends Controller
{

    public function __construct () {
        $this->middleware(['auth', 'file_clearance']);

        // for active routing state
        \View::share('is_file_route', true);
    }

    public function index () {
        $files = File::select('id', 'file', 'from', 'to', 'company_id', 'remarks')
                ->with(['from_user' => function ($qry) {
                  $qry->select('id', \DB::raw('CONCAT(first_name," ",last_name) AS name'));
                }])
                ->with(['to_user' => function ($qry) {
                  $qry->select('id', \DB::raw('CONCAT(first_name," ",last_name) AS name'));
                }])
                ->with(['to_company' => function ($qry) {
                  $qry->select('id', 'name');
                }])
                ->get();
        $companies = Company::select('id', 'name')->orderBy('name', 'asc')->get();
        $users = User::select('id', 'first_name', 'last_name')->where('id', '!=', 1)->orderBy('first_name', 'asc')->get();
        return view('files.index', compact('files', 'companies', 'users'));
    }

    public function view () {
        $files = File::select('id', 'file', 'from', 'to', 'company_id', 'remarks')
                ->with(['from_user' => function ($qry) {
                  $qry->select('id', \DB::raw('CONCAT(first_name," ",last_name) AS name'));
                }])
                ->with(['to_user' => function ($qry) {
                  $qry->select('id', \DB::raw('CONCAT(first_name," ",last_name) AS name'));
                }])
                ->with(['to_company' => function ($qry) {
                  $qry->select('id', 'name');
                }])
                ->where('to', \Auth::user()->id)
                ->orWhere('company_id', \Auth::user()->company_id)
                ->get();
        $file_settings = FileSetting::where('user_id', \Auth::user()->id)->first();
        return view('files.view', compact('files', 'file_settings'));
    }

    public function edit ($id) {
        $file = File::select('id', 'file', 'from', 'to', 'company_id', 'remarks')->where('id', $id)->first();
        $companies = Company::select('id', 'name')->orderBy('name', 'asc')->get();
        $users = User::select('id', 'first_name', 'last_name')->where('id', '!=', 1)->orderBy('first_name', 'asc')->get();
        return view('files.edit', compact('file', 'companies', 'users'));
    }

    public function edit_ajax ($id) {
        $file = File::select('to', 'company_id')->where('id', $id)->first();
        if ($file->to) {
          $response = [
            'to' => 'user',
            'id' => $file->to,
          ];
        } else {
          $response = [
            'to' => 'company',
            'id' => $file->company_id,
          ];
        }

        return response()->json($response, 200);
    }

    public function update (Request $req, $id) {
        $file = File::find($id);
        $rules = [
          'file' => 'sometimes|max:10240',
          'remarks' => 'required',
        ];
        $messages = [
          'file.max' => 'The file may not be greater than 10 MB.',
        ];
        $validator = Validator::make($req->all(), $rules, $messages);
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

        if ($req->file) {
            // Store file into folder
            $req_file = $req->file('file');
            $file_name = $req_file->getClientOriginalName();
            if ($req->user) {
              $end_path = 'user/' . $req->user;
            } else {
              $end_path = 'company/' . $req->company;
            }
            $file_path = 'uploads/files/' . $end_path;
            $req_file->storeAs($file_path, $file_name);

            // insert file
            $file->file = $file_name;
        }

        $file->from = \Auth::user()->id;
        $file->to = $req->user;
        $file->company_id = $req->company;
        $file->remarks = $req->remarks;
        $file->update();

        $flash_message = [
            'title' => 'Well done!',
            'status' => 'success',
            'message' => 'One (1) record has been successfully updated.',
        ];
        Session::flash('update_success', $flash_message);
        return redirect()->route('files.index');
    }

    public function trash ($id) {
        $file = File::select('id', 'file', 'from', 'to', 'company_id', 'remarks')
                ->with(['from_user' => function ($qry) {
                  $qry->select('id', \DB::raw('CONCAT(first_name," ",last_name) AS name'));
                }])
                ->with(['to_user' => function ($qry) {
                  $qry->select('id', \DB::raw('CONCAT(first_name," ",last_name) AS name'));
                }])
                ->with(['to_company' => function ($qry) {
                  $qry->select('id', 'name');
                }])
                ->where('id', $id)
                ->first();
        return view('files.trash', compact('file'));
    }

    public function delete ($id) {
        $file = File::find($id);
        $file->delete();
        $flash_message = [
            'title' => 'Well done!',
            'status' => 'success',
            'message' => 'One (1) record has been successfully deleted.',
        ];
        Session::flash('delete_success', $flash_message);
        return redirect()->route('files.index');
    }

    public function download ($id) {
        $file = File::where('id', $id)->first();
        if ($file->to) {
          $path = 'user/' . $file->to;
        } else {
          $path = 'company/' . $file->company_id;
        }
        $file = $file->file;
        return response()->download(storage_path("app/uploads/files/{$path}/{$file}"));
    }





    // AJAX STORE
    public function store_ajax (Request $req) {
        $rules = [
          'file' => 'required|max:10240',
          'remarks' => 'required',
        ];
        $messages = [
          'file.max' => 'The file may not be greater than 10 MB.',
        ];
        $validator = Validator::make($req->all(), $rules, $messages);

        if ($validator->fails()) {
          return response()->json(['validator' => $validator->errors()], 422);
        }

        // Store file into folder
        $req_file = $req->file('file');
        $file_name = $req_file->getClientOriginalName();
        if ($req->user) {
          $end_path = 'user/' . $req->user;
        } else {
          $end_path = 'company/' . $req->company;
        }
        $file_path = 'uploads/files/' . $end_path;
        $req_file->storeAs($file_path, $file_name);

        $file = new File;
        $file->file = $file_name;
        $file->from = \Auth::user()->id;
        $file->to = $req->user;
        $file->company_id = $req->company;
        $file->remarks = $req->remarks;
        $file->save();

        $response = \DB::table('files')
                    ->select('files.id',
                             'files.file',
                             \DB::raw('(SELECT CONCAT(first_name," ",last_name) FROM users WHERE id=files.from) AS `from`'),
                             \DB::raw('(SELECT CONCAT(first_name," ",last_name) FROM users WHERE id=files.to) AS `to`'),
                             \DB::raw('(SELECT name FROM companies WHERE id=files.company_id) AS `company`'),
                             'files.remarks')
                    ->where('id', $file->id)
                    ->first();

        $sender = \Auth::user()->first_name . ' ' . \Auth::user()->last_name;
        $sender_email = \Auth::user()->extn_email1 ? \Auth::user()->extn_email1 : 'marylouursante@addessa.com';
        if ($file->to) {
          $to = $file->to_user->first_name . ' ' . $file->to_user->last_name;
        } else {
          $to = $file->to_company->name;
        }

        if ($file->to) {
          $email_addresses = \DB::table('file_settings')
                             ->select('user_id',
                                      \DB::raw('(SELECT CONCAT_WS(",",extn_email1,extn_email2,extn_email3) FROM users WHERE id=user_id) AS emails'))
                             ->where('email_notif', 1)
                             ->where('user_id', $file->to)
                             ->first();
          $email_addresses = $email_addresses ? explode(",",$email_addresses->emails) : null;
        } elseif ($file->company_id) {
          $addresses = \DB::table('file_settings AS fs')
                             ->select('u.extn_email1',
                                      'u.extn_email2',
                                      'u.extn_email3')
                             ->join('users AS u', 'u.id', '=', 'fs.user_id')
                             ->join('companies AS c', 'c.id', '=', 'u.company_id')
                             ->where('fs.email_notif', 1)
                             ->where('c.id', $file->company_id)
                             ->get();
          $email_addresses = [];
          foreach ($addresses as $address) {
            array_push($email_addresses, $address->extn_email1, $address->extn_email2, $address->extn_email3);
          }
          // filter for null and get only unique values then flatten into array again
          $email_addresses = collect(array_unique(array_filter($email_addresses)))->flatten()->all();
        }
        // return response()->json(['response' => $email_addresses], 422);

        if (!empty($email_addresses)) {
          $data = array(
            'sender' => 'ADDESSA SUPPLIER PORTAL - ' . \Carbon\Carbon::now()->toDateTimeString(),
            'subject' => $req->remarks,
            'file' => $file_name,
            'to' => $to,
            'from' => $sender,
            'from_email' => $sender_email,
          );

          Mail::send('files.emails.send', $data, function ($message) use ($data, $email_addresses) {
            $message->from($data['from_email'], $data['sender']);
            $message->to($email_addresses)->subject($data['subject']);
          });

          if (Mail::failures()) {
            return "Fail in sending email";
          }
        }

        return response()->json(['response' => $response], 200);
    }
}