<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class UserClearance
{
    
    public function handle($request, Closure $next) {
        // Show :: Show Users permission doesn't affect showing lists
		if ($request->is('users/index')) {
		  if (Auth::user()->hasPermissionTo('Show Users')) {
		      return $next($request);
		  } else {
		      if (Auth::user()->hasAnyPermission('Create Users',
		                                         'Edit Users',
		                                         'Delete Users')) {
		          return $next($request);
		      } else {
		          abort('403');
		      }
		  }
		}

		// Create
		if ($request->is('users/create') ||
			$request->is('users/store') ||
			$request->is('users/store-ajax')) {
		  if (!Auth::user()->hasPermissionTo('Create Users')) {
		      abort('403');
		  } else {
		      return $next($request);
		  }
		}

		// Edit
		if ($request->is('users/*/edit') || $request->is('users/*/update')) {
		  if (!Auth::user()->hasPermissionTo('Edit Users')) {
		      abort('403');
		  } else {
		      return $next($request);
		  }
		}

		// Delete
		if ($request->is('users/*/trash') || $request->is('users/*/delete')) {
		  if (!Auth::user()->hasPermissionTo('Delete Users')) {
		      abort('403');
		  } else {
		      return $next($request);
		  }
		}

        return $next($request);
    }
}
