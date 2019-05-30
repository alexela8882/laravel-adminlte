<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class FileClearance
{

    public function handle($request, Closure $next) {
        
        // Show :: Show Files permission doesn't affect showing lists
        if ($request->is('files/index')) {
          if (Auth::user()->hasPermissionTo('Show Files')) {
              return $next($request);
          } else {
              if (Auth::user()->hasAnyPermission('Create Files',
                                                 'Edit Files',
                                                 'Delete Files')) {
                  return $next($request);
              } else {
                  abort('403');
              }
          }
        }

        // Create
        if ($request->is('files/create') ||
            $request->is('files/store') ||
            $request->is('files/store-ajax')) {
          if (!Auth::user()->hasPermissionTo('Create Files')) {
              abort('403');
          } else {
              return $next($request);
          }
        }

        // Edit
        if ($request->is('files/*/edit') || $request->is('files/*/update')) {
          if (!Auth::user()->hasPermissionTo('Edit Files')) {
              abort('403');
          } else {
              return $next($request);
          }
        }

        // Delete
        if ($request->is('files/*/trash') || $request->is('files/*/delete')) {
          if (!Auth::user()->hasPermissionTo('Delete Files')) {
              abort('403');
          } else {
              return $next($request);
          }
        }

        return $next($request);
    }
}
