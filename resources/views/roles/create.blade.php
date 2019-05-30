@extends('layouts.app')

@section('title', 'Add Role')

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
      <li class="active">Add new</li>
    </ol>
  </section>

  <section class="content">
    <div class="row mt-4 mb-4">
			<div class="col-md-12">
				<div class="box box-primary">
          <div class="box-header with-border">
            <h3 class="box-title">
            	Add role
            </h3>
          </div>
          <form method="post" action="{{ route('roles.store') }}">
						{{ csrf_field() }}
						<div class="box-body">
							<div class="row">
		        		<div class="col-md-5">
				          @include ('errors.list')
				          @include ('successes.list')

									<div class="form-group {{ $errors->has('name') ? 'has-danger' : '' }}">
										<label>Name</label>
										<input class="form-control" type="text" name="name" value="{{ old('name') }}" placeholder="Name" autofocus>
										@if ($errors->has('name'))
											<span class="form-text text-danger">
												{{ $errors->first('name') }}
											</span>
										@endif
									</div>

				          <div class="form-group {{ $errors->has('permissions') ? 'has-danger' : '' }}">
										<label>Permissions</label>
				            @foreach ($permissions as $permission)
				            	<div class="checkbox icheck">
												<label>
													{{Form::checkbox('permissions[]',  $permission->id ) }}
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
									<button value=0 name="savebtn" type="submit" class="btn btn-primary">Save & Add new</button>
									<button value=1 name="savebtn" type="submit" class="btn btn-danger">Save & Return</button>
									<a href="{{ route('roles.index') }}" class="btn btn-default pull-right">Back</a>
								</div>
							</div>
						</div>
		      </form>
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
