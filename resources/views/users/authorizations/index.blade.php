@extends('layouts.app')

@section('title', 'User Authorizations')

@section('content')
<div class="content-wrapper">
  <section class="content-header">
    <h1>
      Users
      <small>Manage user authorizations</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="{{ route('users.index') }}">Users</a></li>
      <li class="active">Authorization</li>
    </ol>
  </section>

  <section class="content">
    <div class="row mt-4 mb-4">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title">Authorization</h3>
          </div>
          <div class="box-body table-responsive">
          	@include ('errors.list')
						@include ('successes.list')
          	<table class="table table-bordered table-hover" id="auth-table">
							<thead>
								<tr>
									<th>#</th>
									<th>Name</th>
									<th>Username</th>
									<th>Role</th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($users as $index => $user)
									<tr>
										<td class="align-top">{{ $index + 1 }}</td>
										<td class="align-top">{{ $user->first_name }} {{ $user->last_name }}</td>
										<td class="align-top">{{ $user->username }}</td>
			              <td class="align-top">
			                <?php
												$roles = $user->roles()->pluck('name')->implode('<br>');
												echo nl2br($roles);
											?>
			              </td>
										<td class="align-top">
											<a href="{{ route('authorization.edit', ['id' => $user->id]) }}" class="btn btn-default btn-xs" title="Edit"><i class="fa fa-pencil"></i></a>
										</td>
									</tr>
								@endforeach
							</tbody>
						</table>
          </div>
				</div>
			</div>
		</div>
	</section>
</div>
@stop

@push('scripts')
<script>
  $(function () {
    $('#auth-table').DataTable({
      'paging'      : true,
      'lengthChange': true,
      'searching'   : true,
      'ordering'    : true,
      'info'        : true,
      'select'      : true,
      'responsive'  : true,
      'scrollY'       : "300px",
      dom: "<'row'<'col-sm-3'l><'col-sm-6 text-center'B><'col-sm-3'f>>" +
           "<'row'<'col-sm-12'tr>>" +
           "<'row'<'col-sm-5'i><'col-sm-7'p>>",
      lengthMenu: [
          [ 10, 25, 50, -1 ],
          [ '10', '25', '50', '100', 'All' ]
      ],
      buttons: [
          {
              extend: 'excelHtml5',
              exportOptions: {
                  columns: ':visible'
              }
          },
          'colvis'
      ]
    })
  })
</script>
@endpush
