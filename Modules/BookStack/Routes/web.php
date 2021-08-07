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

//Route::get('/', 'BookStackController@index');

// Authenticated routes...
Route::group(['middleware' => 'auth'], function () {

    // Secure images routing
    Route::get('/uploads/images/{path}', 'Images\ImageController@showImage')->where('path', '.*$');

    Route::group(['prefix' => 'kb'], function () {

        // Shelves
        Route::get('/create-shelf', 'BookshelfController@create');
        Route::get('/', 'BookshelfController@index');
        Route::group(['prefix' => 'shelves'], function () {
            Route::get('/', 'BookshelfController@index');
            // Route::post('/', 'BookshelfController@store');
            Route::get('/{slug}/edit', 'BookshelfController@edit');
            Route::get('/{slug}/delete', 'BookshelfController@showDelete');
            Route::get('/{slug}', 'BookshelfController@show');
            Route::put('/{slug}', 'BookshelfController@update');
            Route::delete('/{slug}', 'BookshelfController@destroy');
            Route::get('/{slug}/permissions', 'BookshelfController@showPermissions');
            Route::put('/{slug}/permissions', 'BookshelfController@permissions');
            Route::post('/{slug}/copy-permissions', 'BookshelfController@copyPermissions');

            Route::get('/{shelfSlug}/create-book', 'BookController@create');
            Route::post('/{shelfSlug}/create-book', 'BookController@store');

            Route::get('/show/{sortByView}/{sortByDate}', 'BookshelfController@showShelf'); 
        });

        Route::get('/create-book', 'BookController@create');
        Route::group(['prefix' => 'books'], function () {
            // Books
            Route::get('/', 'BookController@index');
            Route::post('/', 'BookController@store');
            Route::get('/{slug}/edit', 'BookController@edit');
            Route::put('/{slug}', 'BookController@update');
            Route::delete('/{id}', 'BookController@destroy');
            Route::get('/{slug}/sort-item', 'BookController@getSortItem');
            Route::get('/{slug}', 'BookController@show');
            Route::get('/{bookSlug}/permissions', 'BookController@showPermissions');
            Route::put('/{bookSlug}/permissions', 'BookController@permissions');
            Route::get('/{slug}/delete', 'BookController@showDelete');
            Route::get('/{bookSlug}/sort', 'BookController@sort');
            Route::put('/{bookSlug}/sort', 'BookController@saveSort');
            Route::get('/{bookSlug}/export/html', 'BookController@exportHtml');
            Route::get('/{bookSlug}/export/pdf', 'BookController@exportPdf');
            Route::get('/{bookSlug}/export/plaintext', 'BookController@exportPlainText');

            Route::get('/show/{sortByView}/{sortByDate}', 'BookController@showBook'); 

            // Pages
            Route::get('/{bookSlug}/create-page', 'PageController@create');
            Route::post('/{bookSlug}/create-guest-page', 'PageController@createAsGuest');
            Route::get('/{bookSlug}/draft/{pageId}', 'PageController@editDraft');
            Route::post('/{bookSlug}/draft/{pageId}', 'PageController@store');
            Route::get('/{bookSlug}/page/{pageSlug}', 'PageController@show');
            Route::get('/{bookSlug}/page/{pageSlug}/export/pdf', 'PageController@exportPdf');
            Route::get('/{bookSlug}/page/{pageSlug}/export/html', 'PageController@exportHtml');
            Route::get('/{bookSlug}/page/{pageSlug}/export/plaintext', 'PageController@exportPlainText');
            Route::get('/{bookSlug}/page/{pageSlug}/edit', 'PageController@edit');
            Route::get('/{bookSlug}/page/{pageSlug}/move', 'PageController@showMove');
            Route::put('/{bookSlug}/page/{pageSlug}/move', 'PageController@move');
            Route::get('/{bookSlug}/page/{pageSlug}/copy', 'PageController@showCopy');
            Route::post('/{bookSlug}/page/{pageSlug}/copy', 'PageController@copy');
            Route::get('/{bookSlug}/page/{pageSlug}/delete', 'PageController@showDelete');
            Route::get('/{bookSlug}/draft/{pageId}/delete', 'PageController@showDeleteDraft');
            Route::get('/{bookSlug}/page/{pageSlug}/permissions', 'PageController@showPermissions');
            Route::put('/{bookSlug}/page/{pageSlug}/permissions', 'PageController@permissions');
            Route::put('/{bookSlug}/page/{pageSlug}', 'PageController@update');
            Route::delete('/{bookSlug}/page/{pageSlug}', 'PageController@destroy');
            Route::delete('/{bookSlug}/draft/{pageId}', 'PageController@destroyDraft');

            // Revisions
            Route::get('/{bookSlug}/page/{pageSlug}/revisions', 'PageController@showRevisions');
            Route::get('/{bookSlug}/page/{pageSlug}/revisions/{revId}', 'PageController@showRevision');
            Route::get('/{bookSlug}/page/{pageSlug}/revisions/{revId}/changes', 'PageController@showRevisionChanges');
            Route::put('/{bookSlug}/page/{pageSlug}/revisions/{revId}/restore', 'PageController@restoreRevision');
            Route::delete('/{bookSlug}/page/{pageSlug}/revisions/{revId}/delete', 'PageController@destroyRevision');

            // Chapters
            Route::get('/{bookSlug}/chapter/{chapterSlug}/create-page', 'PageController@create');
            Route::post('/{bookSlug}/chapter/{chapterSlug}/create-guest-page', 'PageController@createAsGuest');
            Route::get('/{bookSlug}/create-chapter', 'ChapterController@create');
            Route::post('/{bookSlug}/create-chapter', 'ChapterController@store');
            Route::get('/{bookSlug}/chapter/{chapterSlug}', 'ChapterController@show');
            Route::put('/{bookSlug}/chapter/{chapterSlug}', 'ChapterController@update');
            Route::get('/{bookSlug}/chapter/{chapterSlug}/move', 'ChapterController@showMove');
            Route::put('/{bookSlug}/chapter/{chapterSlug}/move', 'ChapterController@move');
            Route::get('/{bookSlug}/chapter/{chapterSlug}/edit', 'ChapterController@edit');
            Route::get('/{bookSlug}/chapter/{chapterSlug}/permissions', 'ChapterController@showPermissions');
            Route::get('/{bookSlug}/chapter/{chapterSlug}/export/pdf', 'ChapterController@exportPdf');
            Route::get('/{bookSlug}/chapter/{chapterSlug}/export/html', 'ChapterController@exportHtml');
            Route::get('/{bookSlug}/chapter/{chapterSlug}/export/plaintext', 'ChapterController@exportPlainText');
            Route::put('/{bookSlug}/chapter/{chapterSlug}/permissions', 'ChapterController@permissions');
            Route::get('/{bookSlug}/chapter/{chapterSlug}/delete', 'ChapterController@showDelete');
            Route::delete('/{bookSlug}/chapter/{chapterSlug}', 'ChapterController@destroy');
        });

        // Settings
        Route::group(['prefix' => 'settings'], function () {
            Route::get('/', 'SettingController@index')->name('settings');
            Route::post('/', 'SettingController@update');

            // Maintenance
            Route::get('/maintenance', 'SettingController@showMaintenance');
            Route::delete('/maintenance/cleanup-images', 'SettingController@cleanupImages');

            // Users
            Route::get('/users', 'UserController@index');
            Route::get('/users/create', 'UserController@create');
            Route::get('/users/{id}/delete', 'UserController@delete');
            Route::patch('/users/{id}/switch-book-view', 'UserController@switchBookView');
            Route::patch('/users/{id}/switch-shelf-view', 'UserController@switchShelfView');
            Route::patch('/users/{id}/change-sort/{type}', 'UserController@changeSort');
            Route::patch('/users/{id}/update-expansion-preference/{key}', 'UserController@updateExpansionPreference');
            Route::post('/users/create', 'UserController@store');
            Route::get('/users/{id}', 'UserController@edit');
            Route::put('/users/{id}', 'UserController@update');
            Route::delete('/users/{id}', 'UserController@destroy');

            // Roles
            Route::get('/roles', 'PermissionController@listRoles');
            Route::get('/roles/new', 'PermissionController@createRole');
            Route::post('/roles/new', 'PermissionController@storeRole');
            Route::get('/roles/delete/{id}', 'PermissionController@showDeleteRole');
            Route::delete('/roles/delete/{id}', 'PermissionController@deleteRole');
            Route::get('/roles/{id}', 'PermissionController@editRole');
            Route::put('/roles/{id}', 'PermissionController@updateRole');
        });


    });

    // AJAX routes
    Route::put('/ajax/page/{id}/save-draft', 'PageController@saveDraft');
    Route::get('/ajax/page/{id}', 'PageController@getPageAjax');
    Route::delete('/ajax/page/{id}', 'PageController@ajaxDestroy');

    // Tag routes (AJAX)
    Route::group(['prefix' => 'ajax/tags'], function () {
        Route::get('/get/{entityType}/{entityId}', 'TagController@getForEntity');
        Route::get('/suggest/names', 'TagController@getNameSuggestions');
        Route::get('/suggest/values', 'TagController@getValueSuggestions');
    });

//    Route::get('/ajax/search/entities', 'SearchController@searchEntitiesAjax');

    // Comments
    Route::post('/ajax/page/{pageId}/comment', 'CommentController@savePageComment');
    Route::put('/ajax/comment/{id}', 'CommentController@update');
    Route::delete('/ajax/comment/{id}', 'CommentController@destroy');

    // Attachments routes
    Route::get('/attachments/{id}', 'AttachmentController@get');
    Route::post('/attachments/upload', 'AttachmentController@upload');
    Route::post('/attachments/upload/{id}', 'AttachmentController@uploadUpdate');
    Route::post('/attachments/link', 'AttachmentController@attachLink');
    Route::put('/attachments/{id}', 'AttachmentController@update');
    Route::get('/attachments/get/page/{pageId}', 'AttachmentController@listForPage');
    Route::put('/attachments/sort/page/{pageId}', 'AttachmentController@sortForPage');
    Route::delete('/attachments/{id}', 'AttachmentController@delete');

    Route::get('/custom-head-content', 'HomeController@customHeadContent');

    // Search
//    Route::get('/search', 'SearchController@search');                                                                                                                             
   Route::get('/searchGrid', 'SearchController@searchGrid')->name('searchGrid');
//    Route::get('/search/book/{bookId}', 'SearchController@searchBook');
//    Route::get('/search/chapter/{bookId}', 'SearchController@searchChapter');
//    Route::get('/search/entity/siblings', 'SearchController@searchSiblings');

});
