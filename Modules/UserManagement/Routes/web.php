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

Route::prefix('user-management')->middleware('auth')->group(function () {

    Route::get('/', 'UserManagementController@index')->name("user-management.index");

    Route::get('/feedback-category/store', 'UserManagementController@addFeedbackCategory')->name("user.feedback-category");
    Route::get('/feedback-status/store', 'UserManagementController@addFeedbackStatus')->name("user.feedback-status");
    Route::get('/feedback-table/data', 'UserManagementController@addFeedbackTableData')->name("user.feedback-table-data");

    // Route::get('/userfeedback','UserManagementController@cat_name');
    // Route::post('/userfeedback','UserManagementController@cat_name')->name('user-management.insert');

    Route::post('/request-list', 'UserManagementController@permissionRequest')->name("user-management.permission.request");

    Route::post('/request-delete', 'UserManagementController@deletePermissionRequest')->name("user-management.permission.delete.request.");

    Route::post('/task-activity', 'UserManagementController@taskActivity')->name("user-management.task.activity");
    Route::post('today-task-history', 'UserManagementController@todayTaskHistory')->name("user-management.today.task.history");
    Route::post('modifiy-permission', 'UserManagementController@modifiyPermission')->name("user-management.modifiy.permission");
    Route::get('/edit/{id}', 'UserManagementController@edit')->name("user-management.edit");
    Route::get('/role/{id}', 'UserManagementController@getRoles')->name("user-management.get-role");
    Route::post('/role/{id}', 'UserManagementController@submitRoles')->name("user-management.submit-role");
    Route::get('/permission/{id}', 'UserManagementController@getPermission')->name("user-management.get-permission");
    Route::post('/permission/{id}', 'UserManagementController@submitPermission')->name("user-management.submit-permission");
    Route::post('/add-permission', 'UserManagementController@addNewPermission')->name("user-management.add-permission");
    Route::get('/show/{id}', 'UserManagementController@show')->name("user-management.show");
    Route::patch('/update/{id}', 'UserManagementController@update')->name("user-management.update");
    Route::post('/{id}/activate', 'UserManagementController@activate')->name("user-management.activate");
    Route::get('track/{id}', 'UserManagementController@usertrack')->name("user-management.usertrack");
    Route::get('/user/team/{id}', 'UserManagementController@createTeam')->name("user-management.team");
    Route::post('/user/team/{id}', 'UserManagementController@submitTeam')->name("user-management.team.submit");
    Route::get('/user/teams/{id}', 'UserManagementController@getTeam')->name("user-management.team.info");
    Route::post('/user/teams/{id}', 'UserManagementController@editTeam')->name("user-management.team.edit");
    Route::post('/user/delete-team/{id}', 'UserManagementController@deleteTeam')->name("user-management.team.delete");
    Route::get('/paymentInfo/{id}', 'UserManagementController@paymentInfo')->name("user-management.payment-info");
    Route::get('payments/{id}', 'UserManagementController@userPayments')->name("user-management.payments");
    Route::post('payments/{id}', 'UserManagementController@savePayments')->name("user-management.savePayments");

    Route::get('user-avaibility/{id}', 'UserManagementController@getPendingandAvalHour')->name("user-management.task-hours");

    Route::post('user-avaibility/submit-time', 'UserManagementController@saveUserAvaibility')->name("user-management.user-avaibility.submit-time");

    Route::get('user-avl-list/{id}', 'UserManagementController@userAvaibilityForModal')->name("user-management.user-avl-list");

    Route::post('user-avaibility/{id}', 'UserManagementController@userAvaibilityUpdate')->name("user-management.update-user-avaibility");
    Route::post('approve-user/{id}', 'UserManagementController@approveUser')->name("user-management.approve-user");
    Route::post('/add-new-method', 'UserManagementController@addPaymentMethod')->name("user-management.add-payment-method");
    Route::get('/task/user/{id}', 'UserManagementController@userTasks')->name("user-management.tasks");
    Route::post('/reply/add', 'UserManagementController@addReply')->name('user-management.reply.add');
    Route::get('/reply/delete', 'UserManagementController@deleteReply')->name('user-management.reply.delete');
    Route::get('/records', 'UserManagementController@records')->name("user-management.records");
    Route::get('/user-details/{id}', 'UserManagementController@GetUserDetails')->name("user-management.user-details");
    Route::get('task-hours/{id}', 'UserManagementController@getPendingandAvalHour')->name("user-management.task-hours");
    Route::get('/system-ips', 'UserManagementController@systemIps');
    Route::get('{id}/get-database', 'UserManagementController@getDatabase')->name("user-management.get-database");
    Route::post('{id}/create-database', 'UserManagementController@createDatabaseUser')->name("user-management.create-database");
    Route::post('{id}/assign-database-table', 'UserManagementController@assignDatabaseTable')->name("user-management.assign-database-table");
    Route::post('{id}/delete-database-access', 'UserManagementController@deleteDatabaseAccess')->name("user-management.delete-database-access");
    Route::post('{id}/choose-database', 'UserManagementController@chooseDatabase')->name("user-management.choose-database");
    Route::post('/update-status', 'UserManagementController@updateStatus');

    Route::post('/user-generate-file-store', 'UserManagementController@userGenerateStorefile')->name("user-management.gent-file-store");

    Route::get('/user-generate-file-listing/{userid}', 'UserManagementController@userPemfileHistoryListing')->name("user-management-pem-history-list");
    Route::post('/delete-pem-file/{id}', 'UserManagementController@deletePemFile')->name("user-management-delete-pem-file");
});
