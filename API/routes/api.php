<?php

use Illuminate\Support\Facades\Route;

/*
 * Public Routes
 */

Route::post('login', 'SessionController@login');
Route::post('logout', 'SessionController@logout');
Route::get('session', 'SessionController@getSession');

/*
 * Member/Admin Routes
 */

Route::prefix('app')->middleware('roleGuard:member|admin')->group(function() {
    Route::resource('tracked-urls', 'App\TrackedUrlController', ['only' => ['index', 'store', 'destroy']]);
    Route::get('tracked-url-images/find-for-tracked-url/{id}', 'App\TrackedUrlImageController@findForUrl');
    Route::get('tracked-url-images/find-for-trashed-tracked-url/{id}', 'App\TrackedUrlImageController@findForTrashedUrl');
    Route::resource('trashed-tracked-urls', 'App\TrashedTrackedUrlController', ['only' => ['index', 'destroy']]);
    Route::post('trashed-tracked-urls/restore', 'App\TrashedTrackedUrlController@restore');
});

/*
 * Admin Routes
 */

Route::prefix('admin')->middleware('roleGuard:admin')->group(function() {
    Route::get('log', 'Admin\LogController@index');
    Route::post('test', 'Admin\LogController@test');
    Route::resource('users', 'Admin\UserController', ['only' => ['index', 'store', 'destroy']]);
});