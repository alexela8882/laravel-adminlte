@extends('layouts.app')

@section('title', 'Edit User')

@section('content')
<div class="content-wrapper">
  <section class="content-header">
    <h1>
      Users
      <small>Manage user accounts</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="{{ route('users.index') }}">Users</a></li>
      <li class="active">Edit</li>
    </ol>
  </section>

  <section class="content">
  	<div class="row">
  		<div class="col-md-12">
  			<div class="box">
          <div class="box-body">
          	@include ('errors.list')
						@include ('successes.list')
				    <div class="row">
				      <div class="col-md-6">
				        <div class="box box-warning box-solid">
				          <div class="box-header with-border">
				            <h3 class="box-title">Edit user</h3>
				          </div>
				          <form method="post" action="{{ route('user.update', ['id' => $user->id]) }}">
										{{ csrf_field() }}
										<div class="box-body">
											<div class="row">
												<div class="col-md-6">
													<div class="form-group {{ $errors->has('first_name') ? 'has-error' : '' }}">
														<label>First Name</label>
														<input class="form-control" type="text" name="first_name" value="{{ $user->first_name }}" placeholder="First Name" autofocus>
														@if ($errors->has('first_name'))
															<span class="form-text text-danger">
																{{ $errors->first('first_name') }}
															</span>
														@endif
													</div>
												</div>

												<div class="col-md-6">
													<div class="form-group {{ $errors->has('last_name') ? 'has-error' : '' }}">
														<label>Last Name</label>
														<input class="form-control" type="text" name="last_name" value="{{ $user->last_name }}" placeholder="Last Name">
														@if ($errors->has('last_name'))
															<span class="form-text text-danger">
																{{ $errors->first('last_name') }}
															</span>
														@endif
													</div>
												</div>
											</div>

											<div class="row">
												<div class="col-md-6">
													<div class="form-group {{ $errors->has('username') ? 'has-error' : '' }}">
														<label>Username</label>
														<input class="form-control" type="username" name="username" value="{{ $user->username }}" placeholder="Username">
														@if ($errors->has('username'))
															<span class="form-text text-danger">
																{{ $errors->first('username') }}
															</span>
														@endif
													</div>
												</div>
												<div class="col-md-6">
													<div class="form-group {{ $errors->has('username') ? 'has-error' : '' }}">
														<label>Company</label>
														<select class="form-control" name="company">
															@foreach ($companies as $company)
																<option {{ $company->id == $user->company_id ? 'selected' : '' }}
																				value="{{ $company->id }}">
																	{{ $company->name }}
																</option>
															@endforeach
														</select>
													</div>
												</div>
											</div>
										</div>

										<div class="box-footer">
											<button type="submit" name="savebtn" value=0 class="btn btn-primary">Update</button>
											<a href="{{ route('users.index') }}" class="btn btn-default pull-right">Cancel</a>
										</div>
									</form>
				        </div>
				      </div>

				      <div class="col-md-6">
				        <div class="box box-danger box-solid">
				          <div class="box-header with-border">
				            <h3 class="box-title">Change Password</h3>
				          </div>
				          <form method="post" action="{{ route('user.password_reset', ['id' => $user->id]) }}">
										{{ csrf_field() }}
										<div class="box-body">

											@if (\Session::has('user_password_reset_fail'))
												<div class="alert alert-dismissible alert-{{ \Session::get('user_password_reset_fail.status') }}">
												  <button type="button" class="close" data-dismiss="alert">&times;</button>
												  <strong>{{ \Session::get('user_password_reset_fail.title') }}</strong> {{ \Session::get('user_password_reset_fail.message') }}
												</div>
											@endif

											<div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
												<label>Password</label>
												<input class="form-control" type="password" name="password" placeholder="Password">
												@if ($errors->has('password'))
													<span class="form-text text-danger">
														{{ $errors->first('password') }}
													</span>
												@endif
											</div>
										</div>

										<div class="box-footer">
											<button type="submit" class="btn btn-danger">Proceed</button>
										</div>
									</form>
				        </div>
				      </div>
				    </div>
				  </div>
			  </div>
		  </div>
		</div>
  </section>
</div>
@endsection