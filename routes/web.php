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

// ----------------------------------------------------------
// Default Main Entry
// ----------------------------------------------------------
Route::get('/', function () {
    return view('welcome');
});



Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');


// ----------------------------------------------------------
// Admin Routes
// ----------------------------------------------------------
Route::group(['prefix' => 'admin','namespace' => 'Admin'], function () {

    // Admin main entry
    Route::get('/', 'HomeController@index')->name('admin.home');

    Route::resource('posts', 'PostController',[
        'as' => 'admin'
    ]);
    // Posts CRUD
    //Route::get('/posts/create', 'PostController@create')->name('admin_post_create');
    //Route::post('/posts', 'PostController@store')->name('admin_post_store');
    //Route::get('/posts/{id}/edit', 'PostController@edit')->name('admin_post_edit');
    //Route::put('/posts/{id}', 'PostController@update')->name('admin_post_update');
    //Route::delete('/posts/{id}', 'PostController@destroy')->name('admin_post_destroy');
});
