<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

// Route::resource('permissions', 'PermissionController');
// Route::resource('roles', 'RoleController');

Route::group(['prefix' => 'users/authorizations'], function () {
  Route::get('/index', [
      'uses' => 'AuthorizationController@index',
      'as' => 'authorizations.index',
    ]);
  Route::get('{id}/edit/', [
      'uses' => 'AuthorizationController@edit',
      'as' => 'authorization.edit',
    ]);
  Route::post('{id}/update/', [
      'uses' => 'AuthorizationController@update',
      'as' => 'authorization.update',
    ]);
});

Route::group(['prefix' => 'permissions'], function () {
  Route::get('/index', [
      'uses' => 'PermissionController@index',
      'as' => 'permissions.index',
    ]);
  Route::get('/create', [
      'uses' => 'PermissionController@create',
      'as' => 'permissions.create',
    ]);
  Route::post('/store', [
      'uses' => 'PermissionController@store',
      'as' => 'permissions.store',
    ]);
  Route::get('{id}/edit/', [
      'uses' => 'PermissionController@edit',
      'as' => 'permissions.edit',
    ]);
  Route::post('{id}/update/', [
      'uses' => 'PermissionController@update',
      'as' => 'permissions.update',
    ]);
  Route::get('{id}/trash/', [
      'uses' => 'PermissionController@trash',
      'as' => 'permissions.trash',
    ]);
  Route::post('{id}/destroy/', [
      'uses' => 'PermissionController@destroy',
      'as' => 'permissions.destroy',
    ]);


  // AJAX
  Route::post('index-ajax/', [
      'uses' => 'PermissionController@index_ajax',
      'as' => 'permissions.index-ajax',
    ]);
  Route::post('store-ajax/', [
      'uses' => 'PermissionController@store_ajax',
      'as' => 'permissions.store-ajax',
    ]);
});

Route::group(['prefix' => 'roles'], function () {
  Route::get('/index', [
      'uses' => 'RoleController@index',
      'as' => 'roles.index',
    ]);
  Route::get('/create', [
      'uses' => 'RoleController@create',
      'as' => 'roles.create',
    ]);
  Route::post('/store', [
      'uses' => 'RoleController@store',
      'as' => 'roles.store',
    ]);
  Route::get('{id}/edit/', [
      'uses' => 'RoleController@edit',
      'as' => 'roles.edit',
    ]);
  Route::post('{id}/update/', [
      'uses' => 'RoleController@update',
      'as' => 'roles.update',
    ]);
  Route::get('{id}/trash/', [
      'uses' => 'RoleController@trash',
      'as' => 'roles.trash',
    ]);
  Route::post('{id}/destroy/', [
      'uses' => 'RoleController@destroy',
      'as' => 'roles.destroy',
    ]);
});

Route::group(['prefix' => '/', 'middleware' => ['auth']], function () {
	Route::get('/', [
			'uses' => 'HomeController@index',
			'as' => 'home',
		]);
});

// -----
// USERS
// -----
Route::group(['prefix' => 'users'], function () {
	Route::get('/index', [
			'uses' => 'UserController@index',
			'as' => 'users.index',
		]);
	Route::get('/create', [
			'uses' => 'UserController@create',
			'as' => 'user.create',
		]);
	Route::post('/store', [
			'uses' => 'UserController@store',
			'as' => 'user.store',
		]);
	Route::get('{id}/edit', [
			'uses' => 'UserController@edit',
			'as' => 'user.edit',
		]);
	Route::post('{id}/update', [
			'uses' => 'UserController@update',
			'as' => 'user.update',
		]);
	Route::post('{id}/password_reset', [
			'uses' => 'UserController@password_reset',
			'as' => 'user.password_reset',
		]);
	Route::get('{id}/trash', [
			'uses' => 'UserController@trash',
			'as' => 'user.trash',
		]);
	Route::post('{id}/delete', [
			'uses' => 'UserController@delete',
			'as' => 'user.delete',
		]);
	Route::get('profile', [
			'uses' => 'UserController@profile',
			'as' => 'user.profile',
		])->middleware('auth');
  Route::put('profile-update', [
      'uses' => 'UserController@profile_update',
      'as' => 'user.profile-update',
    ])->middleware('auth');
	Route::put('changepass', [
			'uses' => 'UserController@changepass',
			'as' => 'user.changepass',
		])->middleware('auth');
  Route::post('import', [
			'uses' => 'UserController@import',
			'as' => 'user.import',
		]);

  // AJAX
  Route::post('index-ajax', [
      'uses' => 'UserController@index_ajax',
      'as' => 'user.index-ajax',
    ]);
  Route::post('store-ajax', [
      'uses' => 'UserController@store_ajax',
      'as' => 'user.store-ajax',
    ]);
});

Route::group(['prefix' => 'companies'], function () {
  Route::get('/index', [
      'uses' => 'CompanyController@index',
      'as' => 'companies.index',
    ]);
  Route::get('{id}/edit', [
      'uses' => 'CompanyController@edit',
      'as' => 'company.edit',
    ]);
  Route::put('{id}/update', [
      'uses' => 'CompanyController@update',
      'as' => 'company.update',
    ]);
  Route::get('{id}/trash', [
      'uses' => 'CompanyController@trash',
      'as' => 'company.trash',
    ]);
  Route::delete('{id}/delete', [
      'uses' => 'CompanyController@delete',
      'as' => 'company.delete',
    ]);
  Route::post('/store-ajax', [
      'uses' => 'CompanyController@store_ajax',
      'as' => 'company.store-ajax',
    ]);
});

Route::group(['prefix' => 'files'], function () {
  Route::get('/index', [
      'uses' => 'FileController@index',
      'as' => 'files.index',
    ]);
  Route::get('/view', [
      'uses' => 'FileController@view',
      'as' => 'files.view',
    ]);
  Route::post('/store', [
      'uses' => 'FileController@store',
      'as' => 'file.store',
    ]);
  Route::get('{id}/edit', [
      'uses' => 'FileController@edit',
      'as' => 'file.edit',
    ]);
  Route::put('{id}/update', [
      'uses' => 'FileController@update',
      'as' => 'file.update',
    ]);
  Route::get('{id}/trash', [
      'uses' => 'FileController@trash',
      'as' => 'file.trash',
    ]);
  Route::delete('{id}/delete', [
      'uses' => 'FileController@delete',
      'as' => 'file.delete',
    ]);
  Route::post('{id}/download', [
      'uses' => 'FileController@download',
      'as' => 'file.download',
    ]);

  // AJAX
  Route::post('/store-ajax', [
      'uses' => 'FileController@store_ajax',
      'as' => 'file.store-ajax',
    ]);
  Route::get('{id}/edit-ajax', [
      'uses' => 'FileController@edit_ajax',
      'as' => 'file.edit-ajax',
    ]);
});

Route::group(['prefix' => 'file-settings'], function () {
  Route::get('/', [
      'uses' => 'FileSettingController@settings',
      'as' => 'file-settings.index',
    ]);
  Route::post('/update-ajax', [
      'uses' => 'FileSettingController@update_ajax',
      'as' => 'file-setting.update-ajax',
    ]);
  Route::get('{email}/{email_notif}/new-email-ajax', [
      'uses' => 'FileSettingController@new_email_ajax',
      'as' => 'file-setting.new-email-ajax',
    ]);
});

// ------
// THEMES
// ------
Route::group(['prefix' => 'themes'], function () {
	Route::post('/{id}/update', [
			'uses' => 'ThemeController@update',
			'as' => 'themes.update',
		]);
});