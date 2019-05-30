@extends('layouts.app')

@section('title', 'Edit Role')

@section('content')
<div class="content-wrapper">
  <section class="content-header">
    <h1>
    	Roles
    	<small>Manage role</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="{{ route('roles.index') }}">Roles</a></li>
      <li class="active">Edit</li>
    </ol>
  </section>

  <section class="content">
    <div class="row mt-4 mb-4">
			<div class="col-md-12">
				<div class="box box-warning">
          <div class="box-header with-border">
            <h3 class="box-title">
            	Edit role
            </h3>
          </div>
          {{ Form::model($role, array('route' => array('roles.update', $role->id), 'method' => 'POST')) }}
						{{ csrf_field() }}
						<div class="box-body">
							<div class="row">
		        		<div class="col-md-5">
				          @include ('errors.list')
				          @include ('successes.list')

									<div class="form-group {{ $errors->has('name') ? 'has-danger' : '' }}">
										<label>Name</label>
										<input class="form-control" type="text" name="name" value="{{ $role->name }}" placeholder="Name" readonly autofocus>
										@if ($errors->has('name'))
											<span class="form-text text-danger">
												{{ $errors->first('name') }}
											</span>
										@endif
									</div>

				          <div class="form-group {{ $errors->has('permissions') ? 'has-danger' : '' }}">
										<label>Permissions</label>
										@if ($role->id === 1)
											<input type="hidden" name="permissions[]" value="1" readonly>
										@endif
				            @foreach ($permissions as $permission)
				            	<div class="checkbox icheck">
				            		<label>
					              	{{Form::checkbox('permissions[]',
					              		$permission->id, $role->permissions,
					              		$role->id === 1 && $permission->id === 1 ? array('disabled') : false ) }}
					              	{{ $permission->name }}
				              	</label>
				              </div>
				            @endforeach

				            @if ($errors->has('permissions'))
											<span class="form-text text-danger">
												{{ $errors->first('permissions') }}
											</span>
										@endif
									</div>
								</div>
							</div>
		        </div>

		        <div class="box-footer">
		        	<div class="row">
		        		<div class="col-md-5">
									<button type="submit" class="btn btn-primary">Update</button>
									<a href="{{ route('roles.index') }}" class="btn btn-default pull-right">Back</a>
								</div>
							</div>
						</div>
		      {{ Form::close() }}
				</div>
			</div>
		</div>
	</section>
</div>
@stop

@push('scripts')
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_flat-blue',
    });
  });
</script>
@endpush
