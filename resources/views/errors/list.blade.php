@if (\Session::has('create_fail'))
  <div class="alert alert-dismissible alert-{{ \Session::get('create_fail.status') }}">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong>{{ \Session::get('create_fail.title') }}</strong> {{ \Session::get('create_fail.message') }}
  </div>
@elseif (\Session::has('update_fail'))
  <div class="alert alert-dismissible alert-{{ \Session::get('update_fail.status') }}">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong>{{ \Session::get('update_fail.title') }}</strong> {{ \Session::get('update_fail.message') }}
  </div>
@elseif (\Session::has('delete_fail'))
  <div class="alert alert-dismissible alert-{{ \Session::get('delete_fail.status') }}">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <strong>{{ \Session::get('delete_fail.title') }}</strong> {{ \Session::get('delete_fail.message') }}
  </div>
@endif
