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

use App\Helpers\TwilioHelper;

Auth::routes();
Route::post('customer/add_customer_address', 'CustomerController@add_customer_address');

//Route::get('unused_category', 'TestingController@Demo');

Route::get('/test/dummydata', 'TestingController@testingFunction');

Route::get('/test/test', 'OrderController@testEmail');
Route::get('/memory', function () {
    return view('memory');
})->name('memory');

Route::get('/test/pushProduct', 'TmpTaskController@testPushProduct');
Route::get('/test/fixBrandPrice', 'TmpTaskController@fixBrandPrice');
Route::get('/test/deleteChatMessages', 'TmpTaskController@deleteChatMessages');
Route::get('/test/deleteProductImages', 'TmpTaskController@deleteProductImages');
Route::get('/test/deleteQueue', 'TmpTaskController@deleteQueue');


Route::get('/test/analytics', 'AnalyticsController@cronShowData');
Route::get('/test/analytics-user', 'AnalyticsController@cronGetUserShowData')->name('test.google.analytics');

Route::get('/test/dhl', 'TmpTaskController@test');

Route::middleware('auth')->group(function()
{
    Route::get('create-media-image', 'CustomerController@testImage');
Route::get('generate-favicon', 'HomeController@generateFavicon');


Route::get('/products/affiliate', 'ProductController@affiliateProducts');
Route::post('/products/published', 'ProductController@published');

//Route::get('/home', 'HomeController@index')->name('home');
Route::get('/productselection/list', 'ProductSelectionController@sList')->name('productselection.list');
Route::get('/productsearcher/list', 'ProductSearcherController@sList')->name('productsearcher.list');
Route::post('/productselection/email-set', 'ProductSelectionController@emailTplSet')->name('productselection.email.set');
// adding chat contro



Route::get('/mageOrders', 'MagentoController@get_magento_orders');

Route::get('/message', 'MessageController@index')->name('message');
Route::post('/message', 'MessageController@store')->name('message.store');
Route::post('/message/{message}', 'MessageController@update')->name('message.update');
Route::post('/message/{id}/removeImage', 'MessageController@removeImage')->name('message.removeImage');
Route::get('/chat/getnew', 'ChatController@checkfornew')->name('checkfornew');
Route::get('/chat/updatenew', 'ChatController@updatefornew')->name('updatefornew');
//Route::resource('/chat','ChatController@getmessages');

Route::get('users/check/logins', 'UserController@checkUserLogins')->name('users.check.logins');
Route::resource('courier', 'CourierController');
Route::resource('product-location', 'ProductLocationController');
});


//Google Web Master Routes
Route::prefix('googlewebmaster')->middleware('auth')->group(static function () {
    
    Route::get('get-site-submit-hitory','GoogleWebMasterController@getSiteSubmitHitory')->name('googlewebmaster.get.history') ;
    Route::post('re-submit-site','GoogleWebMasterController@ReSubmitSiteToWebmaster')->name('googlewebmaster.re-submit.site.webmaster') ;
    Route::get('get-access-token','GoogleWebMasterController@googleLogin')->name('googlewebmaster.get-access-token') ;
    Route::get('/index', 'GoogleWebMasterController@index')->name('googlewebmaster.index');

    Route::get('update/sites/data','GoogleWebMasterController@updateSitesData')->name('update.sites.data');
   
});



Route::prefix('product')->middleware('auth')->group(static function () {
    Route::get('manual-crop/assign-products', 'Products\ManualCroppingController@assignProductsToUser');
    Route::resource('manual-crop', 'Products\ManualCroppingController');
    Route::get('hscode', 'ProductController@hsCodeIndex');
    Route::post('hscode/save-group', 'ProductController@saveGroupHsCode')->name('hscode.save.group');
    Route::post('hscode/edit-group', 'ProductController@editGroup')->name('hscode.edit.group');
    Route::post('store-website-description', 'ProductController@storeWebsiteDescription')->name('product.store.website.description');
    Route::post('test', 'ProductController@test')->name('product.test.template');
});

Route::prefix('logging')->middleware('auth')->group(static function () {

    Route::any('list/api/logs','LaravelLogController@apiLogs')->name('api-log-list');
    Route::any('list/api/logs/generate-report','LaravelLogController@generateReport')->name('api-log-list-generate-report');

   // Route::post('filter/list/api/logs','LaravelLogController@apiLogs')->name('api-filter-logs')
    Route::get('list-magento', 'Logging\LogListMagentoController@index')->name('list.magento.logging');
    Route::get('list-magento/error-reporting', 'Logging\LogListMagentoController@errorReporting')->name('list.magento.error-reporting');
    Route::post('list-magento/{id}', 'Logging\LogListMagentoController@updateMagentoStatus');
    Route::get('show-error-logs/{product_id}/{website_id?}', 'Logging\LogListMagentoController@showErrorLogs')->name('list.magento.show-error-logs');
    Route::get('show-error-log-by-id/{id}', 'Logging\LogListMagentoController@showErrorByLogId')->name('list.magento.show-error-log-by-id');

    Route::get('list-laravel-logs', 'LaravelLogController@index')->name('logging.laravel.log');
    Route::get('live-laravel-logs', 'LaravelLogController@liveLogs')->name('logging.live.logs');

    Route::get('live-laravel-logs-single', 'LaravelLogController@liveLogsSingle');

    Route::get('keyword-create', 'LaravelLogController@LogKeyword');
    Route::post('assign', 'LaravelLogController@assign')->name('logging.assign');
    Route::get('sku-logs', 'Logging\LogScraperController@logSKU')->name('logging.scrap.log');
    Route::get('sku-logs-errors', 'Logging\LogScraperController@logSKUErrors')->name('logging.sku.errors.log');
    Route::get('list-visitor-logs', 'VisitorController@index')->name('logging.visitor.log');
    // Route::get('log-scraper', 'Logging\LogScraperController@index')->name('log-scraper.index');
    Route::get('live-scraper-logs', 'LaravelLogController@scraperLiveLogs')->name('logging.live.scraper-logs');
    Route::get('live-laravel-logs/downloads', 'LaravelLogController@liveLogDownloads')->name('logging.live.downloads');
    Route::get('live-magento-logs/downloads', 'LaravelLogController@liveMagentoDownloads')->name('logging.live.magento.downloads');
    //TODO::Magento Product API call Route
    Route::get('magento-product-api-call', 'Logging\LogListMagentoController@showMagentoProductAPICall')->name('logging.magento.product.api.call');
    Route::post('magento-product-skus-ajax', 'Logging\LogListMagentoController@getMagentoProductAPIAjaxCall')->name('logging.magento.product.api.ajax.call');
});

Route::get('log-scraper-api', 'Logging\LogScraperController@scraperApiLog')->middleware('auth')->name('log-scraper.api');
Route::get('log-scraper', 'Logging\LogScraperController@index')->middleware('auth')->name('log-scraper.index');

Route::prefix('category-messages')->middleware('auth')->group(function () {
    Route::post('bulk-messages/addToDND', 'BulkCustomerRepliesController@addToDND');
    Route::post('bulk-messages/removeFromDND', 'BulkCustomerRepliesController@removeFromDND');
    Route::post('bulk-messages/keyword', 'BulkCustomerRepliesController@storeKeyword');
    Route::post('bulk-messages/keyword/update-whatsappno', 'BulkCustomerRepliesController@updateWhatsappNo')->name('bulk-messages.whatsapp-no');
    Route::post('bulk-messages/send-message', 'BulkCustomerRepliesController@sendMessagesByKeyword');
    Route::resource('bulk-messages', 'BulkCustomerRepliesController');
    Route::resource('keyword', 'KeywordToCategoryController');
    Route::resource('category', 'CustomerCategoryController');
});

Route::group(['middleware' => ['auth', 'optimizeImages']], function () {
    //Crop Reference
    Route::get('crop-references', 'CroppedImageReferenceController@index');
    Route::get('crop-references-grid', 'CroppedImageReferenceController@grid');
    //Ajax request for select2
    Route::get('/crop-references-grid/getCategories', 'CroppedImageReferenceController@getCategories');
    Route::get('/crop-references-grid/getProductIds', 'CroppedImageReferenceController@getProductIds');
    Route::get('/crop-references-grid/getBrands', 'CroppedImageReferenceController@getBrands');
    Route::get('/crop-references-grid/getSupplier', 'CroppedImageReferenceController@getSupplier');
    Route::get('crop-referencesx', 'CroppedImageReferenceController@index');

    Route::get('/magento/status', 'MagentoController@addStatus');
    Route::post('/magento/status/save', 'MagentoController@saveStatus')->name('magento.save.status');

    Route::post('crop-references-grid/reject', 'CroppedImageReferenceController@rejectCropImage');

    Route::get('public-key', 'EncryptController@index')->name('encryption.index');
    Route::post('save-key', 'EncryptController@saveKey')->name('encryption.save.key');
    Route::post('forget-key', 'EncryptController@forgetKey')->name('encryption.forget.key');

    Route::get('reject-listing-by-supplier', 'ProductController@rejectedListingStatistics');
    Route::get('lead-auto-fill-info', 'LeadsController@leadAutoFillInfo');

    Route::get('color-reference/used-products', 'ColorReferenceController@usedProducts');

    Route::get('color-reference-fix-issue','ColorReferenceController@cmdcallcolorfix')->name('erp-color-fix-cmd');

    Route::get('color-reference/affected-product', 'ColorReferenceController@affectedProduct');
    Route::post('color-reference/update-color', 'ColorReferenceController@updateColor');

    Route::resource('color-reference', 'ColorReferenceController');

    Route::get('compositions/{id}/used-products', 'CompositionsController@usedProducts')->name('compositions.used-products');
    Route::get('compositions/affected-product', 'CompositionsController@affectedProduct');
    Route::post('compositions/update-composition', 'CompositionsController@updateComposition');
    Route::post('compositions/update-multiple-composition', 'CompositionsController@updateMultipleComposition');
    Route::post('compositions/update-all-composition', 'CompositionsController@updateAllComposition');
    Route::post('compositions/replace-composition', 'CompositionsController@replaceComposition')->name('compositions.replace');
    Route::get('compositions/{id}/history', 'CompositionsController@history')->name('compositions.history');
    Route::get('compositions/delete-unused', 'CompositionsController@deleteUnused')->name('compositions.delete.unused');
    Route::post('compositions/update-name', 'CompositionsController@updateName')->name('compositions.update.name');
    Route::resource('compositions', 'CompositionsController');

    Route::post('descriptions/store', 'ChangeDescriptionController@store')->name('descriptions.store');

    Route::post('descriptions/delete', 'ChangeDescriptionController@destroy')->name('descriptions.delete');

    Route::resource('descriptions', 'ChangeDescriptionController');

    Route::get('crop/approved', 'ProductCropperController@getApprovedImages');
    Route::get('order-cropped-images', 'ProductCropperController@showCropVerifiedForOrdering');
    Route::post('save-sequence/{id}', 'ProductCropperController@saveSequence');
    Route::get('skip-sequence/{id}', 'ProductCropperController@skipSequence');
    Route::get('reject-sequence/{id}', 'ProductCropperController@rejectSequence');
    Route::post('ammend-crop/{id}', 'ProductCropperController@ammendCrop');
    Route::get('products/auto-cropped', 'ProductCropperController@getListOfImagesToBeVerified');
    Route::get('products/crop-issue-summary', 'ProductCropperController@cropIssuesPage');
    Route::get('products/rejected-auto-cropped', 'ProductCropperController@showRejectedCrops');
    Route::get('products/auto-cropped/{id}', 'ProductCropperController@showImageToBeVerified');
    Route::get('products/auto-cropped/{id}/show-rejected', 'ProductCropperController@showRejectedImageToBeverified');
    Route::get('products/auto-cropped/{id}/approve', 'ProductCropperController@approveCrop');
    Route::post('products/auto-cropped/{id}/approve-rejected', 'ProductCropperController@approveRejectedCropped');
    Route::get('products/auto-cropped/{id}/reject', 'ProductCropperController@rejectCrop');
    Route::get('products/auto-cropped/{id}/crop-approval-confirmation', 'ProductCropperController@cropApprovalConfirmation');
    Route::get('customer/livechat-redirect', 'LiveChatController@reDirect');
    Route::get('livechat/setting', 'LiveChatController@setting');
    Route::post('livechat/save', 'LiveChatController@save')->name('livechat.save');
    Route::post('livechat/remove', 'LiveChatController@remove')->name('livechat.remove');
    Route::resource('roles', 'RoleController');
    Route::resource('permissions', 'PermissionController');
    Route::get('permissions/grandaccess/users', 'PermissionController@users')->name('permissions.users');
    Route::get('unauthorized', 'RoleController@unAuthorized');
    Route::get('users/logins', 'UserController@login')->name('users.login.index');
    Route::post('users/status-change', 'UserController@statusChange');
    Route::get('users/loginips', 'UserController@loginIps')->name('users.login.ips');
    Route::get('users/add-system-ip', 'UserController@addSystemIp');
    Route::get('users/delete-system-ip', 'UserController@deleteSystemIp');
    Route::get('permissions/grandaccess/users', 'PermissionController@users')->name('permissions.users');
    Route::get('userlogs', 'UserLogController@index')->name('userlogs.index');
    Route::get('userlogs/{$id}', 'UserLogController@index');
    Route::get('userlogs/datatables', 'UserLogController@getData')->name('userlogs.datatable');
    Route::get('users/{id}/assigned', 'UserController@showAllAssignedProductsForUser');
    Route::post('users/{id}/unassign/products', 'UserController@unassignProducts');
    Route::post('users/{id}/assign/products', 'UserController@assignProducts')->name('user.assign.products');
    Route::post('users/{id}/activate', 'UserController@activate')->name('user.activate');
    Route::resource('users', 'UserController');
    Route::resource('listing-payments', 'ListingPaymentsController');

    Route::get('products/assign-product', 'ProductController@getPreListProducts')->name('products.product-assign');
    Route::post('products/assign-product', 'ProductController@assignProduct')->name('products.product-assign-submit');
    // Translation Language
    Route::post('translationLanguage/add', 'ProductController@translationLanguage')->name('translation.language.add');
    // Product Translation Rejection
    Route::post('productTranslation/reject', 'ProductController@productTranslationRejection')->name('product.translation.rejection');


    Route::get('products/product-translation', 'ProductController@productTranslation')->name('products.product-translation');
    Route::get('products/product-translation/{id}', 'ProductController@viewProductTranslation')->name('products.product-translation.view');
    Route::post('products/product-translation/submit/{product_translation_id}', 'ProductController@editProductTranslation')->name('products.product-translation.edit');
    Route::get('products/product-translation/details/{id}/{locale}', 'ProductController@getProductTranslationDetails')->name('products.product-translation.locale');
    Route::get('product/listing/users', 'ProductController@showListigByUsers');
    Route::get('products/listing', 'ProductController@listing')->name('products.listing');
    Route::get('products/listing/final', 'ProductController@approvedListing')->name('products.listing.approved');
    Route::get('products/listing/final/{images?}', 'ProductController@approvedListing')->name('products.listing.approved.images');
    Route::post('products/listing/final/pushproduct', 'ProductController@pushProduct');
    Route::post('products/changeautopushvalue', 'ProductController@changeAutoPushValue');
    
    Route::get('products/listing/final-crop', 'ProductController@approvedListingCropConfirmation');
    Route::post('products/listing/final-crop-image', 'ProductController@cropImage')->name('products.crop.image');


    Route::get('products/listing/magento', 'ProductController@approvedMagento')->name('products.listing.magento');
    Route::get('products/listing/rejected', 'ProductController@showRejectedListedProducts');
    Route::get('product/listing-remark', 'ProductController@addListingRemarkToProduct');
    Route::get('product/update-listing-remark', 'ProductController@updateProductListingStats');
    Route::post('product/crop_rejected_status', 'ProductController@crop_rejected_status');
    Route::post('product/all_crop_rejected_status', 'ProductController@all_crop_rejected_status');

    // Added Mass Action
    Route::get('product/delete-product', 'ProductController@deleteProduct')->name('products.mass.delete');
    Route::get('products/approveProduct', 'ProductController@approveProduct')->name('products.mass.approve');;

    Route::get('product/relist-product', 'ProductController@relistProduct');
    Route::get('products/stats', 'ProductController@productStats');
    //ajay singh
    //Route::get('products/scrap-logs', 'ProductController@productScrapLog');
    Route::get('products/status-history', 'ProductController@productScrapLog');
    Route::get('products/description', 'ProductController@productDescription');

    Route::post('products/{id}/updateName', 'ProductController@updateName');
    Route::post('products/{id}/updateDescription', 'ProductController@updateDescription');
    Route::post('products/{id}/updateComposition', 'ProductController@updateComposition');
    Route::post('products/{id}/updateColor', 'ProductController@updateColor');
    Route::post('products/{id}/updateCategory', 'ProductController@updateCategory');
    Route::post('products/{id}/updateSize', 'ProductController@updateSize');
    Route::post('products/{id}/updatePrice', 'ProductController@updatePrice');
    Route::get('products/{id}/quickDownload', 'ProductController@quickDownload')->name('products.quick.download');
    Route::post('products/{id}/quickUpload', 'ProductController@quickUpload')->name('products.quick.upload');
    Route::post('products/{id}/listMagento', 'ProductController@listMagento');
    Route::post('products/{id}/unlistMagento', 'ProductController@unlistMagento');
    Route::post('products/{id}/approveMagento', 'ProductController@approveMagento');
    Route::post('products/{id}/updateMagento', 'ProductController@updateMagento');
    Route::post('products/updateMagentoProduct', 'ProductController@updateMagentoProduct')->name('product.update.magento');
    Route::post('products/{id}/approveProduct', 'ProductController@approveProduct');
    Route::post('products/{id}/originalCategory', 'ProductController@originalCategory');
    Route::post('products/{id}/originalColor', 'ProductController@originalColor');
    Route::post('products/{id}/submitForApproval', 'ProductController@submitForApproval');
    Route::get('products/{id}/category-history', 'ProductCategoryController@history');
    Route::post('products/{id}/addListingRemarkToProduct', 'ProductController@addListingRemarkToProduct');
    Route::post('products/{id}/
    ', 'ProductController@updateApprovedBy');
    //    Route::get('products/{id}/color-historyproducts/{id}/color-history', 'ProductColorController@history');

    Route::post('products/add/def_cust/{id}', 'ProductController@add_product_def_cust')->name('products.add.def_cust');

    Route::post('products/{id}/changeCategorySupplier', 'ProductController@changeAllCategoryForAllSupplierProducts');
    Route::post('products/{id}/changeColorSupplier', 'ProductController@changeAllColorForAllSupplierProducts');
    Route::resource('products', 'ProductController');
    Route::resource('attribute-replacements', 'AttributeReplacementController');
    Route::post('products/bulk/update', 'ProductController@bulkUpdate')->name('products.bulk.update');
    Route::post('products/{id}/archive', 'ProductController@archive')->name('products.archive');
    Route::post('products/{id}/restore', 'ProductController@restore')->name('products.restore');
    Route::get('/manual-image-upload', 'ProductSelectionController@manualImageUpload')->name('manual.image.upload');
    Route::resource('productselection', 'ProductSelectionController');
    Route::get('productattribute/delSizeQty/{id}', 'ProductAttributeController@delSizeQty');
    Route::resource('productattribute', 'ProductAttributeController');
    Route::resource('productsearcher', 'ProductSearcherController');
    Route::resource('productimagecropper', 'ProductCropperController');
    Route::resource('productsupervisor', 'ProductSupervisorController');
    Route::resource('productlister', 'ProductListerController');
    Route::resource('productapprover', 'ProductApproverController');
    Route::get('productinventory/product-images/{id}', 'ProductInventoryController@getProductImages')->name('productinventory.product-images');
    Route::post('productinventory/import', 'ProductInventoryController@import')->name('productinventory.import');
    Route::get('productinventory/list', 'ProductInventoryController@list')->name('productinventory.list');
    Route::get('productinventory/inventory-list', 'ProductInventoryController@inventoryList')->name('productinventory.inventory-list');
    Route::get('productinventory/new-inventory-list', 'ProductInventoryController@inventoryListNew')->name('productinventory.inventory-list-new');
    Route::get('download-report', 'ProductInventoryController@downloadReport')->name('download-report');
    Route::get('download-scrapped-report', 'ProductInventoryController@downloadScrapReport')->name('download-scrapped-report');
    Route::post('productinventory/change-size-system', 'ProductInventoryController@changeSizeSystem')->name('productinventory.change-size-system');
    Route::post('productinventory/change-product-status', 'ProductInventoryController@updateStatus')->name('productinventory.update-status');
    Route::post('productinventory/store-erp-size', 'ProductInventoryController@changeErpSize')->name('productinventory.change-erp-size');

    Route::get('productinventory/inventory-history/{id}', 'ProductInventoryController@inventoryHistory')->name('productinventory.inventory-history');
    Route::post('productinventory/merge-scrap-brand', 'ProductInventoryController@mergeScrapBrand')->name('productinventory.merge-scrap-brand');

    Route::get('product/history/by/supplier','ProductInventoryController@supplierProductHistory')->name('supplier.product.history');
    Route::get('product/history/by/supplier-brand','ProductInventoryController@supplierProductHistoryBrand')->name('supplier.product.history.brand');
    Route::get('product/discount/files','ProductInventoryController@supplierDiscountFiles')->name('supplier.discount.files');
    Route::post('product/discount/files','ProductInventoryController@exportExcel')->name('supplier.discount.files.post');

    Route::get('supplier/{supplier}/products/summary/','ProductInventoryController@supplierProductSummary')->name('supplier.product.summary');


    Route::get('productinventory/all-suppliers/{id}', 'ProductInventoryController@getSuppliers')->name('productinventory.all-suppliers');
    Route::resource('productinventory', 'ProductInventoryController');

    Route::prefix('product-inventory')->group(function () {
        Route::get('/', 'NewProductInventoryController@index')->name('product-inventory.new');
        Route::get('fetch/images', 'NewProductInventoryController@fetchImgGoogle')->name('product-inventory.fetch.img.google');
        Route::post('/push-in-shopify-records', 'NewProductInventoryController@pushInStore')->name('product-inventory.pushInStore');
        Route::prefix('{id}')->group(function () {
            Route::get('push-in-shopify', 'NewProductInventoryController@pushInShopify')->name('product-inventory.push-in-shopify');
        });
    });

    Route::post('facebook-posts/save', 'FacebookPostController@store')->name('facebook-posts/save');
    Route::get('facebook-posts/create', 'FacebookPostController@create')->name('facebook-posts.create');
    Route::resource('facebook-posts', 'FacebookPostController');


    Route::post('facebook-posts/save', 'FacebookPostController@store')->name('facebook-posts/save');
    Route::get('facebook-posts/create', 'FacebookPostController@create')->name('facebook-posts.create');
    Route::resource('facebook-posts', 'FacebookPostController');

    Route::resource('sales', 'SaleController');
    Route::resource('stock', 'StockController');
    Route::post('stock/track/package', 'StockController@trackPackage')->name('stock.track.package');
    Route::delete('stock/{id}/permanentDelete', 'StockController@permanentDelete')->name('stock.permanentDelete');
    Route::post('stock/privateViewing/create', 'StockController@privateViewingStore')->name('stock.privateViewing.store');
    Route::get('stock/private/viewing', 'StockController@privateViewing')->name('stock.private.viewing');
    Route::delete('stock/private/viewing/{id}', 'StockController@privateViewingDestroy')->name('stock.private.viewing.destroy');
    Route::post('stock/private/viewing/upload', 'StockController@privateViewingUpload')->name('stock.private.viewing.upload');
    Route::post('stock/private/viewing/{id}/updateStatus', 'StockController@privateViewingUpdateStatus')->name('stock.private.viewing.updateStatus');
    Route::post('stock/private/viewing/{id}/updateOfficeBoy', 'StockController@updateOfficeBoy')->name('stock.private.viewing.updateOfficeBoy');
  
  
    Route::post('sop', 'ProductController@saveSOP')->name('sop.add');
    Route::get('sop', 'ProductController@getdata')->name('sop.index');
    Route::delete('sop/{id}', 'ProductController@destroyname')->name('sopdel.destroyname');
    Route::get('sop/edit', 'ProductController@edit')->name('editName');
    Route::post('update', 'ProductController@update')->name('updateName');
    Route::get('sop/search', 'ProductController@searchsop');
    Route::get('soplogs', 'ProductController@sopnamedata_logs')->name('sopname.logs');



    Route::get('product/delete-image', 'ProductController@deleteImage')->name('product.deleteImages');

    // Delivery Approvals
    Route::post('deliveryapproval/{id}/updateStatus', 'DeliveryApprovalController@updateStatus')->name('deliveryapproval.updateStatus');
    Route::resource('deliveryapproval', 'DeliveryApprovalController');

    //  Route::resource('activity','ActivityConroller');

    Route::get('brand/get_all_images', 'BrandController@get_all_images')->name('brand.get_all_images');//Purpose : upload logo - DEVTASK-4278
    Route::get('brand/logo_data', 'BrandController@fetchlogos')->name('brand.logo_data');//Purpose : Get Brand Logo - DEVTASK-4278
    Route::post('brand/uploadlogo', 'BrandController@uploadlogo')->name('brand.uploadlogo');//Purpose : upload logo - DEVTASK-4278
    Route::post('brand/set_logo_with_brand', 'BrandController@set_logo_with_brand')->name('brand.set_logo_with_brand');//Purpose : upload logo with brand - DEVTASK-4278
    Route::post('brand/remove_logo', 'BrandController@remove_logo')->name('brand.remove_logo');//Purpose : remove logo - DEVTASK-4278

    // For Brand size chart
    Route::get('brand/size/chart', 'BrandSizeChartController@index')->name('brand/size/chart');
    Route::get('brand/create/size/chart', 'BrandSizeChartController@createSizeChart')->name('brand/create/size/chart');
    Route::post('brand/store/size/chart', 'BrandSizeChartController@storeSizeChart')->name('brand/store/size/chart');

    Route::post('brand/store-category-segment-discount', 'BrandController@storeCategorySegmentDiscount')->name('brand.store_category_segment_discount');
    Route::post('brand/attach-website', 'BrandController@attachWebsite');
    Route::post('brand/change-segment', 'BrandController@changeSegment');
    Route::post('brand/update-reference', 'BrandController@updateReference');
    Route::post('brand/merge-brand', 'BrandController@mergeBrand');
    Route::post('brand/unmerge-brand', 'BrandController@unMergeBrand')->name('brand.unmerge-brand');
    Route::get('brand/{id}/create-remote-id', 'BrandController@createRemoteId');
    Route::get('brand/{id}/activities', 'BrandController@activites')->name('brand.activities');
    Route::get('brand/fetch-new', 'BrandController@fetchNewBrands')->name('brand.fetchnew');
    Route::resource('brand', 'BrandController');

   Route::put('brand/priority/{id}', 'BrandController@priority');

   


    Route::resource('reply', 'ReplyController');

    Route::post('reply/chatbot/questions', 'ReplyController@chatBotQuestionT')->name('reply.create.chatbot_questions');
    Route::post('reply/category/store', 'ReplyController@categoryStore')->name('reply.category.store');

    // Auto Replies
    Route::post('autoreply/{id}/updateReply', 'AutoReplyController@updateReply');

    Route::post('autoreply/delete-chat-word', 'AutoReplyController@deleteChatWord');
    
    Route::get('autoreply/replied-chat/{id}', 'AutoReplyController@getRepliedChat');
    
    Route::post('autoreply/save-group', 'AutoReplyController@saveGroup')->name('autoreply.save.group');
    
    Route::post('autoreply/save-group/phrases', 'AutoReplyController@saveGroupPhrases')->name('autoreply.save.group.phrases');
    
    Route::post('autoreply/save-by-question', 'AutoReplyController@saveByQuestion');
    
    Route::post('autoreply/delete-most-used-phrases', 'AutoReplyController@deleteMostUsedPharses')->name("chatbot.delete-most-used-pharses");
    
    Route::get('autoreply/get-phrases', 'AutoReplyController@getPhrases');
    
    Route::post('autoreply/phrases/reply', 'AutoReplyController@getPhrasesReply')->name('autoreply.group.phrases.reply');
    
    Route::get('autoreply/phrases/reply-response', 'AutoReplyController@getPhrasesReplyResponse')->name('autoreply.group.phrases.reply.response');

    Route::resource('autoreply', 'AutoReplyController');
    
    Route::get('most-used-words', 'AutoReplyController@mostUsedWords')->name("chatbot.mostUsedWords");
    Route::get('most-used-phrases', 'AutoReplyController@mostUsedPhrases')->name("chatbot.mostUsedPhrases");

    Route::get('most-used-phrases/deleted', 'AutoReplyController@mostUsedPhrasesDeleted')->name("chatbot.mostUsedPhrasesDeleted");
    Route::get('most-used-phrases/deleted/records', 'AutoReplyController@mostUsedPhrasesDeletedRecords')->name("chatbot.mostUsedPhrasesDeletedRecords");
    Route::post('settings/update', 'SettingController@update');
    Route::post('settings/updateAutomatedMessages', 'SettingController@updateAutoMessages')->name('settings.update.automessages');
    Route::resource('settings', 'SettingController');
    
    Route::get('category/child-categories', 'CategoryController@childCategory')->name('category.child-category');
    Route::get('category/edit-category', 'CategoryController@childEditCategory')->name('category.child-edit-category');
    Route::post('category/{edit}/edit-category', 'CategoryController@updateCategory')->name('category.child-update-category');

    Route::get('category/references/used-products', 'CategoryController@usedProducts');
    Route::post('category/references/update-reference', 'CategoryController@updateReference');
    Route::get('category/references', 'CategoryController@mapCategory')->name('category.map-category');
    Route::post('category/references', 'CategoryController@saveReferences');
    Route::post('category/references/affected-product', 'CategoryController@affectedProduct');
    Route::post('category/references/affected-product-new', 'CategoryController@affectedProductNew');
    Route::post('category/references/update-category', 'CategoryController@updateCategoryReference');
    Route::post('category/references/update-multiple-category', 'CategoryController@updateMultipleCategoryReference');


    Route::post('category/update-field', 'CategoryController@updateField');
    Route::post('category/reference', 'CategoryController@saveReference');
    Route::post('category/save-form', 'CategoryController@saveForm')->name("category.save.form");
    Route::get('category/delete-unused', 'CategoryController@deleteUnused')->name('category.delete.unused');
    //new category reference

    Route::get('category/new-references', 'CategoryController@newCategoryReferenceIndex');
    Route::post('category/new-references/save-category', 'CategoryController@saveCategoryReference');
    Route::get('category/fix-autosuggested', 'CategoryController@fixAutoSuggested')->name("category.fix-autosuggested");
    Route::get('category/fix-autosuggested-string', 'CategoryController@fixAutoSuggestedString')->name("category.fix-autosuggested-via-str");
    Route::get('category/{id}/history', 'CategoryController@history');

    Route::get('sizes/references', 'SizeController@sizeReference');
    Route::get('sizes/{id}/used-products', 'SizeController@usedProducts');

    Route::post('sizes/references/chamge', 'SizeController@referenceAdd');
    Route::get('sizes/affected-product', 'SizeController@affectedProduct');
    Route::post('sizes/update-sizes', 'SizeController@updateSizes');

    Route::resource('category', 'CategoryController');
    Route::resource('category-segment', 'CategorySegmentController');

    Route::resource('resourceimg', 'ResourceImgController');
    Route::get('resourceimg/pending/1', 'ResourceImgController@pending');
    Route::post('add-resource', 'ResourceImgController@addResource')->name('add.resource');
    Route::post('add-resourceCat', 'ResourceImgController@addResourceCat')->name('add.resourceCat');
    Route::post('edit-resourceCat', 'ResourceImgController@editResourceCat')->name('edit.resourceCat');
    Route::post('remove-resourceCat', 'ResourceImgController@removeResourceCat')->name('remove.resourceCat');
    Route::post('acitvate-resourceCat', 'ResourceImgController@activateResourceCat')->name('activate.resourceCat');

    Route::get('resourceimg/pending', 'ResourceImgController@pending');


    Route::post('delete-resource', 'ResourceImgController@deleteResource')->name('delete.resource');
    Route::get('images/resource/{id}', 'ResourceImgController@imagesResource')->name('images/resource');

    Route::resource('benchmark', 'BenchmarkController');

    // adding lead routes
    Route::get('leads/imageGrid', 'LeadsController@imageGrid')->name('leads.image.grid');
    Route::post('leads/sendPrices', 'LeadsController@sendPrices')->name('leads.send.prices');
    Route::resource('leads', 'LeadsController');
    Route::post('leads/{id}/changestatus', 'LeadsController@updateStatus');
    Route::delete('leads/permanentDelete/{leads}', 'LeadsController@permanentDelete')->name('leads.permanentDelete');
    Route::resource('chat', 'ChatController');
    Route::get('erp-leads', 'LeadsController@erpLeads')->name('erp-leads.erpLeads');
    // Route::post('erp-leads', 'LeadsController@filterErpLeads')->name('erp-leads.filterErpLeads');
    Route::post('erp-leads-send-message', 'LeadsController@sendMessage')->name('erp-leads-send-message');
    Route::get('erp-leads/response', 'LeadsController@erpLeadsResponse')->name('leads.erpLeadsResponse');
    Route::get('erp-leads/history', 'LeadsController@erpLeadsHistory')->name('leads.erpLeadsHistory');
    Route::post('erp-leads/{id}/changestatus', 'LeadsController@updateErpStatus');
    Route::get('erp-leads/edit', 'LeadsController@erpLeadsEdit')->name('leads.erpLeads.edit');
    Route::get('erp-leads/create', 'LeadsController@erpLeadsCreate')->name('leads.erpLeads.create');
    Route::get('erp-leads/status/create', 'LeadsController@erpLeadsStatusCreate')->name('erpLeads.status.create');
    Route::post('erp-leads/status/update', 'LeadsController@erpLeadsStatusUpdate')->name('erpLeads.status.update');
    Route::get('erp-leads/status/change', 'LeadsController@erpLeadStatusChange')->name('erpLeads.status.change');
    Route::post('erp-leads/store', 'LeadsController@erpLeadsStore')->name('leads.erpLeads.store');
    Route::get('erp-leads/delete', 'LeadsController@erpLeadDelete')->name('leads.erpLeads.delete');
    Route::get('erp-leads/customer-search', 'LeadsController@customerSearch')->name('leads.erpLeads.customerSearch');
    Route::post('erp-lead-block-customer', 'LeadsController@blockcustomerlead')->name('leads.block.customer');

    //Cron
    Route::get('cron', 'CronController@index')->name('cron.index');
    Route::get('cron/run', 'CronController@runCommand')->name('cron.run.command');
    Route::get('cron/history/{id}', 'CronController@history')->name('cron.history');
    Route::post('cron/history/show', 'CronController@historySearch')->name('cron.history.search');
    Route::post('cron/gethistory/{id}', 'CronController@getCronHistory');


    Route::prefix('store-website')->middleware('auth')->group(static function () {
        Route::get('/status/all', 'OrderController@viewAllStatuses')->name('store-website.all.status');
        Route::get('/status/edit/{id}', 'OrderController@viewEdit')->name('store-website.status.edit');
        Route::post('/status/edit/{id}', 'OrderController@editStatus')->name('store-website.status.submitEdit');
        Route::get('/status/create', 'OrderController@viewCreateStatus');
        Route::post('/status/create', 'OrderController@createStatus')->name('store-website.submit.status');
        Route::get('/status/fetch', 'OrderController@viewFetchStatus');
        Route::post('/status/fetch', 'OrderController@fetchStatus')->name('store-website.fetch.status');
        Route::get('/status/fetchMasterStatus/{id}', 'OrderController@fetchMasterStatus');
    });

    //plesk
    Route::prefix('plesk')->middleware('auth')->group(static function () {
        Route::get('/domains', 'PleskController@index')->name('plesk.domains');
        Route::get('/domains/mail/create/{id}', 'PleskController@create')->name('plesk.domains.view-mail-create');
        Route::post('/domains/mail/create/{id}', 'PleskController@submitMail')->name('plesk.domains.submit-mail');
        Route::post('/domains/mail/delete/{id}', 'PleskController@deleteMail')->name('plesk.domains.delete-mail');
        Route::get('/domains/mail/accounts/{id}', 'PleskController@getMailAccounts')->name('plesk.domains.mail-accounts');
        Route::post('/domains/mail/change-password', 'PleskController@changePassword')->name('plesk.domains.mail-accounts.change-password');
        Route::get('/domains/view/{id}', 'PleskController@show')->name('plesk.domains.view');
    });



    //plesk
    Route::prefix('content-management')->middleware('auth')->group(static function () {
        Route::get('/', 'ContentManagementController@index')->name('content-management.index');
        Route::get('/preview-img/{id}', 'ContentManagementController@previewImage')->name('content-management.preview-img');
        Route::get('/manage/show-history', 'ContentManagementController@showHistory')->name('content-management.manage.show-history');
        Route::get('/social/account/create', 'ContentManagementController@viewAddSocialAccount')->name('content-management.social.create');
        Route::post('/social/account/create', 'ContentManagementController@addSocialAccount')->name('content-management.social.submit');
        Route::get('/manage/{id}', 'ContentManagementController@manageContent')->name('content-management.manage');
        Route::get('/manage/task-list/{id}', 'ContentManagementController@getTaskList')->name('content-management.manage.task-list');
        Route::get('/manage/preview-img/{id}', 'ContentManagementController@previewCategoryImage')->name('content-management.manage.preview-img');
        Route::get('/manage/milestone-task/{id}', 'ContentManagementController@getTaskMilestones')->name('content-management.manage.milestone-task');
        Route::post('/manage/save-category', 'ContentManagementController@saveContentCategory')->name('content-management.manage.save-category');
        Route::post('/manage/edit-category', 'ContentManagementController@editCategory')->name("content-management.category.edit");
        Route::post('/manage/save-content', 'ContentManagementController@saveContent')->name('content-management.manage.save-content');
        Route::post('/upload-documents', 'ContentManagementController@uploadDocuments')->name("content-management.upload-documents");
        Route::post('/save-documents', 'ContentManagementController@saveDocuments')->name("content-management.save-documents");
        Route::post('/delete-document', 'ContentManagementController@deleteDocument')->name("content-management.delete-documents");
        Route::post('/send-document', 'ContentManagementController@sendDocument')->name("content-management.send-documents");
        Route::post('/save-reviews', 'ContentManagementController@saveReviews')->name("content-management.save-reviews");
        Route::post('/manage/milestone-task/submit', 'ContentManagementController@submitMilestones')->name("content-management.submit-milestones");
        Route::post('/manage/attach/images', 'ContentManagementController@getAttachImages')->name("content-management.attach.images");
        Route::get('/download/attach/images', 'ContentManagementController@downloadAttachImages')->name("content-management.download.image");
        Route::prefix('{id}')->group(function () {
            Route::get('list-documents', 'ContentManagementController@listDocuments')->name("content-management.list-documents");
            Route::prefix('remarks')->group(function () {
                Route::get('/', 'ContentManagementController@remarks')->name("content-management.remarks");
                Route::post('/', 'ContentManagementController@saveRemarks')->name("content-management.saveRemarks");
            });
        });


        Route::prefix('contents')->group(function () {
            Route::get('/', 'ContentManagementController@viewAllContents')->name("content-management.contents");
        });
    });

    Route::prefix('content-management-status')->group(function () {
        Route::get('/', 'StoreSocialContentStatusController@index')->name('content-management-status.index');
        Route::post('save', 'StoreSocialContentStatusController@save')->name('content-management-status.save');
        Route::post('statusEdit', 'StoreSocialContentStatusController@statusEdit')->name('content-management-status.edit-status');
        Route::post('store', 'StoreSocialContentStatusController@store')->name('content-management-status.store');
        Route::post('merge-status', 'StoreSocialContentStatusController@mergeStatus')->name('content-management-status.merge-status');
        Route::prefix('{id}')->group(function () {
            Route::get('edit', 'StoreSocialContentStatusController@edit')->name('content-management-status.edit');
            Route::get('delete', 'StoreSocialContentStatusController@delete')->name('content-management-status.delete');
        });
    });



    //
    // Route::post('/delete-document', 'SiteDevelopmentController@deleteDocument')->name("site-development.delete-documents");
    // Route::post('/send-document', 'SiteDevelopmentController@sendDocument')->name("site-development.send-documents");
    // Route::prefix('{id}')->group(function () {
    //     Route::get('list-documents', 'SiteDevelopmentController@listDocuments')->name("site-development.list-documents");
    //     Route::prefix('remarks')->group(function () {
    //         Route::get('/', 'SiteDevelopmentController@remarks')->name("site-development.remarks");
    //         Route::post('/', 'SiteDevelopmentController@saveRemarks')->name("site-development.saveRemarks");
    //     });
    // });

    //  Route::resource('task','TaskController');

    // Instruction
    Route::get('instruction/quick-instruction', 'InstructionController@quickInstruction');
    Route::post('instruction/store-instruction-end-time', 'InstructionController@storeInstructionEndTime');
    Route::get('instruction/list', 'InstructionController@list')->name('instruction.list');
    Route::resource('instruction', 'InstructionController');
    Route::post('instruction/complete', 'InstructionController@complete')->name('instruction.complete');
    Route::post('instruction/pending', 'InstructionController@pending')->name('instruction.pending');
    Route::post('instruction/verify', 'InstructionController@verify')->name('instruction.verify');
    Route::post('instruction/skipped-count', 'InstructionController@skippedCount')->name('instruction.skipped.count');
    Route::post('instruction/verifySelected', 'InstructionController@verifySelected')->name('instruction.verify.selected');
    Route::get('instruction/complete/alert', 'InstructionController@completeAlert')->name('instruction.complete.alert');
    Route::post('instruction/category/store', 'InstructionController@categoryStore')->name('instruction.category.store');


    Route::get('order/{id}/send/confirmationEmail', 'OrderController@sendConfirmation')->name('order.send.confirmation.email');
    Route::post('order/{id}/refund/answer', 'OrderController@refundAnswer')->name('order.refund.answer');
    Route::post('order/send/Delivery', 'OrderController@sendDelivery')->name('order.send.delivery');
    Route::post('order/deleteBulkOrders', 'OrderController@deleteBulkOrders')->name('order.deleteBulkOrders');
    Route::post('order/{id}/send/suggestion', 'OrderController@sendSuggestion')->name('order.send.suggestion');
    Route::post('order/{id}/changestatus', 'OrderController@updateStatus');
    Route::post('order/{id}/sendRefund', 'OrderController@sendRefund');
    Route::post('order/{id}/uploadForApproval', 'OrderController@uploadForApproval')->name('order.upload.approval');
    Route::post('order/{id}/deliveryApprove', 'OrderController@deliveryApprove')->name('order.delivery.approve');
    Route::get('order/{id}/printAdvanceReceipt', 'OrderController@printAdvanceReceipt')->name('order.advance.receipt.print');
    Route::get('order/{id}/emailAdvanceReceipt', 'OrderController@emailAdvanceReceipt')->name('order.advance.receipt.email');
    Route::get('order/{id}/generateInvoice', 'OrderController@generateInvoice')->name('order.generate.invoice');
    Route::get('order/{id}/send-invoice', 'OrderController@sendInvoice')->name('order.send.invoice');
    Route::get('order/{id}/send-order-email', 'OrderController@sendOrderEmail')->name('order.send.email');
    // Route::get('order/{id}/view-products', 'OrderController@viewproducts')->name('order.view.products');
    Route::get('order/{id}/preview-invoice', 'OrderController@previewInvoice')->name('order.perview.invoice');
    Route::post('order/{id}/createProductOnMagento', 'OrderController@createProductOnMagento')->name('order.create.magento.product');
    Route::get('order/{id}/download/PackageSlip', 'OrderController@downloadPackageSlip')->name('order.download.package-slip');
    Route::get('order/track/packageSlip', 'OrderController@trackPackageSlip')->name('order.track.package-slip');
    Route::delete('order/permanentDelete/{order}', 'OrderController@permanentDelete')->name('order.permanentDelete');
    Route::get('order/products/list', 'OrderController@products')->name('order.products');
    Route::get('order/missed-calls', 'OrderController@missedCalls')->name('order.missed-calls');
    Route::get('order/calls/history', 'OrderController@callsHistory')->name('order.calls-history');
    Route::post('order/update/customer', 'OrderController@updateCustomer')->name('order.update.customer');
    Route::post('order/generate/awb/number', 'OrderController@generateAWB')->name('order.generate.awb');
    Route::post('order/update/customer', 'OrderController@updateCustomer')->name('order.update.customer');
    Route::post('order/generate/awb/dhl', 'OrderController@generateAWBDHL')->name('order.generate.awbdhl');
    Route::get('order/generate/awb/rate-request', 'OrderController@generateRateRequet')->name('order.generate.rate-request');
    Route::post('order/generate/awb/rate-request', 'OrderController@generateRateRequet')->name('order.generate.rate-request');
    Route::get('orders/download', 'OrderController@downloadOrderInPdf');
    Route::get('order/email/download/{order_id?}/{email_id?}', 'OrderController@downloadOrderMailPdf')->name('order.generate.order-mail.pdf');
    Route::post('order/{id}/change-status-template', 'OrderController@statusChangeTemplate');
    Route::get('order/change-status', 'OrderController@statusChange');


    Route::get('order/invoices', 'OrderController@viewAllInvoices');
    Route::post('order/create-product', 'OrderController@createProduct')->name('order.create.product');

    

    Route::get('order/{id}/edit-invoice', 'OrderController@editInvoice')->name('order.edit.invoice');
    Route::post('order/edit-invoice', 'OrderController@submitEdit')->name('order.submitEdit.invoice');
    //TODO::invoice wthout order
    Route::get('invoice/without-order', 'OrderController@createInvoiceWithoutOrderNumber')->name('invoice.without.order');
    Route::get('order/order-search', 'OrderController@searchOrderForInvoice')->name('order.search.invoice');
    Route::get('customers/customer-search', 'OrderController@getCustomers')->name('customer.search');
    Route::get('customers/product-search', 'OrderController@getSearchedProducts')->name('product.search');
    Route::get('order/{id}/add-invoice', 'OrderController@addInvoice')->name('order.add.invoice');
    Route::post('order/submit-invoice', 'OrderController@submitInvoice')->name('order.submit.invoice');

    //view
    Route::get('order/view-invoice/{id}', 'OrderController@viewInvoice')->name('order.view.invoice');
    Route::get('order/invoices/{id}/get-details', 'OrderController@getInvoiceDetails')->name('order.view.invoice.get.details');
    Route::post('order/invoices/{id}/update-details', 'OrderController@updateDetails')->name('order.view.invoice.update.details');

    Route::post('order/invoices/add-product', 'OrderController@addProduct')->name('order.view.invoice.add.product');
    Route::post('order/invoices/search-product', 'OrderController@searchProduct')->name('order.search.product');
   //TODO web - added by jammer
    Route::get('order/download-invoice/{id}', 'OrderController@downloadInvoice')->name('order.download.invoice');
    Route::post('order/update-customer-address', 'OrderController@updateCustomerInvoiceAddress')->name('order.update.customer.address');
    Route::get('order/{id}/mail-invoice', 'OrderController@mailInvoice')->name('order.mail.invoice');
    Route::get('order/update-delivery-date', 'OrderController@updateDelDate')->name('order.updateDelDate');
    Route::get('order/view-est-delivery-date-history', 'OrderController@viewEstDelDateHistory')->name('order.viewEstDelDateHistory');
    Route::post('order/addNewReply', 'OrderController@addNewReply')->name('order.addNewReply');
    Route::post('order/get-customer-address', 'OrderController@getCustomerAddress')->name('order.customer.address');
    Route::resource('order', 'OrderController');

    Route::post('order/status/store', 'OrderReportController@statusStore')->name('status.store');
    Route::post('order/report/store', 'OrderReportController@store')->name('status.report.store');

    Route::get('order-refund-status-message', 'OrderReportController@orderRefundStatusMessage')->name('order.status.messages');

    //emails
    Route::get('email/replyMail/{id}', 'EmailController@replyMail');
    Route::post('email/replyMail', 'EmailController@submitReply')->name('email.submit-reply');

    Route::get('email/forwardMail/{id}', 'EmailController@forwardMail');
    Route::post('email/forwardMail', 'EmailController@submitForward')->name('email.submit-forward');

    Route::post('email/resendMail/{id}', 'EmailController@resendMail');
    Route::put('email/{id}/mark-as-read', 'EmailController@markAsRead');
    Route::post('email/{id}/excel-import', 'EmailController@excelImporter');
    Route::post('email/{id}/get-file-status', 'EmailController@getFileStatus');
    Route::resource('email', 'EmailController');
    Route::post('email/platform-update', 'EmailController@platformUpdate');

    Route::post('email/category', 'EmailController@category');
    Route::post('email/status', 'EmailController@status');
    Route::post('email/update_email', 'EmailController@updateEmail');

    Route::post('bluckAction','EmailController@bluckAction')->name('bluckAction');
    Route::any('syncroniseEmail','EmailController@syncroniseEmail')->name('syncroniseEmail');
    Route::post('changeStatus','EmailController@changeStatus')->name('changeStatus');


    Route::get('email-remark', 'EmailController@getRemark')->name('email.getremark');
    Route::post('email-remark', 'EmailController@addRemark')->name('email.addRemark');


    // Zoom Meetings
    //Route::get( 'twilio/missedCallStatus', 'TwilioController@missedCallStatus' );
    Route::post('meeting/create', 'Meeting\ZoomMeetingController@createMeeting');
    Route::get('meeting/allmeetings', 'Meeting\ZoomMeetingController@getMeetings');
    Route::get('meetings/show-data', 'Meeting\ZoomMeetingController@showData')->name('meetings.show.data');
    Route::get('meetings/show', 'Meeting\ZoomMeetingController@show')->name('meetings.show');


    Route::post('task/reminder', 'TaskModuleController@updateTaskReminder');

    Route::get('task/time/history', 'TaskModuleController@getTimeHistory')->name('task.time.history');
    Route::get('task/categories', 'TaskModuleController@getTaskCategories')->name('task.categories');
    Route::get('task/list', 'TaskModuleController@list')->name('task.list');
    Route::get('task/get-discussion-subjects', 'TaskModuleController@getDiscussionSubjects')->name('task.discussion-subjects');
    // Route::get('task/create-task', 'TaskModuleController@createTask')->name('task.create-task');
    Route::post('task/flag', 'TaskModuleController@flag')->name('task.flag');
    Route::post('remark/flag', 'TaskModuleController@remarkFlag')->name('remark.flag');
    Route::post('task/{id}/plan', 'TaskModuleController@plan')->name('task.plan');
    Route::post('task/assign/messages', 'TaskModuleController@assignMessages')->name('task.assign.messages');
    Route::post('task/loadView', 'TaskModuleController@loadView')->name('task.load.view');
    Route::post('task/bulk-complete', 'TaskModuleController@completeBulkTasks')->name('task.bulk.complete');
    Route::post('task/bulk-delete', 'TaskModuleController@deleteBulkTasks')->name('task.bulk.delete');
    Route::post('task/send-document', 'TaskModuleController@sendDocument')->name('task.send-document');
    Route::post('task/message/reminder', 'TaskModuleController@messageReminder')->name('task.message.reminder');
    Route::post('task/{id}/convertTask', 'TaskModuleController@convertTask')->name('task.convert.appointment');
    Route::post('task/{id}/updateSubject', 'TaskModuleController@updateSubject')->name('task.update.subject');
    Route::post('task/{id}/addNote', 'TaskModuleController@addNote')->name('task.add.note');
    Route::post('task/{id}/addSubnote', 'TaskModuleController@addSubnote')->name('task.add.subnote');
    Route::post('task/{id}/updateCategory', 'TaskModuleController@updateCategory')->name('task.update.category');
    Route::post('task/list-by-user-id', 'TaskModuleController@taskListByUserId')->name('task.list.by.user.id');
    Route::post('task/set-priority', 'TaskModuleController@setTaskPriority')->name('task.set.priority');
    Route::get('/task/assign/master-user', 'TaskModuleController@assignMasterUser')->name('task.asign.master-user');
    Route::post('/task/upload-documents', 'TaskModuleController@uploadDocuments')->name("task.upload-documents");
    Route::post('/task/save-documents', 'TaskModuleController@saveDocuments')->name("task.save-documents");
    Route::get('/task/preview-img/{id}', 'TaskModuleController@previewTaskImage')->name('task.preview-img');
    Route::get('/task/complete/{taskid}', 'TaskModuleController@complete')->name('task.complete');
    Route::get('/task/start/{taskid}', 'TaskModuleController@start')->name('task.start');
    Route::get('/statutory-task/complete/{taskid}', 'TaskModuleController@statutoryComplete')->name('task.statutory.complete');
    Route::post('/task/addremark', 'TaskModuleController@addRemark')->name('task.addRemark');
    Route::get('tasks/getremark', 'TaskModuleController@getremark')->name('task.getremark');
    Route::get('tasks/gettaskremark', 'TaskModuleController@getTaskRemark')->name('task.gettaskremark');
    Route::post('task/{id}/makePrivate', 'TaskModuleController@makePrivate');
    Route::post('task/{id}/isWatched', 'TaskModuleController@isWatched');
    Route::post('task-remark/{id}/delete', 'TaskModuleController@archiveTaskRemark')->name('task.archive.remark');
    Route::post('tasks/deleteTask', 'TaskModuleController@deleteTask');
    Route::post('tasks/{id}/delete', 'TaskModuleController@archiveTask')->name('task.archive');
    //  Route::get('task/completeStatutory/{satutory_task}','TaskModuleController@completeStatutory');
    Route::post('task/deleteStatutoryTask', 'TaskModuleController@deleteStatutoryTask');

    Route::get('task/export', 'TaskModuleController@exportTask')->name('task.export');
    Route::post('task/addRemarkStatutory', 'TaskModuleController@addRemark')->name('task.addRemarkStatutory');

    Route::get('task/{id}', 'TaskModuleController@show')->name('task.module.show');

    Route::resource('task', 'TaskModuleController');

    //START - Purpose : add Route for Remind, Revise Message - DEVTASK-4354
    Route::post('task/time/history/approve/sendMessage', 'TaskModuleController@sendReviseMessage')->name('task.time.history.approve.sendMessage');
    Route::post('task/time/history/approve/sendRemindMessage', 'TaskModuleController@sendRemindMessage')->name('task.time.history.approve.sendRemindMessage');
    //END - DEVTASK-4354
    

    Route::post('task/update/approximate', 'TaskModuleController@updateApproximate')->name('task.update.approximate');
    Route::post('task/update/priority-no', 'TaskModuleController@updatePriorityNo')->name('task.update.updatePriorityNo');
    Route::post('task/time/history/approve', 'TaskModuleController@approveTimeHistory')->name('task.time.history.approve');


    Route::post('task/update/due_date', 'TaskModuleController@updateTaskDueDate')->name('task.update.due_date');
    Route::get('task/time/tracked/history', 'TaskModuleController@getTrackedHistory')->name('task.time.tracked.history');
    Route::post('task/create/hubstaff_task', 'TaskModuleController@createHubstaffManualTask')->name('task.create.hubstaff_task');
    Route::post('task/update/cost', 'TaskModuleController@updateCost')->name('task.update.cost');
    Route::get('task/update/milestone', 'TaskModuleController@saveMilestone')->name('task.update.milestone');
    Route::get('task/get/details', 'TaskModuleController@getDetails')->name('task.json.details');
    Route::post('task/get/save-notes', 'TaskModuleController@saveNotes')->name('task.json.saveNotes');
    Route::post('task_category/{id}/approve', 'TaskCategoryController@approve');
    Route::post('task_category/change-status', 'TaskCategoryController@changeStatus');
    Route::resource('task_category', 'TaskCategoryController');
    Route::post('task/addWhatsAppGroup', 'TaskModuleController@addWhatsAppGroup')->name('task.add.whatsapp.group');
    Route::post('task/addGroupParticipant', 'TaskModuleController@addGroupParticipant')->name('task.add.whatsapp.participant');
    Route::post('task/create-task-from-shortcut', 'TaskModuleController@createTaskFromSortcut')->name('task.create.task.shortcut');


    // Route::get('/', 'TaskModuleController@index')->name('home');

    Route::resource('learning', 'LearningModuleController');
    Route::get('learning/status/history','LearningModuleController@getStatusHistory')->name('learning/status/history');


    Route::post('learning/due_date-change', 'LearningModuleController@saveDueDateUpdate')->name('learning-due-change');

    Route::get('learning/duedate/history','LearningModuleController@getDueDateHistory')->name('learning/duedate/history');
    
    Route::resource('learning_category','LearningCategoryController');
    Route::post('learning_category/submodule', 'LearningCategoryController@getSubModule');
    Route::post('learning/create-learning-from-shortcut', 'LearningModuleController@createLearningFromSortcut');
    Route::post('learning-module/update', 'LearningModuleController@learningModuleUpdate')->name('learning-module.update');
    Route::post('/learning/save-documents', 'LearningModuleController@saveDocuments')->name("learning.save-documents");
    Route::get('learning/{id}', 'LearningModuleController@show')->name('learning.module.show');



    Route::get('/', 'MasterControlController@index')->name('home');
    Route::get('/master-dev-task', 'MasterDevTaskController@index')->name('master.dev.task');


    Route::get('project-file-manager/list', 'ProjectFileManagerController@listTree')->name('project-file-manager.list');
    Route::get('project-file-manager', 'ProjectFileManagerController@index')->name('project-file-manager.index');
    Route::post('project-file-manager/update', 'ProjectFileManagerController@update')->name('project-file-manager.update');

    // Daily Planner
    Route::post('dailyplanner/complete', 'DailyPlannerController@complete')->name('dailyplanner.complete');
    Route::post('dailyplanner/reschedule', 'DailyPlannerController@reschedule')->name('dailyplanner.reschedule');
    Route::post('dailyplanner/history', 'DailyPlannerController@history')->name('dailyplanner.history');
    Route::post('dailyplanner/send/schedule', 'DailyPlannerController@sendSchedule')->name('dailyplanner.send.vendor.schedule');
    Route::post('dailyplanner/resend-notification', 'DailyPlannerController@resendNotification')->name('dailyplanner.resend.notification');
    Route::resource('dailyplanner', 'DailyPlannerController');

    Route::resource('refund', 'RefundController');



    // Contacts
    Route::resource('contact', 'ContactController');

    Route::get('/notifications', 'NotificaitonContoller@index')->name('notifications');
    Route::get('/notificaitonsJson', 'NotificaitonContoller@json')->name('notificationJson');
    Route::get('/salesNotificaitonsJson', 'NotificaitonContoller@salesJson')->name('salesNotificationJson');
    Route::post('/notificationMarkRead/{notificaion}', 'NotificaitonContoller@markRead')->name('notificationMarkRead');
    Route::get('/deQueueNotfication', 'NotificationQueueController@deQueueNotficationNew');

    Route::post('/productsupervisor/approve/{product}', 'ProductSupervisorController@approve')->name('productsupervisor.approve');
    Route::post('/productsupervisor/reject/{product}', 'ProductSupervisorController@reject')->name('productsupervisor.reject');
    Route::post('/productlister/isUploaded/{product}', 'ProductListerController@isUploaded')->name('productlister.isuploaded');
    Route::post('/productapprover/isFinal/{product}', 'ProductApproverController@isFinal')->name('productapprover.isfinal');

    Route::get('/productinventory/in/stock', 'ProductInventoryController@instock')->name('productinventory.instock');
    Route::post('/productinventory/in/stock/update-field', 'ProductInventoryController@updateField')->name('productinventory.instock.update-field');
    Route::get('/productinventory/in/delivered', 'ProductInventoryController@inDelivered')->name('productinventory.indelivered');
    Route::get('/productinventory/in/stock/instruction-create', 'ProductInventoryController@instructionCreate')->name('productinventory.instruction.create');
    Route::post('/productinventory/in/stock/instruction', 'ProductInventoryController@instruction')->name('productinventory.instruction');
    Route::get('/productinventory/in/stock/location-history', 'ProductInventoryController@locationHistory')->name('productinventory.location.history');
    Route::post('/productinventory/in/stock/dispatch-store', 'ProductInventoryController@dispatchStore')->name('productinventory.dispatch.store');
    Route::get('/productinventory/in/stock/dispatch', 'ProductInventoryController@dispatchCreate')->name('productinventory.dispatch.create');
    Route::post('/productinventory/stock/{product}', 'ProductInventoryController@stock')->name('productinventory.stock');
    Route::get('productinventory/in/stock/location/change', 'ProductInventoryController@locationChange')->name('productinventory.location.change');


    Route::prefix('google-search-image')->group(function () {
        Route::get('/', 'GoogleSearchImageController@index')->name('google.search.image');
        Route::post('/crop', 'GoogleSearchImageController@crop')->name('google.search.crop');
        Route::post('/crop-search', 'GoogleSearchImageController@searchImageOnGoogle')->name('google.search.crop.post');
        Route::post('details', 'GoogleSearchImageController@details')->name('google.search.details');
        Route::post('queue', 'GoogleSearchImageController@queue')->name('google.search.queue');
        Route::post('/multiple-products', 'GoogleSearchImageController@getImageForMultipleProduct')->name('google.product.queue');
        Route::post('/image-crop-sequence', 'GoogleSearchImageController@cropImageSequence')->name('google.crop.sequence');
        Route::post('/update-product-status', 'GoogleSearchImageController@updateProductStatus')->name('google.product.status');
        Route::post('product-by-image', 'GoogleSearchImageController@getProductFromImage')->name('google.product.image');
    });
    Route::get('/product-search-image', 'GoogleSearchImageController@searchImageList')->name('google.search.product.image');

    Route::prefix('search-image')->group(function () {
        Route::get('/', 'GoogleSearchImageController@product')->name('google.search.product');
        Route::post('/', 'GoogleSearchImageController@product')->name('google.search.product-save');
    });

    Route::prefix('multiple-search-image')->group(function () {
        Route::get('/', 'GoogleSearchImageController@nultipeImageProduct')->name('google.search.multiple');
        Route::post('/save-images', 'GoogleSearchImageController@multipleImageStore')->name('multiple.google.search.product-save');
        Route::post('/single-save-images', 'GoogleSearchImageController@getProductFromText')->name('multiple.google.product-save');
    });

    Route::prefix('approve-search-image')->group(function () {
        Route::get('/', 'GoogleSearchImageController@approveProduct')->name('google.approve.product');
        Route::post('/approve-images-product', 'GoogleSearchImageController@approveTextGoogleImagesToProduct')->name('approve.google.search.images.product');
        Route::post('/reject', 'GoogleSearchImageController@rejectProducts')->name('reject.google.search.text.product');
    });


    Route::get('category', 'CategoryController@manageCategory')->name('category');
    Route::get('category-11', 'CategoryController@manageCategory11')->name('category-11');
    Route::post('add-category', 'CategoryController@addCategory')->name('add.category');
    Route::post('category/{category}/edit', 'CategoryController@edit')->name('category.edit');
    Route::post('category/remove', 'CategoryController@remove')->name('category.remove');

    Route::get('productSearch/', 'SaleController@searchProduct');
    Route::post('productSearch/', 'SaleController@searchProduct');

    Route::get('user-search/', 'UserController@searchUser');
    Route::post('user-search/', 'UserController@searchUser');

    Route::get('activity/', 'ActivityConroller@showActivity')->name('activity');
    Route::post('activity/modal', 'ActivityConroller@recentActivities');
    Route::get('graph/', 'ActivityConroller@showGraph')->name('graph');
    Route::get('graph/user', 'ActivityConroller@showUserGraph')->name('graph_user');

    Route::get('search/', 'SearchController@search')->name('search');
    Route::get('pending/{roletype}', 'SearchController@getPendingProducts')->name('pending');

    Route::get('loadEnvManager/', 'EnvController@loadEnvManager')->name('load_env_manager');

    //  Route::post('productAttachToSale/{sale}/{product_id}','SaleController@attachProduct');
    //  Route::get('productSelectionGrid/{sale}','SaleController@selectionGrid')->name('productSelectionGrid');

    //Attach Products
    Route::get('attachProducts/{model_type}/{model_id}/{type?}/{customer_id?}', 'ProductController@attachProducts')->name('attachProducts');
    Route::post('attachProductToModel/{model_type}/{model_id}/{product_id}', 'ProductController@attachProductToModel')->name('attachProductToModel');
    Route::post('deleteOrderProduct/{order_product}', 'OrderController@deleteOrderProduct')->name('deleteOrderProduct');
    Route::get('attachImages/{model_type}/{model_id?}/{status?}/{assigned_user?}', 'ProductController@attachImages')->name('attachImages');
    Route::post('selected_customer/sendMessage', 'ProductController@sendMessageSelectedCustomer')->name('whatsapp.send_selected_customer');

    // landing page
    Route::prefix('landing-page')->group(function () {
        Route::get('/', 'LandingPageController@index')->name('landing-page.index');
        Route::post('/save', 'LandingPageController@save')->name('landing-page.save');
        Route::get('/records', 'LandingPageController@records')->name('landing-page.records');
        Route::post('/store', 'LandingPageController@store')->name('landing-page.store');
        Route::post('/update-time', 'LandingPageController@updateTime')->name('landing-page.updateTime');
        Route::get('/image/{id}/{productId}/delete', 'LandingPageController@deleteImage')->name('landing-page.deleteImage');
        Route::post('create_status', 'LandingPageController@createStatus')->name('landing-page-create-status.store');
        Route::get('/approve-status', 'LandingPageController@approveStatus')->name('landing-page.approveStatus');
        Route::prefix('{id}')->group(function () {
            Route::get('edit', 'LandingPageController@edit')->name('landing-page.edit');
            Route::get('delete', 'LandingPageController@delete')->name('landing-page.delete');
            Route::get('push-to-shopify', 'LandingPageController@pushToShopify')->name('landing-page.push-to-shopify');
            Route::get('change-store', 'LandingPageController@changeStore')->name('landing-page.change.store');
            Route::get('push-to-magento', 'LandingPageController@pushToMagentoPro')->name('landing-page.push-to-magento');
            Route::get('push-to-magento-status', 'LandingPageController@updateMagentoStock')->name('landing-page.push-to-magento-status');
        });
    });

    Route::prefix('newsletters')->group(function () {
        Route::get('/', 'NewsletterController@index')->name('newsletters.index');
        Route::post('/save', 'NewsletterController@save')->name('newsletters.save');
        Route::get('/records', 'NewsletterController@records')->name('newsletters.records');
        Route::post('/store', 'NewsletterController@store')->name('newsletters.store');
        Route::get('/image/{id}/{productId}/delete', 'NewsletterController@deleteImage')->name('newsletters.deleteImage');
        Route::prefix('{id}')->group(function () {
            Route::get('edit', 'NewsletterController@edit')->name('newsletters.edit');
            Route::get('delete', 'NewsletterController@delete')->name('newsletters.delete');
            Route::get('change-store', 'NewsletterController@changeStore')->name('newsletters.change.store');
            Route::get('preview', 'NewsletterController@preview')->name('newsletters.preview');
        });
    });

    Route::prefix('size')->group(function () {
        Route::get('/', 'SizeController@index')->name('size.index');
        Route::post('/save', 'SizeController@save')->name('size.save');
        Route::get('/records', 'SizeController@records')->name('size.records');
        Route::post('/store', 'SizeController@store')->name('size.store');
        Route::post('push-to-store', 'SizeController@pushToStore')->name('size.push.to.store');
        Route::prefix('{id}')->group(function () {
            Route::get('edit', 'SizeController@edit')->name('size.edit');
            Route::get('delete', 'SizeController@delete')->name('size.delete');
        });
    });

    Route::post('download', 'MessageController@downloadImages')->name('download.images');

    Route::get('quickSell', 'QuickSellController@index')->name('quicksell.index');
    Route::post('quickSell', 'QuickSellController@store')->name('quicksell.store');
    Route::post('quickSell/edit', 'QuickSellController@update')->name('quicksell.update');
    Route::post('quickSell/saveGroup', 'QuickSellController@saveGroup')->name('quicksell.save.group');
    Route::get('quickSell/pending', 'QuickSellController@pending')->name('quicksell.pending');
    Route::post('quickSell/activate', 'QuickSellController@activate')->name('quicksell.activate');
    Route::get('quickSell/search', 'QuickSellController@search')->name('quicksell.search');
    Route::post('quickSell/groupUpdate', 'QuickSellController@groupUpdate')->name('quicksell.group.update');
    Route::get('quickSell/quick-sell-group-list', 'QuickSellController@quickSellGroupProductsList');
    Route::post('quickSell/quicksell-product-delete', 'QuickSellController@quickSellGroupProductDelete');

    // Chat messages
    Route::get('chat-messages/{object}/{object_id}/loadMoreMessages', 'ChatMessagesController@loadMoreMessages');
    Route::post('chat-messages/{id}/set-reviewed', 'ChatMessagesController@setReviewed');
    Route::post('chat-messages/downloadChatMessages', 'ChatMessagesController@downloadChatMessages')->name('chat.downloadChatMessages');
    Route::get('chat-messages/dnd-list', 'ChatMessagesController@dndList')->name('chat.dndList');
    Route::get('chat-messages/dnd-list/records', 'ChatMessagesController@dndListRecords')->name('chat.dndList.records');
    Route::post('chat-messages/dnd-list/move-dnd', 'ChatMessagesController@moveDnd')->name('chat.dndList.moveDnd');
    // Customers
    Route::get('customer/exportCommunication/{id}', 'CustomerController@exportCommunication');
    Route::get('customer/test', 'CustomerController@customerstest');
    Route::post('customer/reminder', 'CustomerController@updateReminder');
    Route::post('supplier/reminder', 'SupplierController@updateReminder');
    Route::post('supplier/excel-import', 'SupplierController@excelImport');
    Route::post('vendors/reminder', 'VendorController@updateReminder');
    Route::post('customer/add-note/{id}', 'CustomerController@addNote');
    Route::post('supplier/add-note/{id}', 'SupplierController@addNote');
    Route::get('customers/{id}/post-show', 'CustomerController@postShow')->name('customer.post.show');
    Route::post('customers/{id}/post-show', 'CustomerController@postShow')->name('customer.post.show');
    Route::post('customers/{id}/sendAdvanceLink', 'CustomerController@sendAdvanceLink')->name('customer.send.advanceLink');
    Route::get('customers/{id}/loadMoreMessages', 'CustomerController@loadMoreMessages');
    Route::get('customer/search', 'CustomerController@search');
    Route::get('customers', 'CustomerController@index')->name('customer.index');
    Route::post('add-reply-category', 'CustomerController@addReplyCategory')->name('add.reply.category');
    Route::post('destroy-reply-category', 'CustomerController@destroyReplyCategory')->name('destroy.reply.category');
    Route::get('customers-load', 'CustomerController@load')->name('customer.load');
    Route::post('customer/{id}/initiateFollowup', 'CustomerController@initiateFollowup')->name('customer.initiate.followup');
    Route::post('customer/{id}/stopFollowup', 'CustomerController@stopFollowup')->name('customer.stop.followup');
    Route::get('customer/export', 'CustomerController@export')->name('customer.export');
    Route::post('customer/merge', 'CustomerController@merge')->name('customer.merge');
    Route::post('customer/import', 'CustomerController@import')->name('customer.import');
    Route::get('customer/create', 'CustomerController@create')->name('customer.create');
    Route::post('customer/block', 'CustomerController@block')->name('customer.block');
    Route::post('customer/flag', 'CustomerController@flag')->name('customer.flag');
    Route::post('customer/in-w-list', 'CustomerController@addInWhatsappList')->name('customer.in-w-list');
    Route::post('customer/prioritize', 'CustomerController@prioritize')->name('customer.priority');
    Route::post('customer/create', 'CustomerController@store')->name('customer.store');
    Route::get('customer/broadcast', 'CustomerController@broadcast')->name('customer.broadcast.list');
    Route::get('customer/broadcast-details', 'CustomerController@broadcastDetails')->name('customer.broadcast.details');
    Route::get('customer/broadcast-send-price', 'CustomerController@broadcastSendPrice')->name('customer.broadcast.run');
    Route::get('customer/contact-download/{id}', 'CustomerController@downloadContactDetailsPdf')->name('customer.download.contact-pdf');
    Route::get('customer/{id}', 'CustomerController@show')->name('customer.show');
    Route::get('customer/{id}/edit', 'CustomerController@edit')->name('customer.edit');
    Route::post('customer/{id}/edit', 'CustomerController@update')->name('customer.update');
    Route::post('customer/{id}/updateNumber', 'CustomerController@updateNumber')->name('customer.update.number');
    Route::post('customer/{id}/updateDND', 'CustomerController@updateDnd')->name('customer.update.dnd');
    Route::post('customer/{id}/updatePhone', 'CustomerController@updatePhone')->name('customer.update.phone');
    Route::delete('customer/{id}/destroy', 'CustomerController@destroy')->name('customer.destroy');
    Route::post('customer/send/message/all/{validate?}', 'WhatsAppController@sendToAll')->name('customer.whatsapp.send.all');
    Route::get('customer/stop/message/all', 'WhatsAppController@stopAll')->name('customer.whatsapp.stop.all');
    Route::get('customer/email/fetch', 'CustomerController@emailFetch')->name('customer.email.fetch');
    Route::get('customer/email/inbox', 'CustomerController@emailInbox')->name('customer.email.inbox');
    Route::post('customer/email/send', 'CustomerController@emailSend')->name('customer.email.send');
    Route::post('customer/send/suggestion', 'CustomerController@sendSuggestion')->name('customer.send.suggestion');
    Route::post('customer/send/instock', 'CustomerController@sendInstock')->name('customer.send.instock');
    Route::post('customer/issue/credit', 'CustomerController@issueCredit')->name('customer.issue.credit');
    Route::post('customer/attach/all', 'CustomerController@attachAll')->name('customer.attach.all');
    Route::post('customer/sendScraped/images', 'CustomerController@sendScraped')->name('customer.send.scraped');
    Route::post('customer/change-whatsapp-no', 'CustomerController@changeWhatsappNo')->name('customer.change.whatsapp');
    Route::post('customer/update-field', 'CustomerController@updateField')->name('customer.update.field');
    Route::post('customer/send-contact-details', 'CustomerController@sendContactDetails')->name('customer.send.contact');
    Route::post('customer/contact-download-donload', 'CustomerController@downloadContactDetails')->name('customer.download.contact');
    Route::post('customer/create-kyc', 'CustomerController@createKyc')->name('customer.create.kyc');

    Route::get('quickcustomer', 'CustomerController@quickcustomer')->name('quickcustomer');
    Route::get('quick-customer', 'QuickCustomerController@index')->name('quick.customer.index');
    Route::get('quick-customer/records', 'QuickCustomerController@records')->name('quick.customer.records');
    Route::post('quick-customer/add-whatsapp-list', 'QuickCustomerController@addInWhatsappList')->name('quick.customer.add-whatsapp-list');

    Route::get('broadcast', 'BroadcastMessageController@index')->name('broadcast.index');
    Route::get('broadcast/images', 'BroadcastMessageController@images')->name('broadcast.images');
    Route::post('broadcast/imagesUpload', 'BroadcastMessageController@imagesUpload')->name('broadcast.images.upload');
    Route::post('broadcast/imagesLink', 'BroadcastMessageController@imagesLink')->name('broadcast.images.link');
    Route::delete('broadcast/{id}/imagesDelete', 'BroadcastMessageController@imagesDelete')->name('broadcast.images.delete');
    Route::get('broadcast/calendar', 'BroadcastMessageController@calendar')->name('broadcast.calendar');
    Route::post('broadcast/restart', 'BroadcastMessageController@restart')->name('broadcast.restart');
    Route::post('broadcast/restart/{id}', 'BroadcastMessageController@restartGroup')->name('broadcast.restart.group');
    Route::post('broadcast/delete/{id}', 'BroadcastMessageController@deleteGroup')->name('broadcast.delete.group');
    Route::post('broadcast/stop/{id}', 'BroadcastMessageController@stopGroup')->name('broadcast.stop.group');
    Route::post('broadcast/{id}/doNotDisturb', 'BroadcastMessageController@doNotDisturb')->name('broadcast.donot.disturb');

    Route::get('purchases', 'PurchaseController@index')->name('purchase.index');
    Route::get('purchase/calendar', 'PurchaseController@calendar')->name('purchase.calendar');
    Route::post('purchase/{id}/updateDelivery', 'PurchaseController@updateDelivery');
    Route::post('purchase/{id}/assignBatch', 'PurchaseController@assignBatch')->name('purchase.assign.batch');
    Route::post('purchase/{id}/assignSplitBatch', 'PurchaseController@assignSplitBatch')->name('purchase.assign.split.batch');
    Route::post('purchase/export', 'PurchaseController@export')->name('purchase.export');
    Route::post('purchase/merge', 'PurchaseController@merge')->name('purchase.merge');
    Route::post('purchase/sendExport', 'PurchaseController@sendExport')->name('purchase.send.export');
    Route::get('purchase/{id}', 'PurchaseController@show')->name('purchase.show');
    Route::get('purchase/{id}/edit', 'PurchaseController@edit')->name('purchase.edit');
    Route::post('purchase/{id}/changestatus', 'PurchaseController@updateStatus');
    Route::post('purchase/{id}/changeProductStatus', 'PurchaseController@updateProductStatus');
    Route::post('purchase/{id}/saveBill', 'PurchaseController@saveBill');
    Route::post('purchase/{id}/downloadFile', 'PurchaseController@downloadFile')->name('purchase.file.download');
    Route::post('purchase/{id}/confirmProforma', 'PurchaseController@confirmProforma')->name('purchase.confirm.Proforma');
    Route::get('purchase/download/attachments', 'PurchaseController@downloadAttachments')->name('purchase.download.attachments');
    Route::delete('purchase/{id}/delete', 'PurchaseController@destroy')->name('purchase.destroy');
    Route::delete('purchase/{id}/permanentDelete', 'PurchaseController@permanentDelete')->name('purchase.permanentDelete');
    Route::get('purchaseGrid/{page?}', 'PurchaseController@purchaseGrid')->name('purchase.grid');
    Route::post('purchaseGrid', 'PurchaseController@store')->name('purchase.store');
    Route::post('purchase/product/replace', 'PurchaseController@productReplace')->name('purchase.product.replace');
    Route::post('purchase/product/create/replace', 'PurchaseController@productCreateReplace')->name('purchase.product.create.replace');
    Route::get('purchase/product/{id}', 'PurchaseController@productShow')->name('purchase.product.show');
    Route::post('purchase/product/{id}', 'PurchaseController@updatePercentage')->name('purchase.product.percentage');
    Route::post('purchase/product/{id}/remove', 'PurchaseController@productRemove')->name('purchase.product.remove');
    Route::get('purchase/email/inbox', 'PurchaseController@emailInbox')->name('purchase.email.inbox');
    Route::get('purchase/email/fetch', 'PurchaseController@emailFetch')->name('purchase.email.fetch');
    Route::post('purchase/email/send', 'PurchaseController@emailSend')->name('purchase.email.send');
    Route::post('purchase/email/resend', 'PurchaseController@emailResend')->name('purchase.email.resend');
    Route::post('purchase/email/reply', 'PurchaseController@emailReply')->name('purchase.email.reply');
    Route::get('pc/test', 'PictureColorsController@index');
    Route::post('purchase/email/forward', 'PurchaseController@emailForward')->name('purchase.email.forward');
    Route::get('download/crop-rejected/{id}/{type}', 'ProductCropperController@downloadImagesForProducts');

    Route::post('purchase/sendmsgsupplier', 'PurchaseController@sendmsgsupplier')->name('purchase.sendmsgsupplier');
    Route::get('get-supplier-msg', 'PurchaseController@getMsgSupplier')->name('get.msg.supplier');
    Route::post('purchase/send/emailBulk', 'PurchaseController@sendEmailBulk')->name('purchase.email.send.bulk');
    Route::resource('purchase-status', 'PurchaseStatusController');

    Route::get('download/crop-rejected/{id}/{type}', 'ProductCropperController@downloadImagesForProducts');

    // Master Plan
    Route::get('mastercontrol/clearAlert', 'MasterControlController@clearAlert')->name('mastercontrol.clear.alert');
    Route::resource('mastercontrol', 'MasterControlController');

    Route::get('purchase-product/getexcel', 'PurchaseProductController@getexcel')->name('purchase-product.getexcel');//Purpose : Set route for Excel - DEVTASK-4236
    Route::get('purchase-product/getallproducts', 'PurchaseProductController@getallproducts')->name('purchase-product.getallproducts');//Purpose : Set route for Excel - DEVTASK-4236
    Route::post('purchase-product/send_Products_Data', 'PurchaseProductController@send_Products_Data')->name('purchase-product.send_Products_Data');//Purpose : Set route for Excel - DEVTASK-4236
    Route::get('purchase-product/download_excel_file', 'PurchaseProductController@download_excel_file')->name('purchase-product.download_excel_file');//Purpose : Set route for Excel - DEVTASK-4236
    Route::post('purchase-product/set_template', 'PurchaseProductController@set_template')->name('purchase-product.set_template');//Purpose : Set route for Template - DEVTASK-4236
    Route::get('purchase-product/get_template', 'PurchaseProductController@get_template')->name('purchase-product.get_template');//Purpose : Set route for Template - DEVTASK-4236
    Route::post('purchase-product/edit_excel_file', 'PurchaseProductController@edit_excel_file')->name('purchase-product.edit_excel_file');//Purpose : Set route for Excel - DEVTASK-4236
    Route::get('purchase-product/openfile/{excel_id?}/{version?}', 'PurchaseProductController@open_excel_file')->name('purchase-product.openfile');
    Route::post('purchase-product/update_excel_sheet', 'PurchaseProductController@update_excel_sheet')->name('purchase-product.update_excel_sheet');
    Route::get('purchase-product/get_excel_data_supplier_wise', 'PurchaseProductController@get_excel_data_supplier_wise')->name('purchase-product.get_excel_data_supplier_wise');
    Route::post('purchase-product/send_excel_file', 'PurchaseProductController@send_excel_file')->name('purchase-product.send_excel_file');

    Route::get('purchase-product/not_mapping_product_supplier_list', 'PurchaseProductController@not_mapping_product_supplier_list')->name('not_mapping_product_supplier_list');//Purpose : Get not mapping supplier - DEVTASK-19941
    
    Route::post('purchase-product/change-status/{id}', 'PurchaseProductController@changeStatus');
    Route::post('purchase-product/submit-status', 'PurchaseProductController@createStatus');
    Route::get('purchase-product/send-products/{type}/{supplier_id}', 'PurchaseProductController@sendProducts');
    Route::get('purchase-product/get-products/{type}/{supplier_id}', 'PurchaseProductController@getProducts');
    Route::get('purchase-product/get-suppliers', 'PurchaseProductController@getSuppliers');
    Route::post('purchase-product/saveDefaultSupplier', 'PurchaseProductController@saveDefaultSupplier');
    Route::post('purchase-product/saveFixedPrice', 'PurchaseProductController@saveFixedPrice');
    Route::post('purchase-product/saveDiscount', 'PurchaseProductController@saveDiscount');
    Route::get('purchase-product/supplier-details/{order_id}', 'PurchaseProductController@getSupplierDetails');
    Route::get('purchase-product/customer-details/{type}/{order_id}', 'PurchaseProductController@getCustomerDetails');
    Route::resource('purchase-product', 'PurchaseProductController');


    Route::post('purchase-product/insert_suppliers_product', 'PurchaseProductController@insert_suppliers_product')->name('purchase-product.insert_suppliers_product');

    Route::get('purchaseproductorders/list', 'PurchaseProductController@purchaseproductorders')->name('purchaseproductorders.list');//Purpose : Add Route for Purchase Product Order - DEVTASK-4236
    Route::post('purchaseproductorders/update', 'PurchaseProductController@purchaseproductorders_update')->name('purchaseproductorders.update');//Purpose : Add Route for Purchase Product Order - DEVTASK-4236
    Route::get('purchaseproductorders/logs', 'PurchaseProductController@purchaseproductorders_logs')->name('purchaseproductorders.logs');//Purpose : Add Route for Purchase Product Order - DEVTASK-4236
    Route::get('purchaseproductorders/orderdata', 'PurchaseProductController@purchaseproductorders_orderdata')->name('purchaseproductorders.orderdata');//Purpose : Add Route for Purchase Product Order - DEVTASK-4236
    Route::post('purchaseproductorders/saveuploads', 'PurchaseProductController@purchaseproductorders_saveuploads')->name('purchaseproductorders.saveuploads');//Purpose : Add Route for Purchase Product Order - DEVTASK-4236

    // Cash Vouchers
    Route::get('/voucher/payment/request', 'VoucherController@paymentRequest')->name("voucher.payment.request");
    Route::post('/voucher/payment/request', 'VoucherController@createPaymentRequest')->name('voucher.payment.request-submit');
    Route::get('/voucher/payment/{id}', 'VoucherController@viewPaymentModal')->name("voucher.payment");
    Route::post('/voucher/payment/{id}', 'VoucherController@submitPayment')->name("voucher.payment.submit");
    Route::post('voucher/{id}/approve', 'VoucherController@approve')->name('voucher.approve');
    Route::post('voucher/store/category', 'VoucherController@storeCategory')->name('voucher.store.category');
    Route::post('voucher/{id}/reject', 'VoucherController@reject')->name('voucher.reject');
    Route::post('voucher/{id}/resubmit', 'VoucherController@resubmit')->name('voucher.resubmit');
    Route::get('/voucher/manual-payment', 'VoucherController@viewManualPaymentModal')->name("voucher.payment.manual-payment");
    Route::post('/voucher/manual-payment', 'VoucherController@manualPaymentSubmit')->name("voucher.payment.manual-payment-submit");
    Route::post('/voucher/paid-selected-payment', 'VoucherController@paidSelected')->name("voucher.payment.paid-selected");
    Route::get('/voucher/paid-selected-payment-list', 'VoucherController@paidSelectedPaymentList')->name("voucher.payment.paid-selected-payment-list");
    Route::post('/voucher/pay-multiple', 'VoucherController@payMultiple')->name("voucher.payment.pay-multiple");

    Route::resource('voucher', 'VoucherController');
    Route::post('/upload-documents', 'VoucherController@uploadDocuments')->name("voucher.upload-documents");
    Route::post('/voucher/save-documents', 'VoucherController@saveDocuments')->name("voucher.save-documents");
    Route::get('/voucher/{id}/list-documents', 'VoucherController@listDocuments')->name("voucher.list-documents");
    Route::post('/voucher/delete-document', 'VoucherController@deleteDocument')->name("voucher.delete-documents");

    // Budget
    Route::resource('budget', 'BudgetController');
    Route::post('budget/category/store', 'BudgetController@categoryStore')->name('budget.category.store');
    Route::post('budget/subcategory/store', 'BudgetController@subCategoryStore')->name('budget.subcategory.store');

    //Comments
    Route::post('doComment', 'CommentController@store')->name('doComment');
    Route::post('deleteComment/{comment}', 'CommentController@destroy')->name('deleteComment');
    Route::get('message/updatestatus', 'MessageController@updatestatus')->name('message.updatestatus');
    Route::get('message/loadmore', 'MessageController@loadmore')->name('message.loadmore');

    //Push Notifications new
    Route::get('/new-notifications', 'PushNotificationController@index')->name('pushNotification.index');
    Route::get('/pushNotifications', 'PushNotificationController@getJson')->name('pushNotifications');
    Route::post('/pushNotificationMarkRead/{push_notification}', 'PushNotificationController@markRead')->name('pushNotificationMarkRead');
    Route::post('/pushNotificationMarkReadReminder/{push_notification}', 'PushNotificationController@markReadReminder')->name('pushNotificationMarkReadReminder');
    Route::post('/pushNotification/status/{push_notification}', 'PushNotificationController@changeStatus')->name('pushNotificationStatus');

    Route::post('dailyActivity/store', 'DailyActivityController@store')->name('dailyActivity.store');
    Route::post('dailyActivity/quickStore', 'DailyActivityController@quickStore')->name('dailyActivity.quick.store');
    Route::get('dailyActivity/complete/{id}', 'DailyActivityController@complete');
    Route::get('dailyActivity/start/{id}', 'DailyActivityController@start');
    Route::get('dailyActivity/get', 'DailyActivityController@get')->name('dailyActivity.get');

    // Complete the task
    // Route::get('/task/count/{taskid}', 'TaskModuleController@taskCount')->name('task.count');
    Route::get('delete/task/note', 'TaskModuleController@deleteTaskNote')->name('delete/task/note');
    Route::get('hide/task/remark', 'TaskModuleController@hideTaskRemark')->name('hide/task/remark');

    // Social Media Image Module
    Route::get('lifestyle/images/grid', 'ImageController@index')->name('image.grid');
    Route::get('lifestyle/images/grid-new', 'ImageController@indexNew')->name('image.grid.new');
    Route::post('images/grid', 'ImageController@store')->name('image.grid.store');
    Route::post('images/grid/attachImage', 'ImageController@attachImage')->name('image.grid.attach');
    Route::get('images/grid/approvedImages', 'ImageController@approved')->name('image.grid.approved');
    Route::get('images/grid/finalApproval', 'ImageController@final')->name('image.grid.final.approval');
    Route::get('images/grid/{id}', 'ImageController@show')->name('image.grid.show');
    Route::get('images/grid/{id}/edit', 'ImageController@edit')->name('image.grid.edit');
    Route::post('images/grid/{id}/edit', 'ImageController@update')->name('image.grid.update');
    Route::delete('images/grid/{id}/delete', 'ImageController@destroy')->name('image.grid.delete');
    Route::post('images/grid/{id}/approveImage', 'ImageController@approveImage')->name('image.grid.approveImage');
    Route::get('images/grid/{id}/download', 'ImageController@download')->name('image.grid.download');
    Route::post('images/grid/make/set', 'ImageController@set')->name('image.grid.set');
    Route::post('images/grid/make/set/download', 'ImageController@setDownload')->name('image.grid.set.download');
    Route::post('images/grid/update/schedule', 'ImageController@updateSchedule')->name('image.grid.update.schedule');
    Route::post('images/searchQueue', 'ImageController@imageQueue')->name('image.queue');

    Route::post('leads/save-leave-message', 'LeadsController@saveLeaveMessage')->name('leads.message.save');

    Route::get('imported/leads', 'ColdLeadsController@showImportedColdLeads');
    Route::get('imported/leads/save', 'ColdLeadsController@addLeadToCustomer');

    // Development
    Route::post('development/task/move-to-progress', 'DevelopmentController@moveTaskToProgress');
    Route::post('development/task/complete-task', 'DevelopmentController@completeTask');
    Route::post('development/task/assign-task', 'DevelopmentController@updateAssignee');
    Route::post('development/task/relist-task', 'DevelopmentController@relistTask');
    Route::post('development/task/update-status', 'DevelopmentController@changeTaskStatus');
    Route::post('development/task/upload-document', 'DevelopmentController@uploadDocument');
    Route::post('development/task/bulk-delete', 'DevelopmentController@deleteBulkTasks');
    Route::get('development/task/get-document', 'DevelopmentController@getDocument');
    Route::get('development/task/export-task', 'DevelopmentController@exportTask');


    Route::resource('task-types', 'TaskTypesController');

    Route::resource('development-messages-schedules', 'DeveloperMessagesAlertSchedulesController');
    Route::get('development', 'DevelopmentController@index')->name('development.index');
    Route::post('development/task/list-by-user-id', 'DevelopmentController@taskListByUserId')->name('development.task.list.by.user.id');
    Route::post('development/task/set-priority', 'DevelopmentController@setTaskPriority')->name('development.task.set.priority');
    Route::post('development/create', 'DevelopmentController@store')->name('development.store');
    Route::post('development/{id}/edit', 'DevelopmentController@update')->name('development.update');
    Route::post('development/{id}/verify', 'DevelopmentController@verify')->name('development.verify');
    Route::get('development/verify/view', 'DevelopmentController@verifyView')->name('development.verify.view');
    Route::delete('development/{id}/destroy', 'DevelopmentController@destroy')->name('development.destroy');
    Route::post('development/{id}/updateCost', 'DevelopmentController@updateCost')->name('development.update.cost');
    Route::post('development/{id}/status', 'DevelopmentController@updateStatus')->name('development.update.status');
    Route::post('development/{id}/updateTask', 'DevelopmentController@updateTask')->name('development.update.task');
    Route::post('development/{id}/updatePriority', 'DevelopmentController@updatePriority')->name('development.update.priority');
    Route::post('development/upload-attachments', 'DevelopmentController@uploadAttachDocuments')->name('development.upload.files');
    Route::get('download-file', 'DevelopmentController@downloadFile')->name('download.file');

    //Route::get('deve lopment/issue/list', 'DevelopmentController@issueIndex')->name('development.issue.index');


    Route::post('development/reminder', 'DevelopmentController@updateDevelopmentReminder');


    Route::get('development/list', 'DevelopmentController@issueTaskIndex')->name('development.issue.index');
    Route::get('development/summarylist', 'DevelopmentController@summaryList')->name('development.summarylist');
    //Route::get('development/issue/list', 'DevelopmentController@issueIndex')->name('development.issue.index');
    Route::post('development/issue/list-by-user-id', 'DevelopmentController@listByUserId')->name('development.issue.list.by.user.id');
    Route::post('development/issue/set-priority', 'DevelopmentController@setPriority')->name('development.issue.set.priority');
    //Route::post('development/time/history/approve', 'DevelopmentController@approveTimeHistory')->name('development/time/history/approve');
    Route::post('development/time/history/approve', 'DevelopmentController@approveTimeHistory')->name('development/time/history/approve');
    Route::post('development/time/history/approve/sendMessage', 'DevelopmentController@sendReviseMessage')->name('development/time/history/approve/sendMessage');
    Route::post('development/time/history/approve/sendRemindMessage', 'DevelopmentController@sendRemindMessage')->name('development/time/history/approve/sendRemindMessage');
    Route::post('development/date/history/approve', 'DevelopmentController@approveDateHistory')->name('development/date/history/approve');
    Route::post('development/lead/time/history/approve', 'DevelopmentController@approveLeadTimeHistory')->name('development/lead/time/history/approve');
    Route::post('development/time/meeting/approve/{task_id}', 'DevelopmentController@approveMeetingHistory')->name('development/time/meeting/approve');
    Route::post('development/time/meeting/store', 'DevelopmentController@storeMeetingTime')->name('development/time/meeting/store');
    Route::get('development/issue/create', 'DevelopmentController@issueCreate')->name('development.issue.create');
    Route::post('development/issue/create', 'DevelopmentController@issueStore')->name('development.issue.store');
    Route::get('development/issue/user/assign', 'DevelopmentController@assignUser');
    Route::get('development/issue/master/assign', 'DevelopmentController@assignMasterUser');
    Route::get('development/issue/team-lead/assign', 'DevelopmentController@assignTeamlead');
    Route::get('development/issue//tester/assign', 'DevelopmentController@assignTester');
    Route::get('development/issue/time/meetings', 'DevelopmentController@getMeetingTimings');
    Route::get('development/issue/module/assign', 'DevelopmentController@changeModule');
    Route::get('development/issue/user/resolve', 'DevelopmentController@resolveIssue');
    Route::get('development/issue/estimate_date/assign', 'DevelopmentController@saveEstimateTime');
    Route::get('development/issue/estimate_date-change/assign', 'DevelopmentController@saveEstimateDate');
    
    Route::get('development/date/history', 'DevelopmentController@getDateHistory')->name('development/date/history');

    Route::get('development/status/history', 'DevelopmentController@getStatusHistory')->name('development/status/history');

    Route::get('development/issue/estimate_minutes/assign', 'DevelopmentController@saveEstimateMinutes')->name('development.issue.estimate_minutes.store');
    Route::get('development/issue/priority-no/assign', 'DevelopmentController@savePriorityNo')->name('development.issue.savePriorityNo.store');


    Route::get('development/issue/lead_estimate_minutes/assign', 'DevelopmentController@saveLeadEstimateTime')->name('development.issue.lead_estimate_minutes.store');
    Route::get('development/issue/responsible-user/assign', 'DevelopmentController@assignResponsibleUser');
    Route::get('development/issue/cost/assign', 'DevelopmentController@saveAmount');
    Route::get('development/issue/milestone/assign', 'DevelopmentController@saveMilestone');
    Route::get('development/issue/language/assign', 'DevelopmentController@saveLanguage');
    Route::post('development/{id}/assignIssue', 'DevelopmentController@issueAssign')->name('development.issue.assign');
    Route::delete('development/{id}/issueDestroy', 'DevelopmentController@issueDestroy')->name('development.issue.destroy');
    Route::get('development/overview', 'DevelopmentController@overview')->name('development.overview');
    Route::get('development/task-detail/{id}', 'DevelopmentController@taskDetail')->name('taskDetail');
    Route::get('development/new-task-popup', 'DevelopmentController@openNewTaskPopup')->name('openNewTaskPopup');

    Route::post('development/status/create', 'DevelopmentController@statusStore')->name('development.status.store');
    Route::post('development/module/create', 'DevelopmentController@moduleStore')->name('development.module.store');
    Route::delete('development/module/{id}/destroy', 'DevelopmentController@moduleDestroy')->name('development.module.destroy');
    Route::post('development/{id}/assignModule', 'DevelopmentController@moduleAssign')->name('development.module.assign');

    Route::post('development/comment/create', 'DevelopmentController@commentStore')->name('development.comment.store');
    Route::post('task/comment/create', 'DevelopmentController@taskComment')->name('task.comment.store');
    Route::post('development/{id}/awaiting/response', 'DevelopmentController@awaitingResponse')->name('development.comment.awaiting.response');

    Route::post('development/cost/store', 'DevelopmentController@costStore')->name('development.cost.store');

    // Development
    Route::get('development', 'DevelopmentController@index')->name('development.index');
    Route::get('development/update-values', 'DevelopmentController@updateValues');
    Route::post('development/create', 'DevelopmentController@store')->name('development.store');
    Route::post('development/{id}/edit', 'DevelopmentController@update')->name('development.update');
    Route::delete('development/{id}/destroy', 'DevelopmentController@destroy')->name('development.destroy');

    Route::get('development/issue/list', 'DevelopmentController@issueIndex')->name('development.issue.index');
    Route::get('development/issue/create', 'DevelopmentController@issueCreate')->name('development.issue.create');
    Route::post('development/issue/create', 'DevelopmentController@issueStore')->name('development.issue.store');
    Route::post('development/{id}/assignIssue', 'DevelopmentController@issueAssign')->name('development.issue.assign');
    Route::delete('development/{id}/issueDestroy', 'DevelopmentController@issueDestroy')->name('development.issue.destroy');

    Route::post('development/module/create', 'DevelopmentController@moduleStore')->name('development.module.store');
    Route::delete('development/module/{id}/destroy', 'DevelopmentController@moduleDestroy')->name('development.module.destroy');
    Route::post('development/{id}/assignModule', 'DevelopmentController@moduleAssign')->name('development.module.assign');

    Route::post('development/comment/create', 'DevelopmentController@commentStore')->name('development.comment.store');
    Route::post('development/{id}/awaiting/response', 'DevelopmentController@awaitingResponse')->name('development.comment.awaiting.response');

    Route::post('development/cost/store', 'DevelopmentController@costStore')->name('development.cost.store');
    Route::get('development/time/history', 'DevelopmentController@getTimeHistory')->name('development/time/history');
    Route::get('development/lead/time/history', 'DevelopmentController@getLeadTimeHistory')->name('development/lead/time/history');
    Route::get('development/user/history', 'DevelopmentController@getUserHistory')->name('development/user/history');
    Route::get('development/tracked/history', 'DevelopmentController@getTrackedHistory')->name('development/tracked/history');
    Route::post('development/create/hubstaff_task', 'DevelopmentController@createHubstaffManualTask')->name('development/create/hubstaff_task');

    /*Routes For Social */
    Route::any('social/get-post/page', 'SocialController@pagePost')->name('social.get-post.page');

    // post creating routes define's here
    Route::get('social/post/page', 'SocialController@index')->name('social.post.page');
    Route::post('social/post/page/create', 'SocialController@createPost')->name('social.post.page.create');

    /*Routes For Social */
    Route::any('social/get-post/page', 'SocialController@pagePost')->name('social.get-post.page');

    // post creating routes define's here
    Route::get('social/post/page', 'SocialController@index')->name('social.post.page');
    Route::post('social/post/page/create', 'SocialController@createPost')->name('social.post.page.create');

    // Ad reports routes
    Route::get('social/ad/report', 'SocialController@report')->name('social.report');
    Route::get('social/ad/schedules', 'SocialController@getSchedules')->name('social.ads.schedules');
    Route::post('social/ad/schedules', 'SocialController@getSchedules')->name('social.ads.schedules.p');
    Route::get('social/ad/schedules/calendar', 'SocialController@getAdSchedules')->name('social.ads.schedules.calendar');
    Route::post('social/ad/schedules/', 'SocialController@createAdSchedule')->name('social.ads.schedules.create');
    Route::post('social/ad/schedules/attach-images/{id}', 'SocialController@attachMedia')->name('social.ads.schedules.attach_images');
    Route::post('social/ad/schedules/attach-products/{id}', 'SocialController@attachProducts')->name('social.ads.schedules.attach_products');
    Route::post('social/ad/schedules/', 'SocialController@createAdSchedule')->name('social.ads.schedules.attach_image');
    Route::get('social/ad/schedules/{id}', 'SocialController@showSchedule')->name('social.ads.schedules.show');
    Route::get('social/ad/insight/{adId}', 'SocialController@getAdInsights')->name('social.ad.insight');
    Route::post('social/ad/report/paginate', 'SocialController@paginateReport')->name('social.report.paginate');
    Route::get('social/ad/report/{ad_id}/{status}/', 'SocialController@changeAdStatus')->name('social.report.ad.status');
    // end to ad reports routes

    // AdCreative reports routes
    Route::get('social/adcreative/report', 'SocialController@adCreativereport')->name('social.adCreative.report');
    Route::post('social/adcreative/report/paginate', 'SocialController@adCreativepaginateReport')->name('social.adCreative.paginate');
    // end to ad reports routes

    // Creating Ad Campaign Routes defines here
    Route::get('social/ad/campaign/create', 'SocialController@createCampaign')->name('social.ad.campaign.create');
    Route::post('social/ad/campaign/store', 'SocialController@storeCampaign')->name('social.ad.campaign.store');

    // Creating Adset Routes define here
    Route::get('social/ad/adset/create', 'SocialController@createAdset')->name('social.ad.adset.create');
    Route::post('social/ad/adset/store', 'SocialController@storeAdset')->name('social.ad.adset.store');

    // Creating Ad Routes define here
    Route::get('social/ad/create', 'SocialController@createAd')->name('social.ad.create');
    Route::post('social/ad/store', 'SocialController@storeAd')->name('social.ad.store');
    // End of Routes for social

    // Paswords Manager
    Route::get('passwords', 'PasswordController@index')->name('password.index');
    Route::post('password/store', 'PasswordController@store')->name('password.store');
    Route::get('password/passwordManager', 'PasswordController@manage')->name('password.manage');
    Route::post('password/change', 'PasswordController@changePassword')->name('password.change');
    Route::post('password/sendWhatsApp', 'PasswordController@sendWhatsApp')->name('password.sendwhatsapp');
    Route::post('password/update', 'PasswordController@update')->name('password.update');
    Route::post('password/getHistory', 'PasswordController@getHistory')->name('password.history');

    //Language Manager
    Route::get('languages', 'LanguageController@index')->name('language.index');
    Route::post('language/store', 'LanguageController@store')->name('language.store');
    Route::post('language/update', 'LanguageController@update')->name('language.update');
    Route::post('language/delete', 'LanguageController@delete')->name('language.delete');


    // Documents Manager
    Route::get('documents', 'DocumentController@index')->name('document.index');
    Route::get('documents-email', 'DocumentController@email')->name('document.email');
    Route::post('document/store', 'DocumentController@store')->name('document.store');
    Route::post('document/{id}/update', 'DocumentController@update')->name('document.update');
    Route::get('document/{id}/download', 'DocumentController@download')->name('document.download');
    Route::delete('document/{id}/destroy', 'DocumentController@destroy')->name('document.destroy');
    Route::post('document/send/emailBulk', 'DocumentController@sendEmailBulk')->name('document.email.send.bulk');
    Route::get('document/gettaskremark', 'DocumentController@getTaskRemark')->name('document.gettaskremark');
    Route::post('document/uploadocument', 'DocumentController@uploadDocument')->name('document.uploadDocument');
    Route::post('document/addremark', 'DocumentController@addRemark')->name('document.addRemark');

    //Document Cateogry
    Route::post('documentcategory/add', 'DocuemntCategoryController@addCategory')->name('documentcategory.add');

    //SKU
    Route::get('sku-format/datatables', 'SkuFormatController@getData')->name('skuFormat.datatable');
    Route::get('sku-format/history', 'SkuFormatController@history')->name('skuFormat.history');
    Route::resource('sku-format', 'SkuFormatController');
    Route::post('sku-format/update', 'SkuFormatController@update')->name('sku.update');
    Route::get('sku/color-codes', 'SkuController@colorCodes')->name('sku.color-codes');
    Route::get('sku/color-codes-update', 'SkuController@colorCodesUpdate')->name('sku.color-codes-update');

    // Cash Flow Module
    Route::get('cashflow/{id}/download', 'CashFlowController@download')->name('cashflow.download');
    Route::get('cashflow/mastercashflow', 'CashFlowController@mastercashflow')->name('cashflow.mastercashflow');
    Route::resource('cashflow', 'CashFlowController');
    Route::resource('dailycashflow', 'DailyCashFlowController');

    //URL Routes Module
    Route::get('routes', 'RoutesController@index')->name('routes.index');
    Route::get('routes/index', 'RoutesController@index')->name('routes.index');
    Route::get('routes/sync', 'RoutesController@sync')->name('routes.sync');
    Route::any('routes/update/{id}', 'RoutesController@update')->name('routes.update');

    // Reviews Module
    Route::post('review/createFromInstagramHashtag', 'ReviewController@createFromInstagramHashtag');
    Route::post('review/restart-script', 'ReviewController@restartScript');
    Route::get('review/instagram/reply', 'ReviewController@replyToPost');
    Route::post('review/instagram/dm', 'ReviewController@sendDm');
    Route::get('review/{id}/updateStatus', 'ReviewController@updateStatus');
    Route::post('review/{id}/updateStatus', 'ReviewController@updateStatus');
    Route::post('review/{id}/updateReview', 'ReviewController@updateReview');
    Route::resource('review', 'ReviewController');
    Route::post('review/schedule/create', 'ReviewController@scheduleStore')->name('review.schedule.store');
    Route::put('review/schedule/{id}', 'ReviewController@scheduleUpdate')->name('review.schedule.update');
    Route::post('review/schedule/{id}/status', 'ReviewController@scheduleUpdateStatus')->name('review.schedule.updateStatus');
    Route::delete('review/schedule/{id}/destroy', 'ReviewController@scheduleDestroy')->name('review.schedule.destroy');
    Route::get('account/{id}', 'AccountController@show');
    Route::post('account/igdm/{id}', 'AccountController@sendMessage');
    Route::post('account/bulk/{id}', 'AccountController@addMessageSchedule');
    Route::post('account/create', 'ReviewController@accountStore')->name('account.store');
    Route::put('account/{id}', 'ReviewController@accountUpdate')->name('account.update');
    Route::delete('account/{id}/destroy', 'ReviewController@accountDestroy')->name('account.destroy');

    Route::resource('brand-review/get', 'BrandReviewController@getAllBrandReview');
    // Threads Routes
    Route::resource('thread', 'ThreadController');
    Route::post('thread/{id}/status', 'ThreadController@updateStatus')->name('thread.updateStatus');

    // Complaints Routes
    Route::resource('complaint', 'ComplaintController');
    Route::post('complaint/{id}/status', 'ComplaintController@updateStatus')->name('complaint.updateStatus');

    // Vendor Module
    Route::get('vendors/product', 'VendorController@product')->name('vendors.product.index');
    Route::post('vendors/store', 'VendorController@store')->name('vendors.store');
    Route::post('vendors/reply/add', 'VendorController@addReply')->name('vendors.reply.add');
    Route::get('vendors/reply/delete', 'VendorController@deleteReply')->name('vendors.reply.delete');
    Route::post('vendors/send/emailBulk', 'VendorController@sendEmailBulk')->name('vendors.email.send.bulk');
    Route::post('vendors/create-user', 'VendorController@createUser')->name('vendors.create.user');
    Route::post('vendors/edit-vendor', 'VendorController@editVendor')->name('vendors.edit-vendor');
    Route::post('vendors/send/message', 'VendorController@sendMessage')->name('vendors/send/message');
    Route::post('vendors/send/email', 'VendorController@sendEmail')->name('vendors.email.send');
    Route::get('vendors/email/inbox', 'VendorController@emailInbox')->name('vendors.email.inbox');
    Route::post('vendors/product', 'VendorController@productStore')->name('vendors.product.store');
    Route::put('vendors/product/{id}', 'VendorController@productUpdate')->name('vendors.product.update');
    Route::delete('vendors/product/{id}', 'VendorController@productDestroy')->name('vendors.product.destroy');
    Route::get('vendors/{vendor}/payments', 'VendorPaymentController@index')->name('vendors.payments');
    Route::post('vendors/{vendor}/payments', 'VendorPaymentController@store')->name('vendors.payments.store');
    Route::put('vendors/{vendor}/payments/{vendor_payment}', 'VendorPaymentController@update')->name('vendors.payments.update');
    Route::delete('vendors/{vendor}/payments/{vendor_payment}', 'VendorPaymentController@destroy')->name('vendors.payments.destroy');
    Route::resource('vendors', 'VendorController');
    Route::get('vendor-search', 'VendorController@vendorSearch')->name('vendor-search');
    Route::get('vendor-search-phone', 'VendorController@vendorSearchPhone')->name('vendor-search-phone');
    Route::get('vendor-search-email', 'VendorController@vendorSearchEmail')->name('vendor-search-email');

    Route::post('vendors/email', 'VendorController@email')->name('vendors.email');
    Route::post('vendot/block', 'VendorController@block')->name('vendors.block');
    Route::post('vendors/inviteGithub', 'VendorController@inviteGithub');
    Route::post('vendors/inviteHubstaff', 'VendorController@inviteHubstaff');
    Route::post('vendors/changeHubstaffUserRole', 'VendorController@changeHubstaffUserRole');
    Route::post('vendors/change-status', 'VendorController@changeStatus');
    Route::get('vendor_category/assign-user', 'VendorController@assignUserToCategory');

    Route::prefix('hubstaff-payment')->group(function () {
        Route::get('/', 'HubstaffPaymentController@index')->name('hubstaff-payment.index');
        Route::get('records', 'HubstaffPaymentController@records')->name('hubstaff-payment.records');
        Route::post('save', 'HubstaffPaymentController@save')->name('hubstaff-payment.save');
        Route::post('merge-category', 'HubstaffPaymentController@mergeCategory')->name('hubstaff-payment.merge-category');
        Route::prefix('{id}')->group(function () {
            Route::get('edit', 'HubstaffPaymentController@edit')->name('hubstaff-payment.edit');
            Route::get('delete', 'HubstaffPaymentController@delete')->name('hubstaff-payment.delete');
        });
    });

    Route::prefix('hubstaff-activities')->group(function () {

        Route::get('/report', 'HubstaffActivitiesController@activityReport')->name('hubstaff-acitivtity.report');
        Route::get('/report-download', 'HubstaffActivitiesController@activityReportDownload')->name('hubstaff-acitivtity-report.download');

        Route::prefix('notification')->group(function () {
            Route::get('/', 'HubstaffActivitiesController@notification')->name('hubstaff-acitivties.notification.index');
            Route::post('/download', 'HubstaffActivitiesController@downloadNotification')->name('hubstaff-acitivties.notification.download');
            Route::get('/records', 'HubstaffActivitiesController@notificationRecords')->name('hubstaff-acitivties.notification.records');
            Route::post('/save', 'HubstaffActivitiesController@notificationReasonSave')->name('hubstaff-acitivties.notification.save-reason');
            Route::post('/change-status', 'HubstaffActivitiesController@changeStatus')->name('hubstaff-acitivties.notification.change-status');
        });
        Route::prefix('activities')->group(function () {
            Route::get('/', 'HubstaffActivitiesController@getActivityUsers')->name('hubstaff-acitivties.activities');
            Route::get('/details', 'HubstaffActivitiesController@getActivityDetails')->name('hubstaff-acitivties.activity-details');
            Route::post('/details', 'HubstaffActivitiesController@approveActivity')->name('hubstaff-acitivties.approve-activity');
            Route::post('/final-submit', 'HubstaffActivitiesController@finalSubmit')->name('hubstaff-activities/activities/final-submit');
            Route::post('/task-notes', 'HubstaffActivitiesController@NotesHistory')->name('hubstaff-activities.task.notes');
            Route::get('/save-notes', 'HubstaffActivitiesController@saveNotes')->name('hubstaff-activities.task.save.notes');
            Route::get('/approve-all-time', 'HubstaffActivitiesController@approveTime')->name('hubstaff-acitivties.approve.time');
            Route::post('/fetch', 'HubstaffActivitiesController@fetchActivitiesFromHubstaff')->name('hubstaff-activities/activities/fetch');
            Route::post('/manual-record', 'HubstaffActivitiesController@submitManualRecords')->name('hubstaff-acitivties.manual-record');
            Route::get('/records', 'HubstaffActivitiesController@notificationRecords')->name('hubstaff-acitivties.notification.records');
            Route::post('/save', 'HubstaffActivitiesController@notificationReasonSave')->name('hubstaff-acitivties.notification.save-reason');
            Route::post('/change-status', 'HubstaffActivitiesController@changeStatus')->name('hubstaff-acitivties.notification.change-status');
            Route::get('/approved/pending-payments', 'HubstaffActivitiesController@approvedPendingPayments')->name('hubstaff-acitivties.pending-payments');
            Route::post('/approved/payment', 'HubstaffActivitiesController@submitPaymentRequest')->name("hubstaff-acitivties.payment-request.submit");
            Route::post('/add-efficiency', 'HubstaffActivitiesController@AddEfficiency')->name('hubstaff-acitivties.efficiency.save');
            Route::get('/task-activity', 'HubstaffActivitiesController@taskActivity')->name('hubstaff-acitivties.acitivties.task-activity');
        });

        Route::post('save', 'HubstaffPaymentController@save')->name('hubstaff-payment.save');
        Route::post('merge-category', 'HubstaffPaymentController@mergeCategory')->name('hubstaff-payment.merge-category');
        Route::prefix('{id}')->group(function () {
            Route::get('edit', 'HubstaffPaymentController@edit')->name('hubstaff-payment.edit');
            Route::get('delete', 'HubstaffPaymentController@delete')->name('hubstaff-payment.delete');
        });
    });

    Route::prefix('manage-modules')->group(function () {
        Route::get('/', 'ManageModulesController@index')->name('manage-modules.index');
        Route::get('records', 'ManageModulesController@records')->name('manage-modules.records');
        Route::post('save', 'ManageModulesController@save')->name('manage-modules.save');
        Route::post('merge-module', 'ManageModulesController@mergeModule')->name('manage-modules.merge-module');
        Route::prefix('{id}')->group(function () {
            Route::get('edit', 'ManageModulesController@edit')->name('manage-modules.edit');
            Route::get('delete', 'ManageModulesController@delete')->name('manage-modules.delete');
        });
    });

    Route::prefix('manage-task-category')->group(function () {
        Route::get('/', 'ManageTaskCategoryController@index')->name('manage-task-category.index');
        Route::get('records', 'ManageTaskCategoryController@records')->name('manage-task-category.records');
        Route::post('save', 'ManageTaskCategoryController@save')->name('manage-task-category.save');
        Route::post('merge-module', 'ManageTaskCategoryController@mergeModule')->name('manage-task-category.merge-module');
        Route::prefix('{id}')->group(function () {
            Route::get('edit', 'ManageTaskCategoryController@edit')->name('manage-task-category.edit');
            Route::get('delete', 'ManageTaskCategoryController@delete')->name('manage-task-category.delete');
        });
    });


    Route::prefix('vendor-category')->group(function () {
        Route::get('/', 'VendorCategoryController@index')->name('vendor-category.index');
        Route::get('records', 'VendorCategoryController@records')->name('vendor-category.records');
        Route::post('save', 'VendorCategoryController@save')->name('vendor-category.save');
        Route::post('merge-category', 'VendorCategoryController@mergeCategory')->name('vendor-category.merge-category');
        Route::get('/permission', 'VendorCategoryController@usersPermission')->name('vendor-category.permission');
        Route::post('/update/permission', 'VendorCategoryController@updatePermission')->name('vendor-category.update.permission');

        Route::prefix('{id}')->group(function () {
            Route::get('edit', 'VendorCategoryController@edit')->name('vendor-category.edit');
            Route::get('delete', 'VendorCategoryController@delete')->name('vendor-category.delete');
        });
    });

    Route::resource('vendor_category', 'VendorCategoryController');

    // Suppliers Module
    Route::get('supplier/categorycount', 'SupplierController@addSupplierCategoryCount')->name('supplier.count');
    Route::post('supplier/saveCategoryCount', 'SupplierController@saveSupplierCategoryCount')->name('supplier.count.save');
    Route::post('supplier/getCategoryCount', 'SupplierController@getSupplierCategoryCount')->name('supplier.count.get');
    Route::post('supplier/updateCategoryCount', 'SupplierController@updateSupplierCategoryCount')->name('supplier.count.update');
    Route::post('supplier/deleteCategoryCount', 'SupplierController@deleteSupplierCategoryCount')->name('supplier.count.delete');

    Route::get('supplier/brandcount', 'SupplierController@addSupplierBrandCount')->name('supplier.brand.count');
    Route::post('supplier/saveBrandCount', 'SupplierController@saveSupplierBrandCount')->name('supplier.brand.count.save');
    Route::post('supplier/getBrandCount', 'SupplierController@getSupplierBrandCount')->name('supplier.brand.count.get');
    Route::post('supplier/updateBrandCount', 'SupplierController@updateSupplierBrandCount')->name('supplier.brand.count.update');
    Route::post('supplier/deleteBrandCount', 'SupplierController@deleteSupplierBrandCount')->name('supplier.brand.count.delete');

    // Get supplier brands and raw brands
    Route::get('supplier/get-scraped-brands', 'SupplierController@getScrapedBrandAndBrandRaw')->name('supplier.scrapedbrands.list');
    // Update supplier brands and raw brands
    Route::post('supplier/update-scraped-brands', 'SupplierController@updateScrapedBrandFromBrandRaw')->name('supplier.scrapedbrands.update');
    // Remove particular scrap brand from scraped brands
    Route::post('supplier/remove-scraped-brands', 'SupplierController@removeScrapedBrand')->name('supplier.scrapedbrands.remove');
    // Copy scraped brands to brands
    Route::post('supplier/copy-scraped-brands', 'SupplierController@copyScrapedBrandToBrand')->name('supplier.scrapedbrands.copy');

    Route::post('supplier/update-brands', 'SupplierController@updateScrapedBrandFromBrandRaw')->name('supplier.brands.update');

    Route::post('supplier/send/emailBulk', 'SupplierController@sendEmailBulk')->name('supplier.email.send.bulk');

    Route::post('supplier/change-whatsapp-no', 'SupplierController@changeWhatsappNo')->name('supplier.change.whatsapp');

    Route::get('supplier/{id}/loadMoreMessages', 'SupplierController@loadMoreMessages');
    Route::post('supplier/flag', 'SupplierController@flag')->name('supplier.flag');
    
    Route::post('supplier/trasnlate/history', 'SupplierController@MessageTranslateHistory')->name('supplier.history');
    Route::resource('supplier', 'SupplierController');
    Route::resource('google-server', 'GoogleServerController');
    Route::post('log-google-cse', 'GoogleServerController@logGoogleCse')->name('log.google.cse');
    

    Route::resource('email-addresses', 'EmailAddressesController');
    
    Route::post('email/geterroremailhistory', 'EmailAddressesController@getErrorEmailHistory');

    Route::get('email/failed/download/history', 'EmailAddressesController@downloadFailedHistory')->name('email.failed.download');

    Route::post('email/getemailhistory/{id}', 'EmailAddressesController@getEmailAddressHistory');
    
    Route::get('email/get-related-account/{id}', 'EmailAddressesController@getRelatedAccount');
    
    Route::post('supplier/block', 'SupplierController@block')->name('supplier.block');
    
    Route::post('supplier/saveImage', 'SupplierController@saveImage')->name('supplier.image');;
    
    Route::post('supplier/change-status', 'SupplierController@changeStatus');
    
    Route::post('supplier/change/category', 'SupplierController@changeCategory')->name('supplier/change/category');
    Route::post('supplier/change/status', 'SupplierController@changeSupplierStatus')->name('supplier/change/status');
    Route::post('supplier/change/subcategory', 'SupplierController@changeSubCategory')->name('supplier/change/subcategory');
    Route::post('supplier/add/category', 'SupplierController@addCategory')->name('supplier/add/category');
    Route::post('supplier/add/subcategory', 'SupplierController@addSubCategory')->name('supplier/add/subcategory');
    Route::post('supplier/add/status', 'SupplierController@addStatus')->name('supplier/add/status');
    Route::post('supplier/add/suppliersize', 'SupplierController@addSupplierSize')->name('supplier/add/suppliersize');
    Route::post('supplier/change/inventorylifetime', 'SupplierController@editInventorylifetime')->name('supplier/change/inventorylifetime');
    Route::post('supplier/change/scrapper', 'SupplierController@changeScrapper')->name('supplier/change/scrapper');
    Route::post('supplier/send/message', 'SupplierController@sendMessage')->name('supplier/send/message');
    Route::post('supplier/change/mail', 'SupplierController@changeMail')->name('supplier/change/mail');
    Route::post('supplier/change/phone', 'SupplierController@changePhone')->name('supplier/change/phone');
    Route::post('supplier/change/size', 'SupplierController@changeSize')->name('supplier/change/size');
    Route::post('supplier/change/size-system', 'SupplierController@changeSizeSystem')->name('supplier/change/size-system');
    Route::post('supplier/change/whatsapp', 'SupplierController@changeWhatsapp')->name('supplier/change/whatsapp');
    // Supplier Category Permission
    Route::get('supplier/category/permission', 'SupplierCategoryController@usersPermission')->name('supplier/category/permission');
    Route::post('supplier/category/update/permission', 'SupplierCategoryController@updatePermission')->name('supplier/category/update/permission');

    Route::post('supplier/add/pricernage', 'SupplierController@addPriceRange')->name('supplier/add/pricernage');
    Route::post('supplier/change/pricerange', 'SupplierController@changePriceRange')->name('supplier/change/pricerange');

    // API Response
    Route::get('api-response','ApiResponseMessageController@index')->name('api-response-message');
    Route::post('api-response','ApiResponseMessageController@store')->name('api-response-message.store');
    Route::post('/getEditModal','ApiResponseMessageController@getEditModal')->name('getEditModal');
    Route::post('/api-response-message-update','ApiResponseMessageController@update')->name('api-response-message.updateResponse');
    Route::get('/api-response-message-dalete/{id}','ApiResponseMessageController@destroy')->name('api-response-message.responseDelete');

    Route::resource('assets-manager', 'AssetsManagerController');
    Route::post('assets-manager/add-note/{id}', 'AssetsManagerController@addNote');
    Route::post('assets-manager/payment-history', 'AssetsManagerController@paymentHistory')->name('assetsmanager.paymentHistory');
    // Agent Routes
    Route::resource('agent', 'AgentController');
    //Route::resource('product-templates', 'ProductTemplatesController');

    Route::prefix('product-templates')->middleware('auth')->group(function () {
        Route::get('/', 'ProductTemplatesController@index')->name('product.templates');
        Route::post('/', 'ProductTemplatesController@index')->name('product.templates');
        Route::get('response', 'ProductTemplatesController@response');
        Route::post('create', 'ProductTemplatesController@create');
        Route::post('reload-image', 'ProductTemplatesController@fetchImage');
        Route::get('destroy/{id}', 'ProductTemplatesController@destroy');
        Route::get('select-product-id', 'ProductTemplatesController@selectProductId');
        Route::get('image', 'ProductTemplatesController@imageIndex');
    });

    Route::prefix('templates')->middleware('auth')->group(function () {

        Route::get('/', 'TemplatesController@index')->name('templates');

        Route::get('response', 'TemplatesController@response');

        //Route::get('bearbanner', 'TemplatesController@updateTemplatesFromBearBanner');

        Route::get('fetch/bearbanner/templates', 'TemplatesController@updateTemplatesFromBearBanner')->name('fetch.bearbanner.templates');

        Route::post('update/bearbanner/template', 'TemplatesController@updateBearBannerTemplate')->name('update.bearbanner.template');

        

        
        Route::post('create', 'TemplatesController@create');
        Route::post('edit', 'TemplatesController@edit');
        Route::get('destroy/{id}', 'TemplatesController@destroy');
        Route::get('generate-template-category-branch', 'TemplatesController@generateTempalateCategoryBrand');
        Route::get('type', 'TemplatesController@typeIndex')->name('templates.type');
    });

    Route::prefix('erp-events')->middleware('auth')->group(function () {
        Route::get('/', 'ErpEventController@index')->name('erp-events');
        Route::post('/store', 'ErpEventController@store')->name('erp-events.store');
        Route::get('/dummy', 'ErpEventController@dummy')->name('erp-events.dummy');
    });

    Route::get('/drafted-products', 'ProductController@draftedProducts');
    Route::get('/drafted-products/edit', 'ProductController@editDraftedProduct');
    Route::post('/drafted-products/edit', 'ProductController@editDraftedProducts');
    Route::post('/drafted-products/delete', 'ProductController@deleteDraftedProducts');
    Route::post('/drafted-products/addtoquicksell', 'ProductController@addDraftProductsToQuickSell');

});


/* ------------------Twilio functionality Routes[PLEASE DONT MOVE INTO MIDDLEWARE AUTH] ------------------------ */


Route::get('twilio/token', 'TwilioController@createToken');
Route::post('twilio/ivr', 'TwilioController@ivr')->name('ivr');
Route::post('twilio/gatherAction', 'TwilioController@gatherAction');
Route::post('twilio/incoming', 'TwilioController@incomingCall');
Route::post('twilio/outgoing', 'TwilioController@outgoingCall');
Route::get('twilio/getLeadByNumber', 'TwilioController@getLeadByNumber');
Route::post('twilio/recordingStatusCallback', 'TwilioController@recordingStatusCallback');
Route::post('twilio/handleDialCallStatus', 'TwilioController@handleDialCallStatus');
Route::post('twilio/handleOutgoingDialCallStatus', 'TwilioController@handleOutgoingDialCallStatus');
Route::post('twilio/storerecording', 'TwilioController@storeRecording');
Route::post('twilio/storetranscript', 'TwilioController@storetranscript');
Route::post('twilio/eventsFromFront', 'TwilioController@eventsFromFront');

Route::post('twilio/twilio_menu_response', 'TwilioController@twilio_menu_response')->name('twilio_menu_response');
Route::post('twilio/change_agent_status', 'TwilioController@change_agent_status')->name('change_agent_status');
Route::post('twilio/change_agent_call_status', 'TwilioController@change_agent_call_status')->name('change_agent_call_status');
Route::post('twilio/leave_message_rec', 'TwilioController@leave_message_rec')->name('leave_message_rec');

Route::get(
    '/twilio/hangup',
    [
        'as' => 'hangup',
        'uses' => 'TwilioController@showHangup'
    ]
);

Route::get('exotel/outgoing', 'ExotelController@call')->name('exotel.call');
Route::get('exotel/checkNumber', 'ExotelController@checkNumber');
Route::post('exotel/recordingCallback', 'ExotelController@recordingCallback');

/* ---------------------------------------------------------------------------------- */

/* ------------------Twilio functionality Routes[PLEASE DONT MOVE INTO MIDDLEWARE AUTH] ------------------------ */

//Route::middleware('auth')->group(function()
//{


Route::post('livechat/incoming', 'LiveChatController@incoming');
Route::post('livechat/getChats', 'LiveChatController@getChats')->name('livechat.get.message');
Route::post('livechat/getChatsWithoutRefresh', 'LiveChatController@getChatMessagesWithoutRefresh')->name('livechat.message.withoutrefresh');
Route::post('livechat/sendMessage', 'LiveChatController@sendMessage')->name('livechat.send.message');
Route::post('livechat/sendFile', 'LiveChatController@sendFile')->name('livechat.send.file');
Route::post('livechat/getUserList', 'LiveChatController@getUserList')->name('livechat.get.userlist');
Route::post('livechat/save-token', 'LiveChatController@saveToken')->name('livechat.save.token');
Route::post('livechat/check-new-chat', 'LiveChatController@checkNewChat')->name('livechat.new.chat');

Route::get('livechat/getLiveChats', 'LiveChatController@getLiveChats')->name('livechat.get.chats');

Route::get('livechat/getorderdetails', 'LiveChatController@getorderdetails')->name('livechat.getorderdetails');



Route::get('/brand-review', '\App\Http\Controllers\Api\v1\BrandReviewController@index');
Route::post('/brand-review/store', '\App\Http\Controllers\Api\v1\BrandReviewController@store')->name('brandreview.store');



Route::prefix('livechat')->group(function () {
    Route::post('/attach-image', 'LiveChatController@attachImage')->name('live-chat.attach.image');
});

/* ---------------------------------------------------------------------------------- */

Route::post('livechat/send-file', 'LiveChatController@sendFileToLiveChatInc')->name('livechat.upload.file');
Route::get('livechat/get-customer-info', 'LiveChatController@getLiveChatIncCustomer')->name('livechat.customer.info');
/*------------------------------------------- livechat tickets -------------------------------- */
Route::get('livechat/tickets', 'LiveChatController@tickets')->name('livechat.get.tickets');
Route::post('tickets/email-send', 'LiveChatController@sendEmail')->name('tickets.email.send');
Route::post('tickets/assign-ticket', 'LiveChatController@AssignTicket')->name('tickets.assign');
Route::post('tickets/add-ticket-status', 'LiveChatController@TicketStatus')->name('tickets.add.status');
Route::post('tickets/change-ticket-status', 'LiveChatController@ChangeStatus')->name('tickets.status.change');
Route::post('livechat/create-ticket', 'LiveChatController@createTickets')->name('livechat.create.ticket');
Route::get('livechat/get-tickets-data', 'LiveChatController@getTicketsData')->name('livechat.get.tickets.data');
Route::post('livechat/create-credit', 'LiveChatController@createCredits')->name('livechat.create.credit');
Route::get('livechat/get-credits-data', 'LiveChatController@getCreditsData')->name('livechat.get.credits.data');





Route::post('whatsapp/incoming', 'WhatsAppController@incomingMessage');
Route::post('whatsapp/incomingNew', 'WhatsAppController@incomingMessageNew');
Route::post('whatsapp/outgoingProcessed', 'WhatsAppController@outgoingProcessed');
Route::post('whatsapp/webhook', 'WhatsAppController@webhook');

Route::get('whatsapp/pullApiwha', 'WhatsAppController@pullApiwha');

Route::post('whatsapp/sendMessage/{context}', 'WhatsAppController@sendMessage')->name('whatsapp.send');
Route::post('whatsapp/sendMultipleMessages', 'WhatsAppController@sendMultipleMessages');
Route::post('whatsapp/approve/{context}', 'WhatsAppController@approveMessage');
Route::get('whatsapp/pollMessages/{context}', 'WhatsAppController@pollMessages');
Route::get('whatsapp/pollMessagesCustomer', 'WhatsAppController@pollMessagesCustomer');
Route::get('whatsapp/updatestatus/', 'WhatsAppController@updateStatus');
Route::post('whatsapp/updateAndCreate/', 'WhatsAppController@updateAndCreate');
Route::post('whatsapp/forwardMessage/', 'WhatsAppController@forwardMessage')->name('whatsapp.forward');
Route::post('whatsapp/{id}/fixMessageError', 'WhatsAppController@fixMessageError');
Route::post('whatsapp/{id}/resendMessage', 'WhatsAppController@resendMessage');
Route::get('message/resend', 'WhatsAppController@resendMessage2');
Route::get('message/delete', 'WhatsAppController@delete');

Route::post('list/autoCompleteMessages', 'WhatsAppController@autoCompleteMessages');




//});


Route::group(['middleware' => ['auth']], function () {
    Route::get('hubstaff/members', 'HubstaffController@index');
    Route::post('hubstaff/members/{id}/save-field', 'HubstaffController@saveMemberField');
    Route::post('hubstaff/linkuser', 'HubstaffController@linkUser');
    Route::get('hubstaff/projects', 'HubstaffController@getProjects');
    Route::post('hubstaff/projects/create', 'HubstaffController@createProject');
    Route::get('hubstaff/projects/{id}', 'HubstaffController@editProject');
    Route::put('hubstaff/projects/edit', 'HubstaffController@editProjectData');
    Route::get('hubstaff/tasks', 'HubstaffController@getTasks');
    Route::get('hubstaff/tasks/add', 'HubstaffController@addTaskFrom');
    Route::put('hubstaff/tasks/editData', 'HubstaffController@editTask');
    Route::post('hubstaff/tasks/addData', 'HubstaffController@addTask');
    Route::get('hubstaff/tasks/{id}', 'HubstaffController@editTaskForm');
    Route::get('hubstaff/redirect', 'HubstaffController@redirect');
    Route::get('hubstaff/debug', 'HubstaffController@debug');
    Route::get('hubstaff/payments', 'UserController@payments');
    Route::post('hubstaff/makePayment', 'UserController@makePayment');
});
/*
 * @date 1/13/2019
 * @author Rishabh Aryal
 * This is route for Instagram
 * feature in this ERP
 */

Route::middleware('auth')->group(function () {
    Route::get('cold-leads/delete', 'ColdLeadsController@deleteColdLead');
    Route::resource('cold-leads-broadcasts', 'ColdLeadBroadcastsController');
    Route::resource('cold-leads', 'ColdLeadsController');
});

Route::prefix('sitejabber')->middleware('auth')->group(function () {
    Route::post('sitejabber/attach-detach', 'SitejabberQAController@attachOrDetachReviews');
    Route::post('review/reply', 'SitejabberQAController@sendSitejabberQAReply');
    Route::get('review/{id}/confirm', 'SitejabberQAController@confirmReviewAsPosted');
    Route::get('review/{id}/delete', 'SitejabberQAController@detachBrandReviews');
    Route::get('review/{id}', 'SitejabberQAController@attachBrandReviews');
    Route::get('accounts', 'SitejabberQAController@accounts');
    Route::get('reviews', 'SitejabberQAController@reviews');
    Route::resource('qa', 'SitejabberQAController');
});

Route::prefix('pinterest')->middleware('auth')->group(function () {
    Route::resource('accounts', 'PinterestAccountAcontroller');
});

Route::prefix('database')->middleware('auth')->group(function () {
    Route::get('/', 'DatabaseController@index')->name("database.index");
    Route::get('/tables/{id}', 'DatabaseTableController@index')->name("database.tables");
    Route::post('/tables/view-lists', 'DatabaseTableController@viewList');
    Route::get('/states', 'DatabaseController@states')->name("database.states");
    Route::get('/process-list', 'DatabaseController@processList')->name("database.process.list");
    Route::get('/process-kill', 'DatabaseController@processKill')->name("database.process.kill");
});

Route::resource('pre-accounts', 'PreAccountController')->middleware('auth');

Route::middleware('auth')->group(function()
{
Route::get('instagram/get/hashtag/{word}', 'InstagramPostsController@hashtag');
Route::post('instagram/post/update-hashtag-post', 'InstagramPostsController@updateHashtagPost');
Route::post('instagram/post/update-hashtag-post', 'InstagramPostsController@updateHashtagPost');
Route::get('instagram/post/publish-post/{id}', 'InstagramPostsController@publishPost');
Route::get('instagram/post/getImages', 'InstagramPostsController@getImages');
Route::get('instagram/post/getCaptions', 'InstagramPostsController@getCaptions');
Route::post('instagram/post/multiple', 'InstagramPostsController@postMultiple');
Route::post('instagram/post/likeUserPost', 'InstagramPostsController@likeUserPost');
Route::post('instagram/post/acceptRequest', 'InstagramPostsController@acceptRequest');
Route::post('instagram/post/sendRequest', 'InstagramPostsController@sendRequest');
});


Route::post('instagram/history', 'InstagramPostsController@history')->name('instagram.accounts.histroy');


Route::prefix('instagram')->middleware('auth')->group(function () {



    Route::get('auto-comment-history', 'UsersAutoCommentHistoriesController@index');
    Route::get('auto-comment-history/assign', 'UsersAutoCommentHistoriesController@assignPosts');
    Route::get('auto-comment-history/send-posts', 'UsersAutoCommentHistoriesController@sendMessagesToWhatsappToScrap');
    Route::get('auto-comment-history/verify', 'UsersAutoCommentHistoriesController@verifyComment');
    Route::post('store', 'InstagramController@store');
    Route::get('{id}/edit', 'InstagramController@edit');
    Route::put('update/{id}', 'InstagramController@update');
    Route::get('delete/{id}', 'InstagramController@deleteAccount');
    Route::resource('auto-comment-report', 'AutoCommentHistoryController');
    Route::resource('auto-comment-hashtags', 'AutoReplyHashtagsController');
    Route::get('flag/{id}', 'HashtagController@flagMedia');
    Route::get('thread/{id}', 'ColdLeadsController@getMessageThread');
    Route::post('thread/{id}', 'ColdLeadsController@sendMessage');
    Route::resource('brand-tagged', 'BrandTaggedPostsController');
    Route::resource('auto-comments', 'InstagramAutoCommentsController');
    Route::post('media/comment', 'HashtagController@commentOnHashtag');
    Route::get('test/{id}', 'AccountController@test');
    Route::get('start-growth/{id}', 'AccountController@startAccountGrowth');
    Route::get('accounts', 'InstagramController@accounts');
    Route::get('notification', 'HashtagController@showNotification');
    Route::get('hashtag/markPriority', 'HashtagController@markPriority')->name('hashtag.priority');
    Route::resource('influencer', 'InfluencersController');
    Route::post('influencer-keyword', 'InfluencersController@saveKeyword')->name('influencers.keyword.save');
    Route::post('influencer-keyword-image', 'InfluencersController@getScraperImage')->name('influencers.image');
    Route::post('influencer-keyword-status', 'InfluencersController@checkScraper')->name('influencers.status');
    Route::post('influencer-keyword-start', 'InfluencersController@startScraper')->name('influencers.start');
    Route::post('influencer-keyword-log', 'InfluencersController@getLogFile')->name('influencers.log');
    Route::post('influencer-restart-script', 'InfluencersController@restartScript')->name('influencers.restart');
    Route::post('influencer-stop-script', 'InfluencersController@stopScript')->name('influencers.stop');

    Route::post('influencer-sort-data', 'InfluencersController@sortData')->name('influencers.sort');
    Route::resource('automated-reply', 'InstagramAutomatedMessagesController');
    Route::get('/', 'InstagramController@index');
    Route::get('comments/processed', 'HashtagController@showProcessedComments');
    Route::get('hashtag/post/comments/{mediaId}', 'HashtagController@loadComments');
    Route::post('leads/store', 'InstagramProfileController@add');
    Route::get('profiles/followers/{id}', 'InstagramProfileController@getFollowers');
    Route::resource('keyword', 'KeywordsController');
    Route::resource('profiles', 'InstagramProfileController');
    Route::get('posts', 'InstagramController@showPosts');
    Route::resource('hashtagposts', 'HashtagPostsController');
    Route::resource('hashtagpostscomments', 'HashtagPostCommentController');
    Route::get('hashtag/grid/{id}', 'HashtagController@showGrid')->name('hashtag.grid');
    Route::get('users/grid/{id}', 'HashtagController@showUserGrid')->name('users.grid');
    Route::get('hashtag/comments/{id?}', 'HashtagController@showGridComments')->name('hashtag.grid');
    Route::get('hashtag/users/{id?}', 'HashtagController@showGridUsers')->name('hashtag.users.grid');
    Route::resource('hashtag', 'HashtagController');
    Route::post('hashtag/process/queue', 'HashtagController@rumCommand')->name('hashtag.command');
    Route::post('hashtag/queue/kill', 'HashtagController@killCommand')->name('hashtag.command.kill');
    Route::post('hashtag/queue/status', 'HashtagController@checkStatusCommand')->name('hashtag.command.status');
    Route::get('hashtags/grid', 'InstagramController@hashtagGrid');
    Route::get('influencers', 'HashtagController@influencer')->name('influencers.index');
    Route::post('influencers/history', 'HashtagController@history')->name('influencers.index.history');
    Route::post('influencers/reply/add', 'HashtagController@addReply')->name('influencers.reply.add');
    Route::post('influencers/reply/delete', 'HashtagController@deleteReply')->name('influencers.reply.delete');

    

    Route::get('comments', 'InstagramController@getComments');
    Route::post('comments', 'InstagramController@postComment');
    Route::get('post-media', 'InstagramController@showImagesToBePosted');
    Route::post('post-media', 'InstagramController@postMedia');
    Route::get('post-media-now/{schedule}', 'InstagramController@postMediaNow');
    Route::get('delete-schedule/{schedule}', 'InstagramController@cancelSchedule');
    Route::get('media/schedules', 'InstagramController@showSchedules');
    Route::post('media/schedules', 'InstagramController@postSchedules');
    Route::get('scheduled/events', 'InstagramController@getScheduledEvents');
    Route::get('schedule/{scheduleId}', 'InstagramController@editSchedule');
    Route::post('schedule/{scheduleId}', 'InstagramController@updateSchedule');
    Route::post('schedule/{scheduleId}/attach', 'InstagramController@attachMedia');

    Route::get('direct-message', 'ColdLeadsController@home');

    // Media manager
    Route::get('media', 'MediaController@index')->name('media.index');
    Route::post('media', 'MediaController@upload')->name('media.upload');
    Route::get('media/files', 'MediaController@files')->name('media.files');
    Route::delete('media', 'MediaController@delete')->name('media.delete');

    //Add Post
    Route::get('post/create', 'InstagramPostsController@post')->name('instagram.post');
     Route::any('post/create/images', 'InstagramPostsController@post')->name('instagram.post.images');

    Route::get('post', 'InstagramPostsController@viewPost')->name('post.index');
    Route::get('post/edit', 'InstagramPostsController@editPost')->name('post.edit');
    Route::post('post/create','InstagramPostsController@createPost')->name('post.store');


     Route::get('users', 'InstagramPostsController@users')->name('instagram.users');
     Route::post('users/save', 'InstagramController@addUserForPost')->name('instagram.users.add');
     Route::get('users/{id}', 'InstagramPostsController@userPost')->name('instagram.users.post');


     //direct message new
     Route::get('direct', 'DirectMessageController@index')->name('direct.index');
    //  Route::get('direct', 'DirectMessageController@incomingPendingRead')->name('direct.index');
     Route::post('direct/send', 'DirectMessageController@sendMessage')->name('direct.send');
     Route::post('direct/sendImage', 'DirectMessageController@sendImage')->name('direct.send.file');
     Route::post('direct/newChats', 'DirectMessageController@incomingPendingRead')->name('direct.new.chats');
     Route::post('direct/group-message', 'DirectMessageController@sendMessageMultiple')->name('direct.group-message');
     Route::post('direct/send-message', 'DirectMessageController@prepareAndSendMessage')->name('direct.send-message');
     Route::post('direct/latest-posts', 'DirectMessageController@latestPosts')->name('direct.latest-posts');
     Route::post('direct/messages', 'DirectMessageController@messages')->name('direct.messages');
     Route::post('direct/history', 'DirectMessageController@history')->name('direct.history');
     Route::post('direct/infulencers-messages', 'DirectMessageController@influencerMessages')->name('direct.infulencers-messages');

});

// logScraperVsAiController
Route::prefix('log-scraper-vs-ai')->middleware('auth')->group(function () {
    Route::match(['get', 'post'], '/{id}', 'logScraperVsAiController@index');
});

Route::prefix('social-media')->middleware('auth')->group(function () {
    Route::get('/instagram-posts/grid','InstagramPostsController@grid');
    Route::get('/instagram-posts', 'InstagramPostsController@index');
});

/*
 * @date 1/17/2019
 * @author Rishabh Aryal
 * This is route API for getting/replying comments
 * from Facebook API
 */

Route::prefix('facebook')->middleware('auth')->group(function () {
    Route::get('/influencers', 'ScrappedFacebookUserController@index');
});

Route::prefix('comments')->middleware('auth')->group(function () {
    Route::get('/facebook', 'SocialController@getComments');
    Route::post('/facebook', 'SocialController@postComment');
});

Route::prefix('scrap')->middleware('auth')->group(function () {
    Route::get('screenshot', 'ScrapStatisticsController@getScreenShot');
    Route::get('get-last-errors', 'ScrapStatisticsController@getLastErrors');
    Route::get('log-details', 'ScrapStatisticsController@logDetails')->name('scrap.log-details');
    Route::get('server-status-history', 'ScrapStatisticsController@serverStatusHistory');
    Route::get('server-status-process', 'ScrapStatisticsController@serverStatusProcess');
    Route::get('get-server-scraper-timing', 'ScrapStatisticsController@getScraperServerTiming');
    Route::get('position-history', 'ScrapStatisticsController@positionHistory');
    Route::post('position-history-download', 'ScrapStatisticsController@positionHistorydownload');//Purpose : Download  Position History Route - DEVTASK-4086
    Route::get('statistics/update-field', 'ScrapStatisticsController@updateField');
    Route::get('statistics/update-scrap-field', 'ScrapStatisticsController@updateScrapperField');
    Route::get('statistics/show-history', 'ScrapStatisticsController@showHistory');
    Route::post('statistics/update-priority', 'ScrapStatisticsController@updatePriority');
    Route::get('statistics/history', 'ScrapStatisticsController@getHistory');
    Route::post('statistics/reply/add', 'ScrapStatisticsController@addReply');
    Route::post('statistics/reply/delete', 'ScrapStatisticsController@deleteReply');
    Route::get('statistics/server-history', 'ScrapStatisticsController@serverHistory');
    Route::get('statistics/server-history/close-job', 'ScrapStatisticsController@endJob')->name("statistics.server-history.close-job");
    Route::get('quick-statistics', 'ScrapStatisticsController@quickView')->name("statistics.quick");
    Route::resource('statistics', 'ScrapStatisticsController');
    Route::get('getremark', 'ScrapStatisticsController@getRemark')->name('scrap.getremark');
    Route::get('latest-remark', 'ScrapStatisticsController@getLastRemark')->name('scrap.latest-remark');
    Route::get('auto-restart', 'ScrapStatisticsController@autoRestart')->name('scrap.auto-restart');
    Route::post('position-all', 'ScrapStatisticsController@positionAll')->name('scrap.position-all');
    Route::post('addremark', 'ScrapStatisticsController@addRemark')->name('scrap.addRemark');
    Route::post('scrap/add/note', 'ScrapStatisticsController@addNote')->name('scrap/add/note');
    Route::get('facebook/inbox', 'FacebookController@getInbox');
    Route::resource('facebook', 'FacebookController');
    Route::get('gmails/{id}', 'GmailDataController@show');
    Route::resource('gmail', 'GmailDataController');
    Route::resource('designer', 'DesignerController');
    Route::resource('sales', 'SalesItemController');
    Route::get('/dubbizle', 'DubbizleController@index');
    Route::post('/dubbizle/set-reminder', 'DubbizleController@updateReminder');
    Route::post('/dubbizle/bulkWhatsapp', 'DubbizleController@bulkWhatsapp')->name('dubbizle.bulk.whatsapp');
    Route::get('/dubbizle/{id}/edit', 'DubbizleController@edit');
    Route::put('/dubbizle/{id}', 'DubbizleController@update');
    Route::get('/dubbizle/{id}', 'DubbizleController@show')->name('dubbizle.show');
    Route::get('/products', 'ScrapController@showProductStat');
    Route::get('/products/auto-rejected-stat', 'ProductController@showAutoRejectedProducts');
    Route::get('/activity', 'ScrapController@activity')->name('scrap.activity');
    Route::get('/excel', 'ScrapController@excel_import');
    Route::post('/excel', 'ScrapController@excel_store');
    Route::get('/google/images', 'ScrapController@index');
    Route::post('/google/images', 'ScrapController@scrapGoogleImages');
    Route::post('/google/images/download', 'ScrapController@downloadImages');
    Route::get('/scraped-urls', 'ScrapController@scrapedUrls');
    Route::get('/generic-scraper', 'ScrapController@genericScraper');
    Route::post('/generic-scraper/save', 'ScrapController@genericScraperSave')->name('generic.save.scraper');
    Route::post('/generic-scraper/full-scrape', 'ScrapController@scraperFullScrape')->name('generic.full-scrape');
    Route::get('/generic-scraper/mapping/{id}', 'ScrapController@genericMapping')->name('generic.mapping');
    Route::post('/generic-scraper/mapping/save', 'ScrapController@genericMappingSave')->name('generic.mapping.save');
    Route::post('/generic-scraper/mapping/delete', 'ScrapController@genericMappingDelete')->name('generic.mapping.delete');

    Route::post('/scraper/saveChildScraper', 'ScrapController@saveChildScraper')->name('save.childrenScraper');
    Route::get('/server-statistics', 'ScrapStatisticsController@serverStatistics')->name('scrap.scrap_server_status');
    Route::get('/server-statistics/history/{scrap_name}', 'ScrapStatisticsController@serverStatisticsHistory')->name('scrap.scrap_server_history');
    Route::get('/task-list', 'ScrapStatisticsController@taskList')->name('scrap.task-list');
    Route::post('/{id}/create', 'ScrapStatisticsController@taskCreate')->name('scrap.task-list.create');

    Route::get('scrap-brand', 'BrandController@scrap_brand')->name('scrap-brand');

    Route::get('/{name}', 'ScrapController@showProducts')->name('show.logFile');
    Route::post('/scrap/assignTask', 'ScrapController@assignScrapProductTask')->name('scrap.assignTask');

    Route::get('servers/statistics','ScrapController@getServerStatistics')->name('scrap.servers.statistics');
 
});

Route::resource('quick-reply', 'QuickReplyController')->middleware('auth');
Route::resource('social-tags', 'SocialTagsController')->middleware('auth');


Route::get('test', 'WhatsAppController@getAllMessages');

Route::middleware('auth')->group(function()
{
    Route::resource('track', 'UserActionsController');
Route::get('competitor-page/hide/{id}', 'CompetitorPageController@hideLead');
Route::get('competitor-page/approve/{id}', 'CompetitorPageController@approveLead');
Route::resource('competitor-page', 'CompetitorPageController');
Route::resource('target-location', 'TargetLocationController');

});



//Legal Module
Route::middleware('auth')->group(function () {
    Route::post('lawyer-speciality', ['uses' => 'LawyerController@storeSpeciality', 'as' => 'lawyer.speciality.store']);
    Route::resource('lawyer', 'LawyerController');
    Route::get('case/{case}/receivable', 'CaseReceivableController@index')->name('case.receivable');
    Route::post('case/{case}/receivable', 'CaseReceivableController@store')->name('case.receivable.store');
    Route::put('case/{case}/receivable/{case_receivable}', 'CaseReceivableController@update')->name('case.receivable.update');
    Route::delete('case/{case}/receivable/{case_receivable}', 'CaseReceivableController@destroy')->name('case.receivable.destroy');
    Route::resource('case', 'CaseController');
    Route::get('case-costs/{case}', ['uses' => 'CaseController@getCosts', 'as' => 'case.cost']);
    Route::post('case-costs', ['uses' => 'CaseController@costStore', 'as' => 'case.cost.post']);
    Route::put('case-costs/update/{case_cost}', ['uses' => 'CaseController@costUpdate', 'as' => 'case.cost.update']);
});

Route::middleware('auth')->resource('keyword-instruction', 'KeywordInstructionController')->except(['create']);


Route::prefix('/seo')->middleware('auth')->name('seo.')->group(function () {
    Route::get('/analytics', 'SEOAnalyticsController@show')->name('analytics');
    Route::get('/analytics/filter', 'SEOAnalyticsController@filter')->name('analytics.filter');
    Route::post('/analytics/filter', 'SEOAnalyticsController@filter')->name('analytics.filter');
    Route::post('/analytics/delete/{id}', 'SEOAnalyticsController@delete')->name('delete_entry');
});

Route::get('display/broken-link-details', 'BrokenLinkCheckerController@displayBrokenLinkDetails')->name('brokenLinks');
//Route::get('display/broken-link-details', 'BrokenLinkCheckerController@displayBrokenLinkDetails')->name('filteredResults');

Route::middleware('auth')->group(function () {
    Route::get('display/broken-link-details', 'BrokenLinkCheckerController@displayBrokenLinkDetails')->name('filteredResults');

    Route::get('old-incomings', 'OldIncomingController@index')->name('oldIncomings');
    Route::get('old-incomings', 'OldIncomingController@index')->name('filteredOldIncomings');
    Route::post('store/old-incomings', 'OldIncomingController@store')->name('storeOldIncomings');
    Route::get('edit/old-incomings/{id}', 'OldIncomingController@edit')->name('editOldIncomings');
    Route::post('update/old-incomings/{id}', 'OldIncomingController@update')->name('updateOldIncomings');

    // Old Module
    Route::post('old/send/emailBulk', 'OldController@sendEmailBulk')->name('old.email.send.bulk');
    Route::post('old/send/email', 'OldController@sendEmail')->name('old.email.send');
    Route::get('old/gettaskremark', 'OldController@getTaskRemark')->name('old.gettaskremark');
    Route::post('old/addremark', 'OldController@addRemark')->name('old.addRemark');
    Route::get('old/email/inbox', 'OldController@emailInbox')->name('old.email.inbox');
    Route::get('old/{old}/payments', 'OldController@paymentindex')->name('old.payments');
    Route::post('old/{old}/payments', 'OldController@paymentStore')->name('old.payments.store');
    Route::put('old/{old}/payments/{old_payment}', 'OldController@paymentUpdate')->name('old.payments.update');
    Route::delete('old/{old}/payments/{old_payment}', 'OldController@paymentDestroy')->name('old.payments.destroy');
    Route::resource('old', 'OldController');
    Route::post('old/block', 'OldController@block')->name('old.block');
    Route::post('old/category/create', 'OldController@createCategory')->name('old.category.create');
    Route::post('old/update/status', 'OldController@updateOld')->name('old.update.status');

    //Simple Duty

    //Simple duty category
    Route::get('duty/category', 'SimplyDutyCategoryController@index')->name('simplyduty.category.index');
    Route::get('duty/category/update', 'SimplyDutyCategoryController@getCategoryFromApi')->name('simplyduty.category.update');

    Route::get('duty/hscode', 'HsCodeController@index')->name('simplyduty.hscode.index');

    Route::post('duty/setting', 'HsCodeController@saveKey')->name('simplyduty.hscode.key');


    //Simple Duty Currency
    Route::get('duty/currency', 'SimplyDutyCurrencyController@index')->name('simplyduty.currency.index');
    Route::get('duty/currency/update', 'SimplyDutyCurrencyController@getCurrencyFromApi')->name('simplyduty.currency.update');

    //Simple Duty Country
    Route::get('duty/country', 'SimplyDutyCountryController@index')->name('simplyduty.country.index');
    Route::get('duty/country/update', 'SimplyDutyCountryController@getCountryFromApi')->name('simplyduty.country.update');
    Route::get('duty/country/updateduty', 'SimplyDutyCountryController@updateduty')->name('simplyduty.country.updateduty');

    //Simple Duty Calculation
    Route::get('duty/calculation', 'SimplyDutyCalculationController@index')->name('simplyduty.calculation.index');
    Route::post('duty/calculation', 'SimplyDutyCalculationController@calculation')->name('simplyduty.calculation');

    //Simply Duty Common
    Route::get('hscode/most-common', 'HsCodeController@mostCommon')->name('hscode.mostcommon.index');

    //Simply Duty Common
    Route::get('hscode/most-common-category', 'HsCodeController@mostCommonByCategory')->name('hscode.mostcommon.category');

    Route::get('display/analytics-data', 'AnalyticsController@showData')->name('showAnalytics');
    Route::post('display/analytics-history', 'AnalyticsController@history')->name('analytics.history');

    Route::get('display/back-link-details', 'BackLinkController@displayBackLinkDetails')->name('backLinkFilteredResults');
    Route::get('links-to-post', 'SEOAnalyticsController@linksToPost');

    Route::prefix('country-duty')->group(function () {
        Route::get('/', 'CountryDutyController@index')->name('country.duty.index');
        Route::post('/search', 'CountryDutyController@search')->name('country.duty.search');
        Route::post('/save-country-group', 'CountryDutyController@saveCountryGroup')->name('country.duty.search');
        Route::prefix('list')->group(function () {
            Route::get('/', 'CountryDutyController@list')->name('country.duty.list');
            Route::get('/records', 'CountryDutyController@records')->name('country.duty.records');
            Route::post('save', 'CountryDutyController@store')->name('country.duty.save');
            Route::post('update-group-field', 'CountryDutyController@updateGroupField')->name('country.duty.update-group-field');
            Route::prefix('{id}')->group(function () {
                Route::get('edit', 'CountryDutyController@edit')->name('country.duty.edit');
                Route::get('delete', 'CountryDutyController@delete')->name('country.duty.delete');
            });
        });
    });
});

//Blogger Module
Route::middleware('auth')->group(function () {

    Route::get('blogger-email', ['uses' => 'BloggerEmailTemplateController@index', 'as' => 'blogger.email.template']);
    Route::put('blogger-email/{bloggerEmailTemplate}', ['uses' => 'BloggerEmailTemplateController@update', 'as' => 'blogger.email.template.update']);

    Route::get('blogger/{blogger}/payments', 'BloggerPaymentController@index')->name('blogger.payments');
    Route::post('blogger/{blogger}/payments', 'BloggerPaymentController@store')->name('blogger.payments.store');
    Route::put('blogger/{blogger}/payments/{blogger_payment}', 'BloggerPaymentController@update')->name('blogger.payments.update');
    Route::delete('blogger/{blogger}/payments/{blogger_payment}', 'BloggerPaymentController@destroy')->name('blogger.payments.destroy');

    Route::resource('blogger', 'BloggerController');

    Route::post('blogger-contact', ['uses' => 'ContactBloggerController@store', 'as' => 'blogger.contact.store']);
    Route::put('blogger-contact/{contact_blogger}', ['uses' => 'ContactBloggerController@update', 'as' => 'blogger.contact.update']);
    Route::delete('blogger-contact/{contact_blogger}', ['uses' => 'ContactBloggerController@destroy', 'as' => 'contact.blogger.destroy']);


    Route::get('display/back-link-details', 'BackLinkController@displayBackLinkDetails')->name('backLinks');
    Route::get('display/back-link-details', 'BackLinkController@displayBackLinkDetails')->name('backLinkFilteredResults');
    Route::post('blogger-product-image/{blogger_product}', ['uses' => 'BloggerProductController@uploadImages', 'as' => 'blogger.image.upload']);
    Route::get('blogger-product-get-image/{blogger_product}', ['uses' => 'BloggerProductController@getImages', 'as' => 'blogger.image']);
    Route::resource('blogger-product', 'BloggerProductController');
});


//Monetary Account Module
Route::middleware('auth')->group(function () {
    Route::resource('monetary-account', 'MonetaryAccountController');
});

// Mailchimp Module
Route::group(['middleware' => 'auth', 'namespace' => 'Mail'], function () {
    Route::get('manageMailChimp', 'MailchimpController@manageMailChimp')->name('manage.mailchimp');
    Route::post('subscribe', ['as' => 'subscribe', 'uses' => 'MailchimpController@subscribe']);
    Route::post('sendCompaign', ['as' => 'sendCompaign', 'uses' => 'MailchimpController@sendCompaign']);
    Route::get('make-active-subscribers', 'MailchimpController@makeActiveSubscriber')->name('make.active.subscriber');
});


Route::group(['middleware' => 'auth', 'namespace' => 'marketing'], function () {
    Route::get('test', function () {
        return 'hello';
    });
});

//Hubstaff Module
Route::group(['middleware' => 'auth', 'namespace' => 'Hubstaff'], function () {

    Route::get('v1/auth', 'HubstaffController@authenticationPage')->name('get.token');

    Route::post('user-details-token', 'HubstaffController@getToken')->name('user.token');

    Route::get('get-users', 'HubstaffController@gettingUsersPage')->name('get.users');

    Route::post('v1/users', 'HubstaffController@userDetails')->name('get.users.api');

    Route::get('get-user-from-id', 'HubstaffController@showFormUserById')->name('get.user-fromid');

    Route::post('get-user-from-id', 'HubstaffController@getUserById')->name('post.user-fromid');

    Route::get('v1/users/projects', 'HubstaffController@getProjectPage')->name('get.user-project-page');

    Route::post('v1/users/projects', 'HubstaffController@getProjects')->name('post.user-project-page');

    // ------------Projects---------------

    Route::get('get-projects', 'HubstaffController@getUserProject')->name('user.project');
    Route::post('get-projects', 'HubstaffController@postUserProject')->name('post.user-project');

    // --------------Tasks---------------

    Route::get('get-project-tasks', 'HubstaffController@getProjectTask')->name('project.task');
    Route::post('get-project-taks', 'HubstaffController@postProjectTask')->name('post.project-task');


    Route::get('v1/tasks', 'HubstaffController@getTaskFromId')->name('get-project.task-from-id');

    Route::post('v1/tasks', 'HubstaffController@postTaskFromId')->name('post-project.task-from-id');

    // --------------Organizaitons--------------
    Route::get('v1/organizations', 'HubstaffController@index')->name('organizations');
    Route::post('v1/organizations', 'HubstaffController@getOrganization')->name('post.organizations');


    // -------v2 preview verion post requests----------
    //    Route::get('v2/organizations/projects', 'HubstaffProjectController@getProject');
    //    Route::post('v2/organizations/projects', 'HubstaffProjectController@postProject');


    Route::get('v1/organization/members', 'HubstaffController@organizationMemberPage')->name('organization.members');
    Route::post('v1/organization/members', 'HubstaffController@showMembers')->name('post.organization-member');

    // --------------Screenshots--------------

    Route::get('v1/screenshots', 'HubstaffController@getScreenshotPage')->name('get.screenshots');

    Route::post('v1/screenshots', 'HubstaffController@postScreenshots')->name('post.screenshot');

    // -------------payments----------------

    Route::get('v1/team_payments', 'HubstaffController@getTeamPaymentPage')->name('team.payments');
    Route::post('v1/team_payments', 'HubstaffController@getPaymentDetail')->name('post.payment-page');


    // ------------Attendance---------------
    Route::get('v2/organizations/attendance-shifts', 'AttendanceController@index')->name('attendance.shifts');

    Route::post('v2/organizations/attendance-shifts', 'AttendanceController@show')->name('attendance.shifts-post');
});

Route::middleware('auth')->group(function()
{
    Route::get('display/analytics-data', 'AnalyticsController@showData')->name('showAnalytics');
Route::get('display/analytics-data', 'AnalyticsController@showData')->name('filteredAnalyticsResults');
Route::get('display/analytics-summary', 'AnalyticsController@analyticsDataSummary')->name('analyticsDataSummary');
Route::get('display/analytics-summary', 'AnalyticsController@analyticsDataSummary')->name('filteredAnalyticsSummary');
Route::get('display/analytics-customer-behaviour', 'AnalyticsController@customerBehaviourByPage')->name('customerBehaviourByPage');
Route::get('display/analytics-customer-behaviour', 'AnalyticsController@customerBehaviourByPage')->name('filteredcustomerBehaviourByPage');
});


Route::middleware('auth')->group(function()
{
    // Broken Links
Route::post('back-link/{id}/updateDomain', 'BrokenLinkCheckerController@updateDomain');
Route::post('back-link/{id}/updateTitle', 'BrokenLinkCheckerController@updateTitle');

// Article Links


Route::get('display/articles', 'ArticleController@index')->name('articleApproval');
Route::post('article/{id}/updateTitle', 'ArticleController@updateTitle');
Route::post('article/{id}/updateDescription', 'ArticleController@updateDescription');

//Back Linking
Route::post('back-linking/{id}/updateTitle', 'BackLinkController@updateTitle');
Route::post('back-linking/{id}/updateDesc', 'BackLinkController@updateDesc');
Route::post('back-linking/{id}/updateURL', 'BackLinkController@updateURL');

//SE Ranking Links
Route::get('se-ranking/sites', 'SERankingController@getSites')->name('getSites');
Route::get('se-ranking/keywords', 'SERankingController@getKeyWords')->name('getKeyWords');
Route::get('se-ranking/keywords', 'SERankingController@getKeyWords')->name('filteredSERankKeywords');
Route::get('se-ranking/competitors', 'SERankingController@getCompetitors')->name('getCompetitors');
Route::get('se-ranking/analytics', 'SERankingController@getAnalytics')->name('getAnalytics');
Route::get('se-ranking/backlinks', 'SERankingController@getBacklinks')->name('getBacklinks');
Route::get('se-ranking/research-data', 'SERankingController@getResearchData')->name('getResearchData');
Route::get('se-ranking/audit', 'SERankingController@getSiteAudit')->name('getSiteAudit');
Route::get('se-ranking/competitors/keyword-positions/{id}', 'SERankingController@getCompetitors')->name('getCompetitorsKeywordPos');
//Dev Task Planner Route
Route::get('dev-task-planner', 'NewDevTaskController@index')->name('newDevTaskPlanner');
Route::get('dev-task-planner', 'NewDevTaskController@index')->name('filteredNewDevTaskPlanner');
//Supplier scrapping info
Route::get('supplier-scrapping-info', 'ProductController@getSupplierScrappingInfo')->name('getSupplierScrappingInfo');

});


Route::group(['middleware' => 'auth', 'admin'], function () {
    Route::get('category/brand/min-max-pricing', 'CategoryController@brandMinMaxPricing');
    Route::get('category/brand/min-max-pricing-update-default', 'CategoryController@updateMinMaxPriceDefault');
    Route::post('category/brand/update-min-max-pricing', 'CategoryController@updateBrandMinMaxPricing');

    Route::post('task/change/status','TaskModuleController@updateStatus')->name('task.change.status');

    Route::post('task/status/create','TaskModuleController@createStatus')->name('task.status.create');
});

// pages notes started from here
Route::group(['middleware' => 'auth'], function () {
    Route::prefix('page-notes')->group(function () {
        Route::post('create', 'PageNotesController@create')->name('createPageNote');
        Route::get('list', 'PageNotesController@list')->name('listPageNote');
        Route::get('edit', 'PageNotesController@edit')->name('editPageNote');
        Route::post('update', 'PageNotesController@update')->name('updatePageNote');
        Route::get('delete', 'PageNotesController@delete')->name('deletePageNote');
        Route::get('records', 'PageNotesController@records')->name('pageNotesRecords');
        Route::get('/', 'PageNotesController@index')->name('pageNotes.viewList');
    });
    Route::prefix('instruction-notes')->group(function () {
        Route::post('create', 'PageNotesController@instructionCreate')->name('instructionCreate');
    });

    Route::post('notesCreate', 'PageNotesController@notesCreate')->name('notesCreate');//Purpose : Create Route for Insert Note - DEVTASK-4289
});

Route::group(['middleware' => 'auth', 'namespace' => 'Marketing', 'prefix' => 'marketing'], function () {
    // Whats App Config
    Route::get('whatsapp-config', 'WhatsappConfigController@index')->name('whatsapp.config.index');
    Route::get('whatsapp-history/{id}', 'WhatsappConfigController@history')->name('whatsapp.config.history');
    Route::post('whatsapp-config/store', 'WhatsappConfigController@store')->name('whatsapp.config.store');
    Route::post('whatsapp-config/edit', 'WhatsappConfigController@edit')->name('whatsapp.config.edit');
    Route::post('whatsapp-config/delete', 'WhatsappConfigController@destroy')->name('whatsapp.config.delete');
    Route::get('whatsapp-queue/{id}', 'WhatsappConfigController@queue')->name('whatsapp.config.queue');
    Route::post('whatsapp-queue/delete', 'WhatsappConfigController@destroyQueue')->name('whatsapp.config.delete_queue');
    Route::post('whatsapp-queue/delete_all/', 'WhatsappConfigController@destroyQueueAll')->name('whatsapp.config.delete_all');
    Route::get('whatsapp-queue/delete_queues/{id}', 'WhatsappConfigController@clearMessagesQueue')->name('whatsapp.config.delete_all_queues');
    Route::get('whatsapp-config/get-barcode', 'WhatsappConfigController@getBarcode')->name('whatsapp.config.barcode');
    Route::get('whatsapp-config/get-screen', 'WhatsappConfigController@getScreen')->name('whatsapp.config.screen');
    Route::get('whatsapp-config/delete-chrome', 'WhatsappConfigController@deleteChromeData')->name('whatsapp.config.delete-chrome');
    Route::get('whatsapp-config/restart-script', 'WhatsappConfigController@restartScript')->name('whatsapp.restart.script');
    Route::get('whatsapp-config/logout-script', 'WhatsappConfigController@logoutScript')->name('whatsapp.restart.logout-script');
    Route::get('whatsapp-config/get-status', 'WhatsappConfigController@getStatus')->name('whatsapp.restart.get-status');
    Route::get('whatsapp-config/get-status-info', 'WhatsappConfigController@getStatusInfo')->name('whatsapp.restart.get-status-info');

    Route::get('whatsapp-config/blocked-number', 'WhatsappConfigController@blockedNumber')->name('whatsapp.block.number');

    Route::post('whatsapp-queue/switchBroadcast', 'BroadcastController@switchBroadcast')->name('whatsapp.config.switchBroadcast');

    //Instagram Config

    // Whats App Config
    Route::get('instagram-config', 'InstagramConfigController@index')->name('instagram.config.index');
    Route::get('instagram-history/{id}', 'InstagramConfigController@history')->name('instagram.config.history');
    Route::post('instagram-config/store', 'InstagramConfigController@store')->name('instagram.config.store');
    Route::post('instagram-config/edit', 'InstagramConfigController@edit')->name('instagram.config.edit');
    Route::post('instagram-config/delete', 'InstagramConfigController@destroy')->name('instagram.config.delete');
    Route::get('instagram-queue/{id}', 'InstagramConfigController@queue')->name('instagram.config.queue');
    Route::post('instagram-queue/delete', 'InstagramConfigController@destroyQueue')->name('instagram.config.delete_queue');
    Route::post('instagram-queue/delete_all/', 'InstagramConfigController@destroyQueueAll')->name('instagram.config.delete_all');

    //Social Config
    Route::get('accounts/{type?}', 'AccountController@index')->name('accounts.index');
    Route::post('accounts', 'AccountController@store')->name('accounts.store');
    Route::post('accounts/edit', 'AccountController@edit')->name('accounts.edit');
    Route::post('accounts/broadcast', 'AccountController@broadcast')->name('accounts.broadcast');



    Route::get('instagram-queue/delete_queues/{id}', 'InstagramConfigController@clearMessagesQueue')->name('instagram.config.delete_all_queues');
    Route::get('instagram-config/get-barcode', 'InstagramConfigController@getBarcode')->name('instagram.config.barcode');
    Route::get('instagram-config/get-screen', 'InstagramConfigController@getScreen')->name('instagram.config.screen');
    Route::get('instagram-config/delete-chrome', 'InstagramConfigController@deleteChromeData')->name('instagram.config.delete');
    Route::get('instagram-config/restart-script', 'InstagramConfigController@restartScript')->name('instagram.restart.script');
    Route::get('instagram-config/blocked-number', 'InstagramConfigController@blockedNumber')->name('instagram.block.number');


    // Route::post('whatsapp-queue/switchBroadcast', 'BroadcastController@switchBroadcast')->name('whatsapp.config.switchBroadcast');

    // Marketing Platform
    Route::get('platforms', 'MarketingPlatformController@index')->name('platforms.index');
    Route::post('platforms/store', 'MarketingPlatformController@store')->name('platforms.store');
    Route::post('platforms/edit', 'MarketingPlatformController@edit')->name('platforms.edit');
    Route::post('platforms/delete', 'MarketingPlatformController@destroy')->name('platforms.delete');

    Route::get('broadcast', 'BroadcastController@index')->name('broadcasts.index');
    Route::get('broadcast/dnd', 'BroadcastController@addToDND')->name('broadcast.add.dnd');
    Route::get('broadcast/gettaskremark', 'BroadcastController@getBroadCastRemark')->name('broadcast.gets.remark');
    Route::post('broadcast/addremark', 'BroadcastController@addRemark')->name('broadcast.add.remark');
    Route::get('broadcast/manual', 'BroadcastController@addManual')->name('broadcast.add.manual');
    Route::post('broadcast/update', 'BroadcastController@updateWhatsAppNumber')->name('broadcast.update.whatsappnumber');
    Route::get('broadcast/sendMessage/list', 'BroadcastController@broadCastSendMessage')->name('broadcast.message.send.list');
    Route::post('broadcast/customer/list', 'BroadcastController@getCustomerBroadcastList')->name('broadcast.customer.list');
    Route::post('broadcast/global/save', 'BroadcastController@saveGlobalValues')->name('broadcast.global.save');
    Route::post('broadcast/enable/count', 'BroadcastController@getCustomerCountEnable')->name('broadcast.enable.count');
    Route::get('broadcast/sendMessage/list', 'BroadcastController@broadCastSendMessage')->name('broadcast.message.send.list');
    Route::post('broadcast/customer/list', 'BroadcastController@getCustomerBroadcastList')->name('broadcast.customer.list');
    Route::post('broadcast/global/save', 'BroadcastController@saveGlobalValues')->name('broadcast.global.save');
    Route::post('broadcast/enable/count', 'BroadcastController@getCustomerCountEnable')->name('broadcast.enable.count');

    Route::get('instagram-broadcast', 'BroadcastController@instagram');

    Route::get('facebook-broadcast', 'BroadcastController@facebook');

    Route::get('mailinglist', 'MailinglistController@index')->name('mailingList');
    Route::get('mailinglist/{id}', 'MailinglistController@show')->name('mailingList.single');

    Route::get('mailinglist/edit/{id}', 'MailinglistController@edit')->name('mailingList.edit');
    Route::post('mailinglist/update/{id}', 'MailinglistController@update')->name('mailingList.update');

    Route::get('mailinglist/add/{id}/{email}', 'MailinglistController@addToList')->name('mailingList.add_to_list');
    Route::get('mailinglist/delete/{id}/{email}', 'MailinglistController@delete')->name('mailingList.delete');
    Route::get('mailinglist/list/delete/{id}', 'MailinglistController@deleteList')->name('mailingList.delete.list');
    Route::post('mailinglist/create', 'MailinglistController@create')->name('mailingList.create');
    Route::get('mailinglist-add-manual', 'MailinglistController@addManual')->name('mailinglist.add.manual');
    Route::post('addRemark', 'MailinglistController@addRemark')->name('mailingList.addRemark');
    Route::get('gettaskremark', 'MailinglistController@getBroadCastRemark')->name('mailingList.gets.remark');
    Route::post('mailinglist/customer/{id}/source', 'MailinglistController@updateCustomerSource')->name('mailingList.customer.source');


    //Email Leads
    Route::get('emailleads', 'EmailLeadsController@index')->name('emailleads');
    Route::any('emailleads/import', 'EmailLeadsController@import')->name('emailleads.import');
    Route::post('emailleads/assign', 'EmailLeadsController@assignList')->name('emailleads.assign');
    Route::get('emailleads/export', 'EmailLeadsController@export')->name('emailleads.export');
    Route::get('emailleads/show/{id}', 'EmailLeadsController@show')->name('emailleads.show');
    Route::get('emailleads/unsubscribe/{lead_id}/{lead_list_id}', 'EmailLeadsController@unsubscribe')->name('emailleads.unsubscribe');

    Route::get('services', 'ServiceController@index')->name('services');
    Route::post('services/store', 'ServiceController@store')->name('services.store');
    Route::post('services/destroy', 'ServiceController@destroy')->name('services.destroy');
    Route::post('services/update', 'ServiceController@update')->name('services.update');

    Route::get('mailinglist-templates', 'MailinglistTemplateController@index')->name('mailingList-template');
    Route::get('mailinglist-ajax', 'MailinglistTemplateController@ajax');
    Route::post('mailinglist-templates/store', 'MailinglistTemplateController@store')->name('mailingList-template.store');
    Route::post('mailinglist-templates/category/store', 'MailinglistTemplateCategoryController@store')->name('mailingList.category.store');


    Route::group(['prefix' => 'mailinglist-templates/{id}'], function () {
        Route::get('delete', 'MailinglistTemplateController@delete')->name('mailingList-template.delete');
    });


    Route::get('mailinglist-emails', 'MailinglistEmailController@index')->name('mailingList-emails');
    Route::post('mailinglist-ajax-index', 'MailinglistEmailController@ajaxIndex');
    Route::post('mailinglist-ajax-store', 'MailinglistEmailController@store');
    Route::post('mailinglist-ajax-show', 'MailinglistEmailController@show');
    Route::post('mailinglist-ajax-duplicate', 'MailinglistEmailController@duplicate');
    Route::post('mailinglist-stats', 'MailinglistEmailController@getStats');
});

Route::group(['middleware' => 'auth', 'prefix' => 'checkout'], function () {
    Route::post('coupons/store', 'CouponController@store')->name('coupons.store');
    Route::post('coupons/{id}', 'CouponController@update');
    Route::get('coupons', 'CouponController@index')->name('coupons.index');
    Route::post('coupons/load', 'CouponController@loadData');
    Route::get('coupons/load', 'CouponController@loadData');
    Route::delete('coupons/{id}', 'CouponController@destroy');
    Route::get('coupons/{id}/report', 'CouponController@showReport');
    Route::get('coupons/report', 'CouponController@showReport');


    Route::post('/coupon-code-rules','CouponController@addRules')->name('couponcode.store');
    Route::post('/rule-details','CouponController@getCouponCodeRuleById')->name('rule_details');
    Route::post('/sales-rules-update','CouponController@updateRules')->name('salesrules.update');
    Route::post('/generate-code','CouponController@generateCouponCode')->name('generateCode');
    Route::post('/getWebsiteByStore','CouponController@getWebsiteByStore')->name('getWebsiteByStore');
    Route::post('/delete-coupon','CouponController@deleteCouponByCode')->name('deleteCouponByCode');
    Route::any('/delete-rules/{id}','CouponController@deleteCouponCodeRuleById')->name('delete-rules');
});

Route::middleware('auth')->group(function()
{
    Route::get('keywordassign', 'KeywordassignController@index')->name('keywordassign.index');
Route::get('keywordassign/load', 'KeywordassignController@loadData');
Route::get('keywordassign/create', 'KeywordassignController@create')->name('keywordassign.create');
Route::post('keywordassign/store', 'KeywordassignController@store')->name('keywordassign.store');
Route::post('keywordassign/taskcategory', 'KeywordassignController@taskcategory')->name('keywordassign.taskcategory');
Route::get('keywordassign/{id}', 'KeywordassignController@edit');
Route::post('keywordassign/{id}/update', 'KeywordassignController@update');
Route::get('keywordassign/{id}/destroy', 'KeywordassignController@destroy');

Route::get('keywordreponse/logs', 'KeywordassignController@keywordreponse_logs')->name('keywordreponse.logs');//Purpose : add route for Keyword logs - DEVTASK-4233



Route::post('attachImages/queue', 'ProductController@queueCustomerAttachImages')->name('attachImages.queue');
});


Route::group(['middleware' => 'auth'], function () {
    Route::prefix('tmp-task')->group(function () {
        Route::get('import-leads', 'TmpTaskController@importLeads')->name('importLeads');
    });
    // this is temp action
    Route::get('update-purchase-order-product', 'PurchaseController@syncOrderProductId');
    Route::get('update-media-directory', 'TmpController@updateImageDirectory');
    Route::resource('page-notes-categories', 'PageNotesCategoriesController');
});


Route::prefix('chat-bot')->middleware('auth')->group(function () {
    Route::get('/connection', 'ChatBotController@connection');
});

Route::middleware('auth')->group(function(){ 

Route::get('scrap-logs', 'ScrapLogsController@index');
Route::get('scrap-logs/{name}', 'ScrapLogsController@indexByName');
Route::get('scrap-logs/fetch/{name}/{date}', 'ScrapLogsController@filter');
Route::get('fetchlog', 'ScrapLogsController@fetchlog');
Route::get('filtertosavelogdb', 'ScrapLogsController@filtertosavelogdb');
Route::get('scrap-logs/file-view/{filename}/{foldername}', 'ScrapLogsController@fileView');

Route::post('scrap-logs/status/store', 'ScrapLogsController@store');

Route::put('supplier/language-translate/{id}', 'SupplierController@languageTranslate');
Route::put('supplier/priority/{id}', 'SupplierController@priority');
Route::get('temp-task/product-creator', 'TmpTaskController@importProduct');


});



Route::prefix('google')->middleware('auth')->group(function () {
    Route::resource('/search/keyword', 'GoogleSearchController');
    Route::get('/search/keyword-priority', 'GoogleSearchController@markPriority')->name('google.search.keyword.priority');
    Route::get('/search/keyword', 'GoogleSearchController@index')->name('google.search.keyword');
    Route::get('/search/results', 'GoogleSearchController@searchResults')->name('google.search.results');
    Route::get('/search/scrap', 'GoogleSearchController@callScraper')->name('google.search.keyword.scrap');

    Route::resource('/affiliate/keyword', 'GoogleAffiliateController');
    Route::get('/affiliate/keyword', 'GoogleAffiliateController@index')->name('google.affiliate.keyword');
    Route::get('/affiliate/keyword-priority', 'GoogleAffiliateController@markPriority')->name('google.affiliate.keyword.priority');
    Route::get('/affiliate/results', 'GoogleAffiliateController@searchResults')->name('google.affiliate.results');
    Route::delete('/affiliate/results/{id}', 'GoogleAffiliateController@deleteSearch');
    Route::delete('/search/results/{id}', 'GoogleSearchController@deleteSearch');
    Route::post('affiliate/flag', 'GoogleAffiliateController@flag')->name('affiliate.flag');
    Route::post('affiliate/email/send', 'GoogleAffiliateController@emailSend')->name('affiliate.email.send');
    Route::get('/affiliate/scrap', 'GoogleAffiliateController@callScraper')->name('google.affiliate.keyword.scrap');
});
Route::any('/jobs', 'JobController@index')->middleware('auth')->name('jobs.list');
Route::get('/jobs/{id}/delete', 'JobController@delete')->middleware('auth')->name('jobs.delete');
Route::post('/jobs/delete-multiple', 'JobController@deleteMultiple')->middleware('auth')->name('jobs.delete.multiple');
Route::any('/jobs/alldelete/{id}', 'JobController@alldelete')->middleware('auth')->name('jobs.alldelete');


Route::any('/failedjobs', 'FailedJobController@index')->middleware('auth')->name('failedjobs.list');
Route::get('/failedjobs/{id}/delete', 'FailedJobController@delete')->middleware('auth')->name('failedjobs.delete');
Route::post('/failedjobs/delete-multiple', 'FailedJobController@deleteMultiple')->middleware('auth')->name('failedjobs.delete.multiple');
Route::any('/failedjobs/alldelete/{id}', 'FailedJobController@alldelete')->middleware('auth')->name('failedjobs.alldelete');

Route::get('/wetransfer-queue', 'WeTransferController@index')->middleware('auth')->name('wetransfer.list');
Route::post('/wetransfer/re-downloads-files', 'WeTransferController@reDownloadFiles')->middleware('auth')->name('wetransfer.reDownload.files');

Route::post('/supplier/manage-scrap-brands', 'SupplierController@manageScrapedBrands')->middleware('auth')->name('manageScrapedBrands');

Route::group(['middleware' => ['auth', 'role_or_permission:Admin|deployer']], function () {
    Route::prefix('github')->group(function () {
        Route::get('/repos', 'Github\RepositoryController@listRepositories');
        Route::get('/repos/{name}/users', 'Github\UserController@listUsersOfRepository');
        Route::get('/repos/{name}/users/add', 'Github\UserController@addUserToRepositoryForm');
        Route::get('/repos/{id}/branches', 'Github\RepositoryController@getRepositoryDetails');
        Route::get('/repos/{id}/pull-request', 'Github\RepositoryController@listPullRequests');
        Route::get('/repos/{id}/branch/merge', 'Github\RepositoryController@mergeBranch');
        Route::get('/repos/{id}/deploy', 'Github\RepositoryController@deployBranch');
        Route::post('/add_user_to_repo', 'Github\UserController@addUserToRepository');
        Route::get('/users', 'Github\UserController@listOrganizationUsers');
        Route::get('/users/{userId}', 'Github\UserController@userDetails');
        Route::get('/groups', 'Github\GroupController@listGroups');
        Route::post('/groups/users/add', 'Github\GroupController@addUser');
        Route::post('/groups/repositories/add', 'Github\GroupController@addRepository');
        Route::get('/groups/{groupId}', 'Github\GroupController@groupDetails');
        Route::get('/groups/{groupId}/repos/{repoId}/remove', 'Github\GroupController@removeRepositoryFromGroup');
        Route::get('/groups/{groupId}/users/{userId}/remove', 'Github\GroupController@removeUsersFromGroup');
        Route::get('/groups/{groupId}/users/add', 'Github\GroupController@addUserForm');
        Route::get('/groups/{groupId}/repositories/add', 'Github\GroupController@addRepositoryForm');
        Route::get('/sync', 'Github\SyncController@index');
        Route::get('/sync/start', 'Github\SyncController@startSync');
        Route::get('/repo_user_access/{id}/remove', 'Github\UserController@removeUserFromRepository');
        Route::post('/linkUser', 'Github\UserController@linkUser');
        Route::post('/modifyUserAccess', 'Github\UserController@modifyUserAccess');
        Route::get('/pullRequests', 'Github\RepositoryController@listAllPullRequests');
    });
});

Route::group(['middleware' => ['auth', 'role_or_permission:Admin|deployer']], function () {
    Route::get('/deploy-node', 'Github\RepositoryController@deployNodeScrapers');
});

Route::middleware('auth')->group(function(){
Route::put('customer/language-translate/{id}', 'CustomerController@languageTranslate');
Route::get('get-language', 'CustomerController@getLanguage')->name('livechat.customer.language');
});


Route::group(['middleware' => 'auth'], function () {
    Route::get('/calendar', 'UserEventController@index');
    Route::get('/calendar/events', 'UserEventController@list');
    Route::post('/calendar/events', 'UserEventController@createEvent')->name("calendar.event.create");
    Route::get('/calendar/events/edit/{id}', 'UserEventController@GetEditEvent')->name("calendar.event.edit");
    Route::post('/calendar/events/update', 'UserEventController@UpdateEvent')->name("calendar.event.update");
    Route::post('/calendar/events/stop', 'UserEventController@stopEvent')->name("calendar.event.stop");
    Route::put('/calendar/events/{id}', 'UserEventController@editEvent');
    Route::delete('/calendar/events/{id}', 'UserEventController@removeEvent');
});

Route::prefix('calendar/public')->middleware('auth')->group(function () {
    Route::get('/{id}', 'UserEventController@publicCalendar');
    Route::get('/events/{id}', 'UserEventController@publicEvents');
    Route::get('/event/suggest-time/{invitationId}', 'UserEventController@suggestInvitationTiming');
    Route::post('/event/suggest-time/{invitationId}', 'UserEventController@saveSuggestedInvitationTiming');
});


Route::middleware('auth')->group(function()
{
    Route::get('/vendor-form', 'VendorSupplierController@vendorForm')->name("developer.vendor.form");
Route::get('/supplier-form', 'VendorSupplierController@supplierForm')->name("developer.supplier.form");
});



Route::prefix('product-category')->middleware('auth')->group(function () {
    Route::get('/history', 'ProductCategoryController@history');
    Route::get('/', 'ProductCategoryController@index')->name("product.category.index.list");
    Route::get('/records', 'ProductCategoryController@records')->name("product.category.records");
    Route::post('/update-category-assigned', 'ProductCategoryController@updateCategoryAssigned')->name("product.category.update-assigned");
});

Route::prefix('product-color')->middleware('auth')->group(function () {
    Route::get('/history', 'ProductColorController@history');
    Route::get('/', 'ProductColorController@index')->name("product.color.index.list");
    Route::get('/records', 'ProductColorController@records')->name("product.color.records");
    Route::post('/update-color-assigned', 'ProductColorController@updateCategoryAssigned')->name("product.color.update-assigned");
});

Route::prefix('listing-history')->middleware('auth')->group(function () {
    Route::get('/', 'ListingHistoryController@index')->name("listing.history.index");
    Route::get('/records', 'ListingHistoryController@records');
});


Route::prefix('ads')->middleware('auth')->group(function () {
    Route::prefix('account')->group(function () {
        Route::post('store','AdsController@saveaccount')->name('ads.saveaccount');
    });
    Route::get('/', 'AdsController@index')->name('ads.index');
    Route::get('/records', 'AdsController@records')->name('ads.records');
    Route::post('/savecampaign', 'AdsController@savecampaign')->name('ads.savecampaign');
    Route::post('/savegroup', 'AdsController@savegroup')->name('ads.savegroup');
    Route::get('/getgroups', 'AdsController@getgroups')->name('ads.getgroups');
    Route::post('/adsstore', 'AdsController@adsstore')->name('ads.adsstore');
});



Route::prefix( 'google-campaigns')->middleware('auth')->group(function () {
    Route::get('/', 'GoogleCampaignsController@index')->name('googlecampaigns.index');
    Route::get('/create', 'GoogleCampaignsController@createPage')->name('googlecampaigns.createPage');
    Route::post('/create', 'GoogleCampaignsController@createCampaign')->name('googlecampaigns.createCampaign');
    Route::get('/update/{id}', 'GoogleCampaignsController@updatePage')->name('googlecampaigns.updatePage');
    Route::post('/update', 'GoogleCampaignsController@updateCampaign')->name('googlecampaigns.updateCampaign');
    Route::delete('/delete/{id}', 'GoogleCampaignsController@deleteCampaign')->name('googlecampaigns.deleteCampaign');
    //google adwords account
    Route::get('/ads-account', 'GoogleAdsAccountController@index')->name('googleadsaccount.index');
    Route::get('/ads-account/create', 'GoogleAdsAccountController@createGoogleAdsAccountPage')->name('googleadsaccount.createPage');
    Route::post('/ads-account/create', 'GoogleAdsAccountController@createGoogleAdsAccount')->name('googleadsaccount.createAdsAccount');
    Route::get('/ads-account/update/{id}', 'GoogleAdsAccountController@editeGoogleAdsAccountPage')->name('googleadsaccount.updatePage');
    Route::post('/ads-account/update', 'GoogleAdsAccountController@updateGoogleAdsAccount')->name('googleadsaccount.updateAdsAccount');

    Route::prefix('{id}')->group(function () {
        Route::prefix('adgroups')->group(function () {
            Route::get('/', 'GoogleAdGroupController@index')->name('adgroup.index');
            Route::get('/create', 'GoogleAdGroupController@createPage')->name('adgroup.createPage');
            Route::post('/create', 'GoogleAdGroupController@createAdGroup')->name('adgroup.createAdGroup');
            Route::get('/update/{adGroupId}', 'GoogleAdGroupController@updatePage')->name('adgroup.updatePage');
            Route::post('/update', 'GoogleAdGroupController@updateAdGroup')->name('adgroup.updateAdGroup');
            Route::delete('/delete/{adGroupId}', 'GoogleAdGroupController@deleteAdGroup')->name('adgroup.deleteAdGroup');

            Route::prefix('{adGroupId}')->group(function () {
                Route::prefix('ads')->group(function () {
                    Route::get('/', 'GoogleAdsController@index')->name('ads.index');
                    Route::get('/create', 'GoogleAdsController@createPage')->name('ads.createPage');
                    Route::post('/create', 'GoogleAdsController@createAd')->name('ads.craeteAd');
                    Route::delete('/delete/{adId}', 'GoogleAdsController@deleteAd')->name('ads.deleteAd');
                });
            });
        });
    });
});

Route::prefix('digital-marketing')->middleware('auth')->group(function () {
    Route::get('/', 'DigitalMarketingController@index')->name('digital-marketing.index');
    Route::post('/get-emails', 'DigitalMarketingController@getEmails');
    Route::get('/records', 'DigitalMarketingController@records')->name('digital-marketing.records');
    Route::post('/save', 'DigitalMarketingController@save')->name('digital-marketing.save');
    Route::post('/saveImages', 'DigitalMarketingController@saveImages')->name('digital-marketing.saveimages');
    Route::prefix('{id}')->group(function () {
        Route::get('/edit', 'DigitalMarketingController@edit')->name("digital-marketing.edit");
        Route::get('/components', 'DigitalMarketingController@components')->name("digital-marketing.components");
        Route::post('/components', 'DigitalMarketingController@componentStore')->name("digital-marketing.components.save");
        Route::get('/delete', 'DigitalMarketingController@delete')->name("digital-marketing.delete");
        Route::get('/files', 'DigitalMarketingController@files')->name("digital-marketing.files");
        Route::get('/files-solution', 'DigitalMarketingController@filesSolution')->name("digital-marketing.filessolution");

        Route::prefix('solution')->group(function () {
            Route::get('/', 'DigitalMarketingController@solution')->name("digital-marketing.solutions");
            Route::get('/records', 'DigitalMarketingController@solutionRecords')->name("digital-marketing.records");
            Route::post('/save', 'DigitalMarketingController@solutionSave')->name("digital-marketing.solution.save");
            Route::post('/create-usp', 'DigitalMarketingController@solutionCreateUsp')->name("digital-marketing.solution.create-usp");
            Route::prefix('{solutionId}')->group(function () {
                Route::get('/edit', 'DigitalMarketingController@solutionEdit')->name("digital-marketing.solution.edit");
                Route::get('/delete', 'DigitalMarketingController@solutionDelete')->name("digital-marketing.solution.delete");
                Route::post('/save-usp', 'DigitalMarketingController@solutionSaveUsp')->name("digital-marketing.solution.delete");
                Route::prefix('research')->group(function () {
                    Route::get('/', 'DigitalMarketingController@research')->name("digital-marketing.solution.research");
                    Route::get('/records', 'DigitalMarketingController@researchRecords')->name("digital-marketing.solution.research");
                    Route::post('/save', 'DigitalMarketingController@researchSave')->name("digital-marketing.solution.research.save");
                    Route::prefix('{researchId}')->group(function () {
                        Route::get('/edit', 'DigitalMarketingController@researchEdit')->name("digital-marketing.solution.research.edit");
                        Route::get('/delete', 'DigitalMarketingController@researchDelete')->name("digital-marketing.solution.research.delete");
                    });
                });
            });
        });
    });
});

Route::group(['middleware' => 'auth', 'prefix' => 'return-exchange'], function () {
    Route::get('/', 'ReturnExchangeController@index')->name('return-exchange.list');
    Route::get('/records', 'ReturnExchangeController@records')->name('return-exchange.records');
    Route::get('/model/{id}', 'ReturnExchangeController@getOrders');
    Route::get('/getProducts/{id}', 'ReturnExchangeController@getProducts');
    Route::get('/getRefundInfo/{id}', 'ReturnExchangeController@getRefundInfo');
    Route::post('/model/{id}/save', 'ReturnExchangeController@save')->name('return-exchange.save');
    Route::post('/updateCustomers', 'ReturnExchangeController@updateCustomer')->name('return-exchange.updateCusromer');
    Route::post('/createRefund', 'ReturnExchangeController@createRefund')->name('return-exchange.createRefund');
    Route::post('/updateRefund', 'ReturnExchangeController@updateRefund')->name('return-exchange.updateRefund');
    Route::post('/update-estimated-date', 'ReturnExchangeController@updateEstmatedDate')->name('return-exchange.update-estimated-date');
    Route::get('/status', 'ReturnExchangeController@status')->name('return-exchange.status');
    Route::post('/status/store', 'ReturnExchangeController@saveStatusField')->name('return-exchange.save.status-field');
    Route::post('/status/create', 'ReturnExchangeController@createStatus')->name('return-exchange.createStatus');
    Route::post('/status/delete', 'ReturnExchangeController@deleteStatus')->name('return-exchange.deleteStatus');
    Route::post('/addNewReply', 'ReturnExchangeController@addNewReply')->name('returnexchange.addNewReply');

    Route::prefix('{id}')->group(function () {
        Route::get('/detail', 'ReturnExchangeController@detail')->name('return-exchange.detail');
        Route::get('/delete', 'ReturnExchangeController@delete')->name('return-exchange.delete');
        Route::get('/history', 'ReturnExchangeController@history')->name('return-exchange.history');
        Route::get('/date-history', 'ReturnExchangeController@estimationHistory')->name('return-exchange.date-history');
        Route::get('/product', 'ReturnExchangeController@product')->name('return-exchange.product');
        Route::post('/update', 'ReturnExchangeController@update')->name('return-exchange.update');
        Route::get('/resend-email', 'ReturnExchangeController@resendEmail')->name('return-exchange.resend-email');
        Route::get('/download-pdf', 'ReturnExchangeController@downloadRefundPdf')->name('return-exchange.download-pdf');
    });
});

/**
 * Shipment module
 */
Route::group(['middleware' => 'auth'], function () {
    Route::post('shipment/send/email', 'ShipmentController@sendEmail')->name('shipment/send/email');
    Route::get('shipment/view/sent/email', 'ShipmentController@viewSentEmail')->name('shipment/view/sent/email');
    Route::get('shipment/waybill-track-histories', 'ShipmentController@viewWaybillTrackHistory')->name('shipment/waybill-track-histories');
    Route::get('shipment/{id}/edit', 'ShipmentController@editShipment')->name('shipment.editShipment');
    Route::post('shipment/{id}/save', 'ShipmentController@saveShipment')->name('shipment.saveShipment');
    Route::resource('shipment', 'ShipmentController');
    Route::get('shipment/customer-details/{id}', 'ShipmentController@showCustomerDetails');
    Route::post('shipment/generate-shipment', 'ShipmentController@generateShipment')->name('shipment/generate');
    Route::get('shipment/get-templates-by-name/{name}', 'ShipmentController@getShipmentByName');
    Route::post('shipment/pickup-request', 'ShipmentController@createPickupRequest')->name('shipment/pickup-request');
    Route::post('shipment/save-box-size', 'ShipmentController@saveBoxSize')->name('shipment.save-box-size');

    Route::get('shipments/payment_info', 'ShipmentController@getPaymentInfo')->name('shipment.get-payment-info');
    Route::post('shipments/payment_info', 'ShipmentController@savePaymentInfo')->name('shipment.save-payment-info');

    /**
     * Twilio account management
     */


    Route::get('twilio/manage-twilio-account', 'TwilioController@manageTwilioAccounts')->name('twilio-manage-accounts');
    Route::post('twilio/add-account', 'TwilioController@addAccount')->name('twilio-add-account');
    Route::get('twilio/delete-account/{id}', 'TwilioController@deleteAccount')->name('twilio-delete-account');
    Route::get('twilio/manage-numbers/{id}', 'TwilioController@manageNumbers')->name('twilio-manage-numbers');


    /**
     * Watson account management
     */

    Route::get('watson/accounts', 'WatsonController@index')->name('watson-accounts');
    Route::post('watson/account', 'WatsonController@store')->name('watson-accounts.add');
    Route::get('watson/account/{id}', 'WatsonController@show')->name('watson-accounts.show');
    Route::post('watson/account/{id}', 'WatsonController@update')->name('watson-accounts.update');
    Route::get('watson/delete-account/{id}', 'WatsonController@destroy')->name('watson-accounts.delete');
    Route::post('watson/add-intents/{id}', 'WatsonController@addIntentsToWatson')->name('watson-accounts.add-intents');


    Route::get('get-twilio-numbers/{account_id}', 'TwilioController@getTwilioActiveNumbers')->name('twilio-get-numbers');
    Route::post('twilio/assign-number', 'TwilioController@assignTwilioNumberToStoreWebsite')->name('assign-number-to-store-website');
    Route::post('twilio/call-forward', 'TwilioController@twilioCallForward')->name('manage-twilio-call-forward');

    Route::get('twilio/call-recordings/{account_id}', 'TwilioController@CallRecordings')->name('twilio-call-recording');
    Route::get('/download-mp3/{sid}', 'TwilioController@downloadRecording')->name('download-mp3');

    Route::get('twilio/call-management', 'TwilioController@callManagement')->name('twilio-call-management');
    Route::get('twilio/incoming-calls/{number_sid}/{number}', 'TwilioController@getIncomingList')->name('twilio-incoming-calls');
    Route::get('twilio/incoming-calls-recording/{call_sid}', 'TwilioController@incomingCallRecording')->name('twilio-incoming-call-recording');

    //missing brands
    Route::get('missing-brands', 'MissingBrandController@index')->name('missing-brands.index');
    Route::post('missing-brands/store', 'MissingBrandController@store')->name('missing-brands.store');
    Route::post('missing-brands/reference', 'MissingBrandController@reference')->name('missing-brands.reference');
    Route::post('missing-brands/multi-reference', 'MissingBrandController@multiReference')->name('missing-brands.multi-reference');
    Route::post('missing-brands/automatic-merge', 'MissingBrandController@automaticMerge')->name('missing-brands.automatic-merge');


    //subcategory route

});


Route::middleware('auth')->group(function()
{
Route::post('message-queue/approve/approved', '\Modules\MessageQueue\Http\Controllers\MessageQueueController@approved');


Route::get('message-counter', [\Modules\MessageQueue\Http\Controllers\MessageQueueController::class,'message_counter'])->name('message.counter');




//Charity Routes
Route::get('charity', 'CharityController@index')->name('charity');
Route::any('charity/update', 'CharityController@update')->name('charity.update');
Route::post('charity/store', 'CharityController@store')->name('charity.store');
Route::get('charity/charity-order/{charity_id}', 'CharityController@charityOrder')->name('charity.charity-order');
Route::post('charity/add-status', 'CharityController@addStatus')->name('charity.add-status');
Route::post('charity/update-charity-order-status', 'CharityController@updateCharityOrderStatus')->name('charity.update-charity-order-status');
Route::post('charity/create-history', 'CharityController@createHistory')->name('charity.create-history');
Route::get('charity/view-order-history/{order_id}', 'CharityController@viewHistory')->name('charity.view-order-history');

});





/****Webhook URL for twilio****/
Route::get('/run-webhook/{sid}', 'TwilioController@runWebhook');

Route::middleware('auth')->group(function()
{
/*
 * Quick Reply Page
 * */
Route::get('/quick-replies', 'QuickReplyController@quickReplies')->name('quick-replies');
Route::get('/get-store-wise-replies/{category_id}/{store_website_id?}', 'QuickReplyController@getStoreWiseReplies')->name('store-wise-replies');
Route::post('/save-store-wise-reply', 'QuickReplyController@saveStoreWiseReply')->name('save-store-wise-reply');
Route::post('/attached-images-grid/customer/create-template', 'ProductController@createTemplate')->name('attach.cus.create.tpl');

/**
 * Store Analytics Module
 */
Route::get('/store-website-analytics/index', 'StoreWebsiteAnalyticsController@index');
Route::any('/store-website-analytics/create', 'StoreWebsiteAnalyticsController@create');
Route::get('/store-website-analytics/edit/{id}', 'StoreWebsiteAnalyticsController@edit');
Route::get('/store-website-analytics/delete/{id}', 'StoreWebsiteAnalyticsController@delete');
Route::get('/store-website-analytics/report/{id}', 'StoreWebsiteAnalyticsController@report');
Route::get('/analytis/cron/showData', 'AnalyticsController@cronShowData');

Route::get('store-website-country-shipping', 'StoreWebsiteCountryShippingController@index')->name('store-website-country-shipping.index');
Route::any('store-website-country-shipping/create', 'StoreWebsiteCountryShippingController@create')->name('store-website-country-shipping.create');
Route::get('store-website-country-shipping/edit/{id}', 'StoreWebsiteCountryShippingController@edit')->name('store-website-country-shipping.edit');
Route::get('store-website-country-shipping/delete/{id}', 'StoreWebsiteCountryShippingController@delete')->name('store-website-country-shipping.delete');

Route::get('/attached-images-grid/customer/', 'ProductController@attachedImageGrid');
Route::post('/attached-images-grid/add-products/{suggested_products_id}', 'ProductController@attachMoreProducts');//
Route::post('/attached-images-grid/remove-products/{customer_id}', 'ProductController@removeProducts');//
Route::post('/attached-images-grid/remove-single-product/{customer_id}', 'ProductController@removeSingleProduct');//
Route::get('/attached-images-grid/sent-products', 'ProductController@suggestedProducts');
Route::post('/attached-images-grid/forward-products', 'ProductController@forwardProducts');//
Route::post('/attached-images-grid/resend-products/{suggested_products_id}', 'ProductController@resendProducts');//
Route::get('/attached-images-grid/get-products/{type}/{suggested_products_id}/{customer_id}', 'ProductController@getCustomerProducts');
});


//referfriend
Route::prefix('referfriend')->middleware('auth')->group(static function () {
    Route::get('/list', 'ReferFriendController@index')->name('referfriend.list');
    Route::DELETE('/delete/{id?}', 'ReferFriendController@destroy')->name('referfriend.destroy');
});

//ReferralProgram
Route::prefix('referralprograms')->middleware('auth')->group(static function () {
    Route::get('/list', 'ReferralProgramController@index')->name('referralprograms.list');
    Route::DELETE('/delete/{id?}', 'ReferralProgramController@destroy')->name('referralprograms.destroy');
    Route::get('/add', 'ReferralProgramController@create')->name('referralprograms.add');
    Route::get('/{id?}/edit', 'ReferralProgramController@edit')->name('referralprograms.edit');
    Route::post('/store', 'ReferralProgramController@store')->name('referralprograms.store');
    Route::post('/update', 'ReferralProgramController@update')->name('referralprograms.update');


});


//CommonMailPopup

// auth not applied
Route::post('/common/sendEmail', 'CommonController@sendCommonEmail')->name('common.send.email');
Route::get('/common/getmailtemplate', 'CommonController@getMailTemplate')->name('common.getmailtemplate');

//Google file translator
Route::prefix('googlefiletranslator')->middleware('auth')->group(static function () {
    Route::get('/list', 'GoogleFileTranslator@index')->name('googlefiletranslator.list');
    Route::DELETE('/delete/{id?}', 'GoogleFileTranslator@destroy')->name('googlefiletranslator.destroy');
    Route::get('/add', 'GoogleFileTranslator@create')->name('googlefiletranslator.add');
    Route::get('/{id?}/edit', 'GoogleFileTranslator@edit')->name('googlefiletranslator.edit');
    Route::get('/{id?}/download', 'GoogleFileTranslator@download')->name('googlefiletranslator.download');
    Route::post('/store', 'GoogleFileTranslator@store')->name('googlefiletranslator.store');
    Route::post('/update', 'GoogleFileTranslator@update')->name('googlefiletranslator.update');

});

//Translation
Route::prefix('translation')->middleware('auth')->group(static function () {
    Route::get('/list', 'TranslationController@index')->name('translation.list');
    Route::DELETE('/delete/{id?}', 'TranslationController@destroy')->name('translation.destroy');
    Route::get('/add', 'TranslationController@create')->name('translation.add');
    Route::get('/{id?}/edit', 'TranslationController@edit')->name('translation.edit');
    Route::post('/store', 'TranslationController@store')->name('translation.store');
    Route::post('/update', 'TranslationController@update')->name('translation.update');

});
//for email templates page
Route::get('getTemplateProduct', 'TemplatesController@getTemplateProduct')->middleware('auth')->name('getTemplateProduct');

//Affiliates
Route::prefix('affiliates')->middleware('auth')->group(static function () {
    Route::get('/', 'AffiliateResultController@index')->name('affiliates.list');
    Route::POST('/delete', 'AffiliateResultController@destroy')->name('affiliates.destroy');
    Route::get('/{id?}/edit', 'AffiliateResultController@edit')->name('affiliates.edit');
});
//FCM Notifications
Route::prefix('pushfcmnotification')->middleware('auth')->group(static function () {
    Route::get('/list', 'FcmNotificationController@index')->name('pushfcmnotification.list');
    Route::DELETE('/delete/{id?}', 'FcmNotificationController@destroy')->name('pushfcmnotification.destroy');
    Route::get('/add', 'FcmNotificationController@create')->name('pushfcmnotification.add');
    Route::get('/{id?}/edit', 'FcmNotificationController@edit')->name('pushfcmnotification.edit');
    Route::post('/store', 'FcmNotificationController@store')->name('pushfcmnotification.store');
    Route::post('/update', 'FcmNotificationController@update')->name('pushfcmnotification.update');
    Route::get('/error-list', 'FcmNotificationController@errorList')->name('pushfcmnotification.errorList');
});


//System size
Route::prefix('system')->middleware('auth')->group(static function () {
    Route::get('/size', 'SystemSizeController@index')->name('system.size');
    Route::get('/size/store', 'SystemSizeController@store')->name('system.size.store');
    Route::get('/size/update', 'SystemSizeController@update')->name('system.size.update');
    Route::get('/size/delete', 'SystemSizeController@delete')->name('system.size.delete');

    Route::get('/size/managercheckexistvalue', 'SystemSizeController@managercheckexistvalue')->name('system.size.managercheckexistvalue');
    Route::post('/size/managerstore', 'SystemSizeController@managerstore')->name('system.size.managerstore');
    Route::get('/size/manageredit', 'SystemSizeController@manageredit')->name('system.size.manageredit');
    Route::post('/size/managerupdate', 'SystemSizeController@managerupdate')->name('system.size.managerupdate');
    Route::get('/size/managerdelete', 'SystemSizeController@managerdelete')->name('system.size.managerdelete');

    Route::prefix('auto-refresh')->group(static function () {
        Route::get('/', 'AutoRefreshController@index')->name('auto.refresh.index');
        Route::post('/create', 'AutoRefreshController@store')->name('auto.refresh.store');
        Route::get('/{id}/edit', 'AutoRefreshController@edit')->name('auto.refresh.edit');
        Route::post('/{id}/update', 'AutoRefreshController@update')->name('auto.refresh.update');
        Route::get('/{id}/delete', 'AutoRefreshController@delete')->name('auto.refresh.delete');
    });
});

Route::middleware('auth')->group(function()
{
Route::get('/scrapper-python', 'scrapperPhyhon@index')->name('scrapper.phyhon.index');
Route::get('/scrapper-python/list-images', 'scrapperPhyhon@listImages')->name('scrapper.phyhon.listImages');

Route::get('/set/default/store/{website?}/{store?}/{checked?}', 'scrapperPhyhon@setDefaultStore')->name('set.default.store');



Route::get('/get/website/stores/{website?}', 'scrapperPhyhon@websiteStoreList')->name('website.store.list');


// DEV MANISH
Route::get('google-keyword-search', 'GoogleAddWord\googleAddsController@index')->name('google-keyword-search');
Route::get('google-keyword-search-v6', 'GoogleAddWord\googleAddsV6Controller@main')->name('google-keyword-search-v6');

Route::resource('google-traslation-settings', 'GoogleTraslationSettingsController');
});

Route::post('displayContentModal','EmailContentHistoryController@displayModal')->name('displayContentModal');
Route::post('add_content','EmailContentHistoryController@store')->name('add_content');

// DEV MANISH
//System size
Route::group(['middleware' => 'auth', 'admin'], function () {
    Route::any('/erp-log', 'ErpLogController@index')->name('erp-log');
});

Route::group(['middleware' => 'auth', 'admin'], function () {
    Route::any('/database-log', 'ScrapLogsController@databaseLog');
});

Route::get('gtmetrix', 'gtmetrix\WebsiteStoreViewGTMetrixController@index')->name('gt-metrix');
Route::get('gtmetrix/status/{status}', 'gtmetrix\WebsiteStoreViewGTMetrixController@saveGTmetrixCronStatus')->name('gt-metrix.status');
Route::post('gtmetrix/history', 'gtmetrix\WebsiteStoreViewGTMetrixController@history')->name('gtmetrix.hitstory');
Route::post('gtmetrix/save-time', 'gtmetrix\WebsiteStoreViewGTMetrixController@saveGTmetrixCronType')->name('saveGTmetrixCronType');

Route::get('product-pricing', 'product_price\ProductPriceController@index')->name('product.pricing');
// Route::post('gtmetrix/save-time', 'gtmetrix\WebsiteStoreViewGTMetrixController@saveGTmetrixCronType')->name('saveGTmetrixCronType');

Route::group(['middleware' => 'auth', 'admin'], function () {
    Route::prefix('plan')->group(static function () {
        Route::get('/', 'PlanController@index')->name('plan.index');
        Route::post('/create', 'PlanController@store')->name('plan.store');
        Route::get('/edit', 'PlanController@edit')->name('plan.edit');
        Route::post('/{id}/update', 'PlanController@update')->name('plan.update');
        Route::get('/delete/{id}', 'PlanController@delete')->name('plan.delete');
        Route::get('/{id}/plan-action', 'PlanController@planAction');
        Route::post('/plan-action/store', 'PlanController@planActionStore');
        Route::post('/plan-action/solutions-store', 'PlanController@planSolutionsStore');
        Route::get('/plan-action/solutions-get/{id}', 'PlanController@planSolutionsGet');

        Route::post('plan/basis/create', 'PlanController@newBasis')->name('plan.create.basis');
        Route::post('plan/type/create', 'PlanController@newType')->name('plan.create.type');
        Route::post('plan/category/create', 'PlanController@newCategory')->name('plan.create.category');
    });
});
Route::group(['middleware' => 'auth'], function () {
    Route::get('/admin-menu/db-query', 'DBQueryController@index')->name('admin.databse.menu.direct.dbquery');
    Route::post('/admin-menu/db-query/get-columns', 'DBQueryController@columns')->name('admin.databse.menu.direct.dbquery.columns');
    Route::post('/admin-menu/db-query/confirm', 'DBQueryController@confirm')->name('admin.databse.menu.direct.dbquery.confirm');
    Route::post('/admin-menu/db-query/delete/confirm', 'DBQueryController@deleteConfirm')->name('admin.databse.menu.direct.dbquery.delete.confirm');
    Route::post('/admin-menu/db-query/update', 'DBQueryController@update')->name('admin.databse.menu.direct.dbquery.update');
    Route::post('/admin-menu/db-query/delete', 'DBQueryController@delete')->name('admin.databse.menu.direct.dbquery.delete');
});

Route::middleware('auth')->prefix('totem')->group(function() {

    Route::get('/', 'TasksController@dashboard')->name('totem.dashboard');

    Route::group(['prefix' => 'tasks'], function () {
        Route::get('/', 'TasksController@index')->name('totem.tasks.all');

        Route::get('{task}', 'TasksController@view')->name('totem.task.view');

        Route::post('{task}/delete', 'TasksController@destroy')->name('totem.task.delete');

        Route::post('{task}/status', 'TasksController@status')->name('totem.task.status');
    });

});

Route::prefix('select2')->middleware('auth')->group(function () {
    Route::get('customers', 'Select2Controller@customers')->name('select2.customer');
    Route::get('customersByMultiple', 'Select2Controller@customersByMultiple')->name('select2.customerByMultiple');
    Route::get('users', 'Select2Controller@users')->name('select2.user');
    Route::get('users_vendors', 'Select2Controller@users_vendors')->name('select2.uservendor');
    Route::get('suppliers', 'Select2Controller@suppliers')->name('select2.suppliers');
    Route::get('updatedby-users', 'Select2Controller@updatedbyUsers')->name('select2.updatedby_users');
    Route::get('scraped-brand', 'Select2Controller@scrapedBrand')->name('select2.scraped-brand');
    Route::get('brands', 'Select2Controller@allBrand')->name('select2.brands');
    Route::get('categories', 'Select2Controller@allCategory')->name('select2.categories');
});

Route::get('whatsapp-log', 'Logging\WhatsappLogsController@getWhatsappLog')->name('whatsapp.log');


//Magento Product Error

Route::prefix('magento-product-error')->middleware('auth')->group(static function () {
    Route::get('/', 'MagentoProductPushErrors@index')->name('magento-productt-errors.index');
    Route::get('/records', 'MagentoProductPushErrors@records')->name("magento-productt-errors.records");

    Route::post('/loadfiled', 'MagentoProductPushErrors@getLoadDataValue');

    Route::get('/download', 'MagentoProductPushErrors@groupErrorMessage')->name('magento_product_today_common_err');
});

Route::prefix('message-queue-history')->middleware('auth')->group(static function () {
    Route::get('/', 'MessageQueueHistoryController@index')->name('message-queue-history.index');
    Route::get('/records', 'MessageQueueHistoryController@records')->name("message-queue-history.records");
});


Route::prefix('custom-chat-message')->middleware('auth')->group(static function () {
    Route::get('/', 'ChatMessagesController@customChatListing')->name('custom-chat-message.index');
    Route::get('/records', 'ChatMessagesController@customChatRecords');
});

