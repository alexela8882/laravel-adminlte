@extends('layouts.app')

@section('title', 'Files')

@section('content')
<div class="content-wrapper">
  <section class="content-header">
    <h1>
      Files
      <small>Manage files</small>
    </h1>
    <ol class="breadcrumb">
      <li><a href="{{ route('home') }}"><i class="fa fa-dashboard"></i> Home</a></li>
      <li class="active">Files</li>
    </ol>
  </section>

  <section class="content">
    <div class="row mt-4 mb-4">
      <div class="col-md-12">
        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title">
              Files
            </h3>
          </div>
          <div class="box-body table-responsive">
            @include ('errors.list')
            @include ('successes.list')
            <table class="table table-bordered table-hover"
                   id="file-table"
                   data-operators='[{"url":"{{route('file.edit',['id'=>':id'])}}","text":"Edit","icon":"fa fa-pencil"},{"url":"{{route('file.trash',['id'=>':id'])}}","text":"Delete","icon":"fa fa-trash"}]'>
              <thead>
                <tr>
                  <th>ID</th>
                  <th>File Name</th>
                  <th>File</th>
                  <th>From</th>
                  <th>To (User)</th>
                  <th>To (Company)</th>
                  <th>Remarks</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($files as $index => $file)
                  <tr>
                    <td>{{ $file->id }}</td>
                    <td>{{ $file->file }}</td>
                    <td>
                      <!-- Files -->
                    </td>
                    <td>{{ $file->from_user->name }}</td>
                    <td>{{ $file->to_user ? $file->to_user->name : '' }}</td>
                    <td>{{ $file->company_id ? $file->to_company->name : '' }}</td>
                    <td>{{ $file->remarks }}</td>
                    <td>
                      <!-- Actions -->
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

<!-- Modal -->
<div id="file-modal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">ADD FILE</h4>
      </div>

      <form method="post"
            action="{{ route("file.store-ajax") }}"
            enctype="multipart/form-data">
        <div class="modal-body">
          <div class="form-group">
            <label>File:</label>
            <input type="file" class="form-control" name="file">
          </div>

          <div class="form-group">
            <label>Remarks:</label>
            <textarea class="form-control" name="remarks"></textarea>
          </div>

          <div class="form-group">
            <label>
              <span>To Company</span>
              <a href="javascript:void(0);" id="to-user">or To User:</a>
            </label>
            <select class="form-control" name="company">
              @foreach ($companies as $company)
                <option value="{{ $company->id }}">{{ $company->name }}</option>
              @endforeach
            </select>

            <select class="form-control" name="" style="display:none;">
              @foreach ($users as $user)
                <option value="{{ $user->id }}">{{ $user->first_name }} {{ $user->last_name }}</option>
              @endforeach
            </select>
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
    $('label').on('click', '#to-user', function() {
      var div = $($(this).parent()).parent(),
          span = $(this).parent().find('span');

      $(span).html('To User');
      $(this).attr('id', 'to-company').html('or To Company:');
      
      $(div).find('select').attr('name', '').hide();
      $(div).find('select').next().attr('name', 'user').show();
    });

    $('label').on('click', '#to-company', function() {
      var div = $($(this).parent()).parent(),
          span = $(this).parent().find('span');

      $(span).html('To Company');
      $(this).attr('id', 'to-user').html('or To User:');
      
      $(div).find('select').attr('name', 'company').show();
      $(div).find('select').next().attr('name', '').hide();
    });

    // --------------------------------------------------------------------

    var fileTable = $('#file-table');
    var table = $(fileTable).DataTable({
          'paging'      : true,
          'lengthChange': true,
          'searching'   : true,
          'ordering'    : true,
          'info'        : true,
          'select': {
            style:    'single',
            selector: 'tr>td:not(:nth-child(2))'
          },
          'responsive'  : true,
          'scrollY'     : "300px",
          'columns': [
              { "data": "id" },
              { "data": "file", "visible": false },
              { "data": null,
                render: function ( data, type, row ) {
                  var url = '{{ route("file.download", ["id" => ":id"]) }}',
                      _token = '{{ csrf_field() }}',
                      url = url.replace(':id', data.id),
                      file = '<form method="post" action="'+url+'">'+
                             _token+
                             '<button style="text-overflow:ellipsis;" stype="submit" class="btn-to-link text-to-white">'+data.file+'</button>'+
                             '</form>';
                  return file;
                }
              },
              { "data": "from" },
              { "data": "to" },
              { "data": "company" },
              { "data": "remarks" },
              { "data": null,
                render: function ( data, type, row ) {
                  var operators = $(fileTable).data('operators');
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
                $('#file-modal').modal('show');
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

    // Custom DataTables AJAX CRUD
    $('#file-table').AjaxCrudDataTables({
      table : table,
      modal : $('#file-modal'),
    });
  });
</script>
@endpush
