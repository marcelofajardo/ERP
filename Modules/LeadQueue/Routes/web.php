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

Route::prefix('leadqueue')->middleware('auth')->group(function() {
    Route::get('/', 'LeadQueueController@index');
});


Route::prefix('lead-queue')->middleware('auth')->group(function() {
    Route::get('/', 'LeadQueueController@index')->name("lead-queue.index");
	Route::get('/approve', 'LeadQueueController@approve')->name("lead-queue.approve");
	Route::get('/approve/approved', 'LeadQueueController@approved')->name("lead-queue.approved");
    Route::get('/status', 'LeadQueueController@status')->name("lead-queue.status");
	Route::get('delete', 'LeadQueueController@deleteRecord')->name("lead-queue.delete.record");
    Route::prefix('records')->group(function() {
		Route::get('/', 'LeadQueueController@records');
		Route::post('/action-handler','LeadQueueController@actionHandler');
		// Route::prefix('{id}')->group(function() {
		// 	Route::get('delete', 'LeadQueueController@deleteRecord');
		// });
	});

	Route::prefix('report')->group(function() {
		Route::get('/', 'LeadQueueController@report')->name("lead-queue.report");
	});

	Route::prefix('setting')->group(function() {
		Route::post('update-limit','LeadQueueController@updateLimit');
		Route::get('recall','LeadQueueController@recall');
	});
});