@extends('layouts.app')

@section('title', 'Users')

@section('content')
<div class="content-wrapper">
  <section class="content-header">
    <h1>
      Users
      <small>Manage user accounts</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Users</li>
    </ol>
  </section>

  <section class="content">
    <div class="row mt-4 mb-4">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title">
              User Accounts
            </h3>
            <div class="box-toolbar pull-right">
              <div class="btn-group">
                <a href="{{ route('authorizations.index') }}" class="btn btn-warning btn-xs">
                  <i class="fa fa-check"></i>&nbsp;
                    AUTHORIZATION
                </a>
              </div>
            </div>
          </div>
          <div class="box-body table-responsive">
            @include ('errors.list')
            @include ('successes.list')
            <table class="table table-bordered table-hover"
                   id="user-table"
                   data-operators='[{"url":"{{route('user.edit',['id'=>':id'])}}","text":"Edit","icon":"fa fa-pencil"},{"url":"{{route('user.trash',['id'=>':id'])}}","text":"Delete","icon":"fa fa-trash"}]'>
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Name</th>
                  <th>Username</th>
                  <th>Company</th>
                  <th>Actions</th>
                </tr>
              </thead>
            </table>
          </div>
        </div>
      </div>
    </div>
  </section>
</div>

<!-- Modal -->
<div id="user-modal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">ADD USER</h4>
      </div>

      <form method="post"
            action="{{ route("user.store-ajax") }}"
            data-select='["company"]'>
        <div class="modal-body">
          <div id="flash-message"></div>
          <div class="form-group">
            <label>First Name:</label>
            <input type="text" class="form-control" name="first_name" autofocus>
          </div>

          <div class="form-group">
            <label>Last Name:</label>
            <input type="text" class="form-control" name="last_name">
          </div>

          <div class="form-group">
            <label>Username:</label>
            <input type="text" class="form-control" name="username">
          </div>

          <div class="form-group">
            <label>Password:</label>
            <input type="password" class="form-control" name="password">
          </div>

          <div class="form-group">
            <label>Company:</label>
            <span id="company-select">
              <a id="add-company-btn" href="javascript:void(0);" class="pull-right"><em>(add new)</em></a>
              <select class="form-control company" name="company">
                @foreach ($companies as $company)
                  <option value="{{ $company->id }}">{{ $company->name }}</option>
                @endforeach
              </select>
            </span>

            <span id="company-input" style="display:none;">
              <a id="select-company-btn" href="javascript:void(0);" class="pull-right"><em>(select existing)</em></a>
              <div class="form-group">
                <label>Name:</label>
                <input class="form-control" type="text" name="company_input">
              </div>
              <div class="form-group">
                <label>Address:</label>
                <input class="form-control" type="text" name="address_input">
              </div>
            </span>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Save</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        </div>
      </form>
    </div>

  </div>
</div>
@stop

@push('scripts')
<script>
  $(function () {
    // ---------------------
    // ------ COMPANY ------
    // ---------------------

    @if (\Session::has('company_select'))
      @if (\Session::get('company_select') == 1)
        company_select();
      @else
        company_new();
      @endif
    @endif

    $('#add-company-btn').click(function () {
      company_new();
    });

    $('#select-company-btn').click(function () {
      company_select();
    });

    function company_new () {
      $('#company-select').hide();
      $('#company-select select').attr('name', ''); // remove name

      $('#company-input').show();
      $('#company-input').find('input:first').focus();
    }

    function company_select () {
      $('#company-input').hide();

      $('#company-select').show();
      $('#company-select select').attr('name', 'company'); // add name
    }
  });
</script>
<script>
  $(function () {
    var user_tbl = $('#user-table');
    var table = $(user_tbl).DataTable({
      "ajax": {
          "url": '{{ route("user.index-ajax") }}',
          "type": "POST",
          "data": {
            "_token": $('meta[name="csrf-token"]').attr('content')
          }
      },
      "columns": [
          { "data": "id" },
          { "data": "name" },
          { "data": "username" },
          { "data": "company" },
          { "data": null,
            render: function ( data, type, row ) {
              var operators = $(user_tbl).data('operators');
              var buttons = ''; // operator buttons holder
              for(var i = 0; i < operators.length; i++) {
                var operator = operators[i];
                var url = operator.url;
                    url = operator.url.replace(':id', data.id);
                buttons += '<div class="btn-group">'+
                              '<a href="'+url+'" class="btn btn-default btn-xs" title="'+operator.text+'"><i class="'+operator.icon+'"></i></a>';
              }
              return buttons;
            }
          },
      ],
      'paging'      : true,
      'lengthChange': true,
      'searching'   : true,
      'ordering'    : true,
      'info'        : true,
      'select'      : true,
      'responsive'  : true,
      'scrollY'     : "300px",
      dom: "<'row'<'col-sm-3'l><'col-sm-6 text-center'B><'col-sm-3'f>>" +
           "<'row'<'col-sm-12'tr>>" +
           "<'row'<'col-sm-5'i><'col-sm-7'p>>",
      columnDefs: [
        { targets: [0], visible: true },
      ],
      lengthMenu: [
          [ 10, 25, 50, -1 ],
          [ '10', '25', '50', '100', 'All' ]
      ],
      buttons: [
          {
            text: 'Add',
            action: function (e, node, config){
              $('#user-modal').modal('show');
            }
          }, {
            extend: 'excelHtml5',
            exportOptions: {
              columns: ':visible'
            }
          },
          'colvis'
      ]
    });

    // Call Custom DataTables AJAX CRUD
    $('#user-table').AjaxCrudDataTables({
      table: table,
      modal: $('#user-modal'),
    });
  });
</script>
@endpush
