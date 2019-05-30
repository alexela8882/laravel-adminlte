@extends('layouts.app')

@section('title', 'Permissions')

@section('content')
<div class="content-wrapper">
  <section class="content-header">
    <h1>
      Permissions
      <small>Manage permission</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Permissions</li>
    </ol>
  </section>

  <section class="content">
    <div class="row mt-4 mb-4">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title">
              Permission Lists
            </h3>
          </div>
          <div class="box-body table-responsive">
            @include('errors.list')
            @include('successes.list')
            <table class="table table-bordered table-hover"
                   id="permission-table"
                   data-operators='[{"url":"{{route('permissions.edit',['id'=>':id'])}}","text":"Edit","icon":"fa fa-pencil"},{"url":"{{route('permissions.trash',['id'=>':id'])}}","text":"Delete","icon":"fa fa-trash"}]'>
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Permissions</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($permissions as $index => $permission)
                  <tr>
                    <td>{{ $permission->id }}</td>
                    <td>{{ $permission->name }}</td>
                    <td></td>
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

<!-- Modal -->
<div id="permission-modal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">ADD PERMISSION</h4>
      </div>

      <form method="post"
            action="{{ route("permissions.store-ajax") }}">
        <div class="modal-body">
          <div id="flash-message"></div>
          <div class="form-group">
            <label>Name:</label>
            <input type="text" class="form-control" name="name" autofocus>
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
    var permTbl = $('#permission-table'),
        table = $(permTbl).DataTable({
          'paging'      : true,
          'lengthChange': true,
          'searching'   : true,
          'ordering'    : true,
          'info'        : true,
          'select'      : true,
          'responsive'  : true,
          'scrollY'     : "300px",
          'columns'     : [
            { "data": "id" },
            { "data": "name" },
            { "data": null,
              render: function ( data, type, row ) {
                var operators = $(permTbl).data('operators');
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
          dom: "<'row'<'col-sm-3'l><'col-sm-6 text-center'B><'col-sm-3'f>>" +
               "<'row'<'col-sm-12'tr>>" +
               "<'row'<'col-sm-5'i><'col-sm-7'p>>",
          lengthMenu: [
              [ 10, 25, 50, -1 ],
              [ '10', '25', '50', '100', 'All' ]
          ],
          buttons: [
              {
                text: 'Add',
                action: function (e, node, config){
                  $('#permission-modal').modal('show');
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

    $('#permission-table').AjaxCrudDataTables({
      table: table,
      modal: $('#permission-modal'),
    });
  });
</script>
@endpush
