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
            <div class="box-toolbar pull-right">
              <div class="form-check form-check-inline">
                <span class="fa fa-question-circle"
                      data-toggle="tooltip"
                      data-container="body"
                      title="Email Notification"></span>
                <input class="form-check-input email-notif"
                       type="checkbox"
                       {{ $file_settings && $file_settings->email_notif == 1 ? 'checked' : '' }}>
                <a class="hide btn btn-default btn-xs" href="javascript:void(0);">
                  <i class="fa fa-cog"></i>&nbsp;Settings
                </a>
              </div>
            </div>
          </div>
          <div class="box-body table-responsive">
            @include ('errors.list')
            @include ('successes.list')
            <table class="table table-bordered table-hover" id="file-table">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>File Name</th>
                  <th>File</th>
                  <th>From</th>
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
@stop

@push('scripts')
<script>
  $(function () {

    $.switcher(); // switcher plugin
    $('[data-toggle="tooltip"]').tooltip({
      trigger: 'hover',
      html: true,
    });

    $('.email-notif').on('change', function () {
      var data = {
                  "_token": '{{ csrf_token() }}',
                  "email_notif": $(this).prev().attr('aria-checked')
                 };
      var url = '{{ route("file-setting.update-ajax") }}';
      console.log(data);
      $.ajax({
        url: url,
        type: 'post',
        data: data,
        success: function (response) {
          const Toast = Swal.mixin({
            toast: true,
            position: 'top',
            showConfirmButton: false,
            timer: 5000
          });

          if (response.email_notif == 1) {
            var notif = 'activated';
            Toast.fire({
              type: 'success',
              title: 'Well Done!',
              text: 'Email notification has been successfully '+notif+'.',
            })
          } else {
            var notif = 'deactivated';
            Toast.fire({
              type: 'info',
              title: 'Reminders!',
              text: 'Email notification has been '+notif+'.',
            })
          }
        },
        error: function (err) {
          var err = err.responseJSON;
          Swal.fire({
            type: 'warning',
            title: 'You have no email saved',
            input: 'email',
            inputPlaceholder: 'Please enter your email address',
            inputAttributes: {
              autocapitalize: 'off'
            },
            showCancelButton: true,
            confirmButtonText: 'Okay',
            showLoaderOnConfirm: true,
            preConfirm: (email) => {
              var url = '{{ route("file-setting.new-email-ajax", ["email" => ":email", "notif" => ":email_notif"]) }}';
                  url = url.replace(':email', email);
                  url = url.replace(':email_notif', data.email_notif);
              return fetch(url)
                .then(response => {
                  if (!response.ok) {
                    throw new Error(response.statusText)
                  }
                  return response.json()
                })
                .catch(error => {
                  Swal.showValidationMessage(
                    `Request failed: ${error}`
                  )
                })
            },
            allowOutsideClick: () => !Swal.isLoading()
          }).then((result) => {
            // reset default value when cancelled
            if (result.dismiss == 'backdrop' || result.dismiss == 'cancel') {
              if ($('.email-notif').is(':checked')) {
                $('.email-notif').prop('checked', false);
                $('.email-notif').prev().attr('aria-checked', 'false');
              } else {
                $('.email-notif').prop('checked', true);
                $('.email-notif').prev().attr('aria-checked', 'true');
              }
            }

            // change value upon proceeding
            if (result.value) {
              var profile_url = '{{ route("user.profile") }}';
              var email_notif = result.value.email_notif;

              if (email_notif == 1) {
                var notif = 'activated';
              } else {
                var notif = 'deactivated';
              }

              Swal.fire({
                type: 'success',
                title: 'Well Done!',
                text: 'Email notification has been successfully '+notif,
                footer: '<a href="'+profile_url+'">Go to your profile to add another email address</a>',
              })
            }
          })
        }
      });
    });

    // ------------------------------------------------------------------

    $('#file-table').DataTable({
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
          { "data": "remarks" },
          { "data": null, "visible": false }
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
          extend: 'excelHtml5',
          exportOptions: {
            columns: ':visible'
          }
        },
        'colvis'
      ]
    });
  });
</script>
@endpush