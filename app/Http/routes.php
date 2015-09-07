<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', array('as' => 'home', 'uses' => 'UserController@index'));
Route::get('login', array('as' => 'login', 'uses' => 'Auth\AuthController@getLogin'));
Route::post('login', array('as' => 'login', 'uses' => 'Auth\AuthController@postLogin'));

// Check login before enter address
Route::group(['middleware' => 'auth'], function() {
	Route::group(['middleware' => 'manager'], function () {
		Route::get('search',    array('as' => 'search', 'uses' => 'UserController@search'));
		Route::get('add',       array('as' => 'add', 'uses' => 'UserController@create'));
	    Route::post('add/conf', array('as' => 'add_conf', 'uses' => 'UserController@add_conf'));
	    Route::post('add/comp', array('as' => 'add_comp', 'uses' => 'UserController@store'));

	    Route::group(['middleware' => 'direct_access'], function() {
	    	Route::group(['middleware' => 'is_disabled'], function() {
	    		Route::get('member/{id}/delete/conf', array('as' => 'delete_conf', 'uses' => 'UserController@delete_conf'));
	            Route::group(['middleware' => 'check_delete'], function () {
	                Route::post('member/{id}/delete/comp', array('as' => 'delete_comp', 'uses' => 'UserController@destroy'));
	            });
	    	});
	    });
	});

    Route::group(['middleware' => 'is_disabled'], function() {
    	Route::get('member/{id}/detail',       array('as' => 'member_detail', 'uses' => 'UserController@show'));
    	Route::group(['middleware' => 'check_edit'], function () {
            Route::get('member/{id}/edit',         array('as' => 'edit', 'uses' => 'UserController@edit'));
            Route::post('member/{id}/edit/conf',   array('as' => 'edit_conf', 'uses' => 'UserController@edit_conf'));
            Route::post('member/{id}/edit/comp',   array('as' => 'edit_comp', 'uses' => 'UserController@update'));
        });
    });

    Route::get('logout', array('as' => 'logout', 'uses' => 'Auth\AuthController@getLogout'));
});



Route::controllers([
    'auth' => 'Auth\AuthController',
    'password' => 'Auth\PasswordController'
]);