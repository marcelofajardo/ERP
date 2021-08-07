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

Route::prefix('store-website')->middleware('auth')->group(function () {
    Route::get('/', 'StoreWebsiteController@index')->name("store-website.index");
    
    Route::get('/magento-user-lising', 'StoreWebsiteController@magentoUserList')->name("store-website.user-list");

    Route::get('/cancellation', 'StoreWebsiteController@cancellation')->name("store-website.cancellation");
    Route::get('/records', 'StoreWebsiteController@records')->name("store-website.records");
    Route::post('/save', 'StoreWebsiteController@save')->name("store-website.save");
    Route::post('/save-cancellation', 'StoreWebsiteController@saveCancellation')->name("store-website.save-cancellation");
    Route::post('/generate-file-store', 'StoreWebsiteController@generateStorefile')->name("store-website.generate-file-store");
    
    Route::post('/save-user-in-magento', 'StoreWebsiteController@saveUserInMagento')->name("store-website.save-user-in-magento");
    Route::post('/delete-user-in-magento', 'StoreWebsiteController@deleteUserInMagento')->name("store-website.delete-user-in-magento");

    Route::prefix('{id}')->group(function () {
        Route::get('/edit', 'StoreWebsiteController@edit')->name("store-website.edit");
        Route::get('/edit-cancellation', 'StoreWebsiteController@editCancellation')->name("store-website.edit-cancellation");
        Route::get('/delete', 'StoreWebsiteController@delete')->name("store-website.delete");
        Route::get('/child-categories', 'CategoryController@getChildCategories')->name("store-website.child-categories");
        Route::post('/submit-social-remarks', 'StoreWebsiteController@updateSocialRemarks')->name("store-website.update.social-remarks");

        Route::prefix('social-strategy')->group(function () {
    		Route::get('/', 'StoreWebsiteController@socialStrategy')->name("store-website.social-strategy");   
    		Route::post('/add-subject', 'StoreWebsiteController@submitSubject')->name("store-website.social-strategy.add-subject");   
            Route::post('/add-strategy', 'StoreWebsiteController@submitStrategy')->name("store-website.social-strategy.add-strategy");
            Route::post('/upload-documents', 'StoreWebsiteController@uploadDocuments')->name("store-website.social-strategy.upload-documents");
            Route::post('/save-documents', 'StoreWebsiteController@saveDocuments')->name("store-website.social-strategy.save-documents");
            Route::get('/list-documents', 'StoreWebsiteController@listDocuments')->name("store-website.social-strategy.list-documents");
            Route::post('/delete-document', 'StoreWebsiteController@deleteDocument')->name("store-website.social-strategy.delete-documents");
            Route::post('/send-document', 'StoreWebsiteController@sendDocument')->name("store-website.social-strategy.send-documents");
            Route::get('/remarks', 'StoreWebsiteController@remarks')->name("store-website.social-strategy.remarks");
            Route::post('/remarks', 'StoreWebsiteController@saveRemarks')->name("store-website.social-strategy.saveRemarks"); 
            Route::get('/edit-subject', 'StoreWebsiteController@viewSubject')->name("store-website.social-strategy.edit-subject"); 
            Route::post('/edit-subject', 'StoreWebsiteController@submitSubjectChange')->name("store-website.social-strategy.submit-edit-subject"); 
    	});

        Route::prefix('attached-category')->group(function () {
    		Route::get('/', 'CategoryController@index')->name("store-website.attached-category.index");
    		Route::post('/', 'CategoryController@store')->name("store-website.attached-category.store");
            Route::prefix('{store_category_id}')->group(function () {
                Route::get('/delete', 'CategoryController@delete')->name("store-website.attached-category.delete");
            });
    	});

        Route::prefix('attached-categories')->group(function () {
            Route::post('/', 'CategoryController@storeMultipleCategories')->name("store-website.attached-categories.store");
        });

        Route::prefix('attached-brand')->group(function () {
            Route::get('/', 'BrandController@index')->name("store-website.attached-brand.index");
            Route::post('/', 'BrandController@store')->name("store-website.attached-brand.store");
            Route::prefix('{store_brand_id}')->group(function () {
                Route::get('/delete', 'BrandController@delete')->name("store-website.attached-brand.delete");
            });
        });

        Route::prefix('goal')->group(function () {
            Route::get('/', 'GoalController@index')->name("store-website.goal.index");
            Route::get('records', 'GoalController@records')->name("store-website.goal.records");
            Route::post('save', 'GoalController@save')->name("store-website.goal.save");
            Route::prefix('{goalId}')->group(function () {
                Route::get('edit', 'GoalController@edit')->name("store-website.goal.edit");
                Route::get('delete', 'GoalController@delete')->name("store-website.goal.delete");
                Route::get('remarks', 'GoalController@remarks')->name("store-website.goal.remarks");
                Route::post('remarks', 'GoalController@storeRemarks')->name("store-website.goal.remarks.store");
            });
        });

        Route::prefix('seo-format')->group(function () {
            Route::get('/', 'SeoController@index')->name("store-website.seo.index");
            Route::post('save', 'SeoController@save')->name("store-website.seo.save");
        });
    });

    Route::prefix('brand')->group(function () {
        Route::get('/', 'BrandController@list')->name("store-website.brand.list");
        Route::get('records', 'BrandController@records')->name("store-website.brand.records");
        Route::post('push-to-store', 'BrandController@pushToStore')->name("store-website.brand.push-to-store");
        Route::post('refresh-min-max-price', 'BrandController@refreshMinMaxPrice')->name("store-website.refresh-min-max-price");
        Route::get('history','BrandController@history')->name("store-website.brand.history");
    });

    Route::prefix('price-override')->group(function () {
        Route::get('/', 'PriceOverrideController@index')->name("store-website.price-override.index");
        Route::get('records', 'PriceOverrideController@records')->name("store-website.price-override.records");
        Route::post('save', 'PriceOverrideController@save')->name("store-website.price-override.save");
        Route::get('calculate', 'PriceOverrideController@calculate')->name("store-website.price-override.calculate");
        Route::prefix('{id}')->group(function () {
            Route::get('edit', 'PriceOverrideController@edit')->name("store-website.price-override.edit");
            Route::get('delete', 'PriceOverrideController@delete')->name("store-website.price-override.delete");
        });
    });

    Route::prefix('category')->group(function () {
        Route::get('/', 'CategoryController@list')->name("store-website.category.list");
        Route::post('save/store/category', 'CategoryController@saveStoreCategory')->name("store-website.save.store.category");
    });

    Route::prefix('color')->group(function () {
        Route::get('/', 'ColorController@index')->name("store-website.color.list");
        Route::post('save', 'ColorController@store')->name("store-website.color.save");
        Route::put('/{id}', 'ColorController@update')->name("store-website.color.edit");
        Route::delete('/{id}', 'ColorController@destroy')->name("store-website.color.destroy");
        Route::post('push-to-store', 'ColorController@pushToStore')->name('store-website.color.push-to-store');
    });

    Route::prefix('websites')->group(function () {
        Route::get('/', 'WebsiteController@index')->name("store-website.websites.index");
        Route::get('/records', 'WebsiteController@records')->name("store-website.websites.records");
        Route::post('save', 'WebsiteController@store')->name("store-website.websites.save");
        Route::post('create-default-stores', 'WebsiteController@createDefaultStores')->name("store-website.websites.createDefaultStores");
        Route::post('move-stores', 'WebsiteController@moveStores')->name("store-website.websites.moveStores");
        Route::post('copy-stores', 'WebsiteController@copyStores')->name("store-website.websites.copyStores");
        Route::post('change-status', 'WebsiteController@changeStatus')->name("store-website.websites.changeStatus");
        Route::post('change-price-ovveride', 'WebsiteController@changePriceOvveride')->name("store-website.websites.changePriceOvveride");
        Route::post('copy-websites', 'WebsiteController@copyWebsites')->name("store-website.websites.copyWebsites");
        Route::get('/{id}/edit', 'WebsiteController@edit')->name("store-website.websites.edit");
        Route::get('/{id}/delete', 'WebsiteController@delete')->name("store-website.websites.delete");
        Route::get('/{id}/push', 'WebsiteController@push')->name("store-website.websites.push");
        Route::get('/{id}/push-stores', 'WebsiteController@pushStores')->name("store-website.websites.pushStores");
        Route::get('/{id}/copy-website-struct', 'WebsiteController@copyWebsiteStructure')->name("store-website.websites.copyWebsiteStructure");
    });

    Route::prefix('website-stores')->group(function () {
        Route::get('/', 'WebsiteStoreController@index')->name("store-website.website-stores.index");
        Route::get('/records', 'WebsiteStoreController@records')->name("store-website.website-stores.records");
        Route::post('save', 'WebsiteStoreController@store')->name("store-website.website-stores.save");
        Route::get('/{id}/edit', 'WebsiteStoreController@edit')->name("store-website.website-stores.edit");
        Route::get('/{id}/delete', 'WebsiteStoreController@delete')->name("store-website.website-stores.delete");
        Route::get('/{id}/push', 'WebsiteStoreController@push')->name("store-website.website-stores.push");
    });

    //Site Attributes
    Route::prefix('site-attributes')->group(function () {
        Route::get('/', 'SiteAttributesControllers@index')->name("store-website.site-attributes.index");
        Route::post('save', 'SiteAttributesControllers@store')->name("store-website.site-attributes-views.save");
        Route::get('list', 'SiteAttributesControllers@list')->name("store-website.site-attributes-views.list");
        Route::get('/records', 'SiteAttributesControllers@records')->name("store-website.site-attributes-views.records");
        Route::get('/{id}/delete', 'SiteAttributesControllers@delete')->name("store-website.site-attributes-views.delete");
        Route::get('/{id}/edit', 'SiteAttributesControllers@edit')->name("store-website.site-attributes-views.edit");
    });

    Route::prefix('website-store-views')->group(function () {
        Route::get('/', 'WebsiteStoreViewController@index')->name("store-website.website-store-views.index");
        Route::get('/records', 'WebsiteStoreViewController@records')->name("store-website.website-store-views.records");
        Route::post('save', 'WebsiteStoreViewController@store')->name("store-website.website-store-views.save");
        Route::get('/{id}/edit', 'WebsiteStoreViewController@edit')->name("store-website.website-store-views.edit");
        Route::get('/{id}/delete', 'WebsiteStoreViewController@delete')->name("store-website.website-store-views.delete");
        Route::get('/{id}/push', 'WebsiteStoreViewController@push')->name("store-website.website-store-views.push");
    });
    
    Route::prefix('page')->group(function () {
        Route::get('/', 'PageController@index')->name("store-website.page.index");
        Route::get('/records', 'PageController@records')->name("store-website.page.records");
        Route::post('save', 'PageController@store')->name("store-website.page.save");
        Route::get('/{id}/edit', 'PageController@edit')->name("store-website.page.edit");
        Route::get('/{id}/delete', 'PageController@delete')->name("store-website.page.delete");
        Route::get('/{id}/push', 'PageController@push')->name("store-website.page.push");
        Route::get('/{id}/pull', 'PageController@pull')->name("store-website.page.pull");
        Route::get('/{id}/get-stores', 'PageController@getStores')->name("store-website.page.getStores");
        Route::get('/{id}/load-page', 'PageController@loadPage')->name("store-website.page.loadPage");
        Route::get('/{id}/history', 'PageController@pageHistory')->name("store-website.page.history");
        Route::get('/{id}/activities', 'PageController@pageActivities')->name('store_website_page.activities');
        Route::get('/{id}/translate-for-other-langauge', 'PageController@translateForOtherLanguage')->name("store-website.page.translate-for-other-langauge");
        Route::get('/{id}/push-website-in-live', 'PageController@pushWebsiteInLive')->name("store-website.page.push-website-in-live");
        Route::get('/{id}/pull-website-in-live', 'PageController@pullWebsiteInLive')->name("store-website.page.pull-website-in-live");
        Route::get('/histories', 'PageController@histories')->name("store-website.page.histories");
        Route::put('/store-platform-id', 'PageController@store_platform_id')->name('store_website_page.store_platform_id');
        Route::post('/copy-to', 'PageController@copyTo')->name('store_website_page.copy.to');
    });

    Route::prefix('category-seo')->group(function () {
        Route::get('/', 'CategorySeoController@index')->name("store-website.category-seo.index");
        Route::get('/records', 'CategorySeoController@records')->name("store-website.category-seo.records");
        Route::post('save', 'CategorySeoController@store')->name("store-website.category-seo.save");
        Route::get('/{id}/edit', 'CategorySeoController@edit')->name("store-website.page.edit");
        Route::get('/{id}/delete', 'CategorySeoController@destroy')->name("store-website.page.delete");
        Route::get('/{id}/translate-for-other-langauge', 'CategorySeoController@translateForOtherLanguage')->name("store-website.page.translate-for-other-langauge");
        Route::get('/{id}/push', 'CategorySeoController@push')->name("store-website.page.push");
        Route::get('/{id}/push-website-in-live', 'CategorySeoController@pushWebsiteInLive')->name("store-website.page.push-website-in-live");
        Route::get('/{id}/history', 'CategorySeoController@history')->name("store-website.page.history");
        Route::get('/{id}/load-page', 'CategorySeoController@loadPage')->name("store-website.page.category.seo.loadPage");
        Route::post('/copy-to', 'CategorySeoController@copyTo')->name('store_website_page.category.seo.copy.to');
    });

    Route::prefix('product-attribute')->group(function () {
        Route::get('/', 'StoreWebsiteProductAttributeController@index')->name("store-website.product-attribute.index");
        Route::get('/records', 'StoreWebsiteProductAttributeController@records')->name("store-website.product-attribute.records");
        Route::post('save', 'StoreWebsiteProductAttributeController@store')->name("store-website.product-attribute.save");
        Route::post('create-default-stores', 'StoreWebsiteProductAttributeController@createDefaultStores')->name("store-website.product-attribute.createDefaultStores");
        Route::post('move-stores', 'StoreWebsiteProductAttributeController@moveStores')->name("store-website.product-attribute.moveStores");
        Route::post('copy-stores', 'StoreWebsiteProductAttributeController@copyStores')->name("store-website.product-attribute.copyStores");
        Route::post('change-status', 'StoreWebsiteProductAttributeController@changeStatus')->name("store-website.product-attribute.changeStatus");
        Route::post('copy-websites', 'StoreWebsiteProductAttributeController@copyWebsites')->name("store-website.product-attribute.copyWebsites");
        Route::get('/{id}/edit', 'StoreWebsiteProductAttributeController@edit')->name("store-website.product-attribute.edit");
        Route::get('/{id}/delete', 'StoreWebsiteProductAttributeController@delete')->name("store-website.product-attribute.delete");
        Route::get('/{id}/push', 'StoreWebsiteProductAttributeController@push')->name("store-website.product-attribute.push");
    });

});

Route::middleware('auth')->group(function()
{
  Route::prefix('site-development')->group(function () {
    
    Route::get('/countdevtask/{id}', 'SiteDevelopmentController@taskCount');
    Route::get('/deletedevtask', 'SiteDevelopmentController@deletedevtask');
    Route::get('/{id?}', 'SiteDevelopmentController@index')->name("site-development.index");
    Route::post('/save-category', 'SiteDevelopmentController@addCategory')->name("site-development.category.save");
    Route::post('/edit-category', 'SiteDevelopmentController@editCategory')->name("site-development.category.edit");
    Route::post('/save-development', 'SiteDevelopmentController@addSiteDevelopment')->name("site-development.save");
    Route::post('/disallow-category', 'SiteDevelopmentController@disallowCategory')->name("site-development.disallow.category");
    Route::post('/upload-documents', 'SiteDevelopmentController@uploadDocuments')->name("site-development.upload-documents");
    Route::post('/save-documents', 'SiteDevelopmentController@saveDocuments')->name("site-development.save-documents");
    Route::post('/delete-document', 'SiteDevelopmentController@deleteDocument')->name("site-development.delete-documents");
    Route::post('/send-document', 'SiteDevelopmentController@sendDocument')->name("site-development.send-documents");
    Route::get('/preview-img/{site_id}', 'SiteDevelopmentController@previewImage')->name("site-development.preview-image");
    Route::get('/artwork-history/{site_id}', 'SiteDevelopmentController@getArtworkHistory')->name("site-development.artwork-history");
    Route::get('/latest-reamrks/{website_id}', 'SiteDevelopmentController@latestRemarks')->name("site-development.latest-reamrks");
    Route::get('/artwork-history/all-histories/{website_id}', 'SiteDevelopmentController@allartworkHistory')->name("site-development.artwork-history.all-histories");
    Route::prefix('{id}')->group(function () {
        Route::get('list-documents', 'SiteDevelopmentController@listDocuments')->name("site-development.list-documents");
        Route::prefix('remarks')->group(function () {
            Route::get('/', 'SiteDevelopmentController@remarks')->name("site-development.remarks");
            Route::post('/', 'SiteDevelopmentController@saveRemarks')->name("site-development.saveRemarks");
        });
    });
});

Route::prefix('site-development-status')->group(function () {
    Route::get('/', 'SiteDevelopmentStatusController@index')->name('site-development-status.index');
    Route::get('records', 'SiteDevelopmentStatusController@records')->name('site-development-status.records');
    Route::get('stats', 'SiteDevelopmentStatusController@statusStats')->name('site-development-status.stats');
    Route::post('save', 'SiteDevelopmentStatusController@save')->name('site-development-status.save');
    Route::post('merge-status', 'SiteDevelopmentStatusController@mergeStatus')->name('site-development-status.merge-status');
    Route::prefix('{id}')->group(function () {
        Route::get('edit', 'SiteDevelopmentStatusController@edit')->name('site-development-status.edit');
        Route::get('delete', 'SiteDevelopmentStatusController@delete')->name('site-development-status.delete');
    });
});

Route::prefix('country-group')->group(function () {
    Route::get('/', 'CountryGroupController@index')->name('store-website.country-group.index');
    Route::get('records', 'CountryGroupController@records')->name('store-website.country-group.records');
    Route::post('save', 'CountryGroupController@save')->name('store-website.country-group.save');
    Route::prefix('{id}')->group(function () {
        Route::get('edit', 'CountryGroupController@edit')->name('store-website.country-group.edit');
        Route::get('delete', 'CountryGroupController@delete')->name('store-website.country-group.delete');
    });
});
});

