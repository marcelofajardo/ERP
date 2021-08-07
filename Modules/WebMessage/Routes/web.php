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

Route::prefix('web-message')->middleware('auth')->group(function() {
    Route::get('/', 'WebMessageController@index');
    Route::post('/send', 'WebMessageController@send');
    Route::get('/message-list/{id}', 'WebMessageController@messageList');
    Route::get('/status','WebMessageController@status');
    Route::post('/action','WebMessageController@action');
    Route::post('/user-action','WebMessageController@userAction'); 
});
