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

Route::prefix('message-queue')->middleware('auth')->group(function() {
    
    Route::get('/', 'MessageQueueController@index')->name("message-queue.index");
    Route::get('/approve', 'MessageQueueController@approve')->name("message-queue.approve");
    Route::get('/status', 'MessageQueueController@status')->name("message-queue.status");
    Route::get('/update-do-not-disturb', 'MessageQueueController@updateDoNotDisturb')->name("message-queue.updateDoNotDisturb");
    
    Route::prefix('records')->group(function() {
		Route::get('/', 'MessageQueueController@records');
		Route::post('action-handler','MessageQueueController@actionHandler');
		Route::prefix('{id}')->group(function() {
			Route::get('delete', 'MessageQueueController@deleteRecord');
		});
	});

	Route::prefix('report')->group(function() {
		Route::get('/', 'MessageQueueController@report')->name("message-queue.report");
	});

	Route::prefix('setting')->group(function() {
		Route::post('update-limit','MessageQueueController@updateLimit');
		Route::post('update-time','MessageQueueController@updateTime');
		Route::get('recall','MessageQueueController@recall');
	});
});
