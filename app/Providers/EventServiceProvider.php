<?php

namespace App\Providers;

use App\Events\VendorPaymentCleared;
use App\Events\VendorPaymentCreated;
use App\Listeners\VendorPaymentCashFlow;
use Illuminate\Support\Facades\Event;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Brand;
use App\Email;
use \Plank\Mediable\Media;
use App\Observers\BrandObserver;
use App\Observers\EmailObserver;
use App\Observers\MediaObserver;
use App\Category;
use App\Observers\ScrappedCategoryMappingObserver;
use App\ScrapedProducts;
use App\Observers\ScrappedProductCategoryMappingObserver;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\Event' => [
            'App\Listeners\EventListener',
        ],

        'Illuminate\Auth\Events\Login' => [
	        'App\Listeners\LogSuccessfulLoginListener',
        ],

        'Illuminate\Auth\Events\Logout' => [
	        'App\Listeners\LogSuccessfulLogoutListener',
        ],

        'App\Events\OrderCreated' => [
	        'App\Listeners\CreateOrderCashFlow',
        ],

        'App\Events\OrderUpdated' => [
	        'App\Listeners\UpdateOrderCashFlow',
        ],

        'App\Events\RefundCreated' => [
	        'App\Listeners\CreateRefundCashFlow',
        ],

        'App\Events\RefundDispatched' => [
	        'App\Listeners\UpdateRefundCashFlow',
        ],

        'App\Events\CaseBilled' => [
	        'App\Listeners\CreateCaseCashFlow',
        ],

        'App\Events\CaseBillPaid' => [
	        'App\Listeners\UpdateCaseCashFlow',
        ],

        'App\Events\ProformaConfirmed' => [
	        'App\Listeners\CreatePurchaseCashFlow',
        ],

        'App\Events\VendorPaymentCreated' => [
	        'App\Listeners\VendorPaymentCashFlow',
        ],

        'App\Events\CaseReceivableCreated' => [
	        'App\Listeners\CreateCaseReceivableCashFlow',
        ],

        'App\Events\BloggerPaymentCreated' => [
            'App\Listeners\CreateBloggerCashFlow',
        ],

        'App\Events\VoucherApproved' => [
            'App\Listeners\CreateVoucherCashFlow',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        Brand::observe(BrandObserver::class);
        Email::observe(EmailObserver::class);
        Media::observe(MediaObserver::class);

        Category::observe(ScrappedCategoryMappingObserver::class);

        ScrapedProducts::observe(ScrappedProductCategoryMappingObserver::class);
        //
    }
}
