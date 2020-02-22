<aside class="main-sidebar">
  <section class="sidebar">
    <div class="user-panel">
      <div class="pull-left image">
        <img src="{{ asset('images/laravel-logo.png') }}" class="img-circle" alt="User Image">
      </div>
      <div class="pull-left info">
        <p>{{ \Auth::user()->first_name }} {{ \Auth::user()->last_name }}</p>
        <a href="#"><i class="fa fa-circle text-success"></i>
          {{ \Auth::user()->company > 0 ? \Auth::user()->company->name : 'Not Assigned' }}
        </a>
      </div>
    </div>

    <form action="#" method="get" class="sidebar-form">
      <div class="input-group">
        <input type="text" name="q" class="form-control" placeholder="Search...">
        <span class="input-group-btn">
          <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
          </button>
        </span>
      </div>
    </form>

    <ul class="sidebar-menu" data-widget="tree">
      <!-- Start of Dashboard -->
      <li class="{{ \Request::route()->getName() === 'home' ? 'active' : '' }}">
        <a href="{{ route('home') }}">
          <i class="fa fa-dashboard"></i> <span>Dashboard</span>
        </a>
      </li>
      <!-- End of Dashboard -->

<!-- --------------------------------------------------------------------- -->
      
      <!-- ----------------------------- -->
      <!-- Start of Administrative Links -->
      <!-- ----------------------------- -->
      @if (\Auth::user()->hasAnyPermission([
        'Administer roles & permissions',

        'Show Users',
        'Create Users',
        'Edit Users',
        'Delete Users',

        'Show User Authorizations',
        'Edit User Authorizations',

      ]))
        <li class="header">ADMINISTRATIVE</li>
        <!-- Start of User Links -->
        @if (\Auth::user()->hasAnyPermission([
          'Show Users',
          'Create Users',
          'Edit Users',
          'Delete Users',

          'Show User Employments',
          'Edit User Employments',

          'Show User Authorizations',
          'Edit User Authorizations',
        ]))
          <li class="treeview {{ isset($is_user_route) ? 'active' : (isset($is_auth_route) ? 'active' : (isset($is_employment_route) ? 'active' : '')) }}">
            <a href="#"><i class="fa fa-gear"></i> <span>User Management</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              @if (\Auth::user()->hasAnyPermission([
                'Show User Authorizations',
                'Edit User Authorizations',
              ]))
                <li class="{{ isset($is_auth_route) ? 'active' : '' }}">
                  <a href="{{ route('authorizations.index') }}"><i class="fa fa-circle-o"></i>&nbsp;Authorization</a>
                </li>
              @endif
              @if (\Auth::user()->hasAnyPermission([
                'Show Users',
                'Create Users',
                'Edit Users',
                'Delete Users',
              ]))
                <li class="{{ isset($is_user_route) ? 'active' : '' }}">
                  <a href="{{ route('users.index') }}"><i class="fa fa-circle-o"></i>&nbsp;User Accounts</a>
                </li>
              @endif
            </ul>
          </li>
        @endif
        <!-- End of User Links -->

        <!-- Start of Authorization Links -->
        @if (\Auth::user()->hasAnyPermission(['Administer roles & permissions']))
          <li class="treeview {{ isset($is_role_route) ? 'active' : (isset($is_permission_route) ? 'active' : '') }}">
            <a href="#"><i class="fa fa-shield"></i> <span>Authorizations</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li class="{{ isset($is_role_route) ? 'active' : '' }}">
                <a href="{{ route('roles.index') }}"><i class="fa fa-circle-o"></i>&nbsp;Roles</a>
              </li>
              <li class="{{ isset($is_permission_route) ? 'active' : '' }}">
                <a href="{{ route('permissions.index') }}"><i class="fa fa-circle-o"></i>&nbsp;Permissions</a>
              </li>
            </ul>
          </li>
        @endif
        <!-- End of Authorization Links -->

        <!-- Start of Company Links -->
        @if (\Auth::user()->hasAnyPermission([
          'Show Companies',
          'Create Companies',
          'Edit Companies',
          'Delete Companies'
        ]))
          <li class="{{ isset($is_company_route) ? 'active' : '' }}">
            <a href="{{ route('companies.index') }}">
              <i class="fa fa-building"></i> <span>Company</span>
            </a>
          </li>
        @endif
        <!-- End of Company Links -->
      @endif
      <!-- --------------------------- -->
      <!-- End of Administrative Links -->
      <!-- --------------------------- -->

      <!-- ------------------- -->
      <!-- Start of File Links -->
      <!-- ------------------- -->
      @if (\Auth::user()->hasAnyPermission([
        'Show Files',
        'Create Files',
        'Edit Files',
        'Delete Files',
        'View Files',
      ]))
        <li class="header">FILE</li>
        <!-- Start of File Links -->
        @if (\Auth::user()->hasAnyPermission([
          'Show Files',
          'Create Files',
          'Edit Files',
          'Delete Files',
          'View Files',
        ]))
          <li class="{{ isset($is_file_route) ? 'active' : '' }}">
            <a href="{{ \Auth::user()->hasPermissionTo('View Files') ? route('files.view') : route('files.index') }}">
              <i class="fa fa-file-text-o"></i> <span>File</span>
            </a>
          </li>
        @endif
        <!-- End of File Links -->
      @endif
      <!-- ----------------- -->
      <!-- End of File Links -->
      <!-- ----------------- -->
    </ul>
  </section>
</aside>