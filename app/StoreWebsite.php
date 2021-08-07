<?php

namespace App;

/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Plank\Mediable\Mediable;

class StoreWebsite extends Model
{
    /**
     * @var string

     * @SWG\Property(property="title",type="string")
     * @SWG\Property(property="remote_software",type="string")
     * @SWG\Property(property="website",type="string")
     * @SWG\Property(property="description",type="string")
     * @SWG\Property(property="is_published",type="boolean")
     * @SWG\Property(property="deleted_at",type="datetime")
     * @SWG\Property(property="created_at",type="datetime")
     * @SWG\Property(property="updated_at",type="datetime")
     * @SWG\Property(property="magento_url",type="string")
     * @SWG\Property(property="magento_username",type="string")
     * @SWG\Property(property="magento_password",type="string")
     * @SWG\Property(property="api_token",type="string")
     * @SWG\Property(property="cropper_color",type="string")
     * @SWG\Property(property="cropping_size",type="string")
     * @SWG\Property(property="instagram",type="string")
     * @SWG\Property(property="instagram_remarks",type="string")
     * @SWG\Property(property="facebook",type="string")
     * @SWG\Property(property="facebook_remarks",type="string")
     * @SWG\Property(property="server_ip",type="integer")
     * @SWG\Property(property="username",type="string")
     * @SWG\Property(property="password",type="string")
     * @SWG\Property(property="staging_username",type="string")
     * @SWG\Property(property="staging_password",type="string")
     * @SWG\Property(property="mysql_username",type="string")
     * @SWG\Property(property="mysql_password",type="string")
     * @SWG\Property(property="mysql_staging_username",type="string")
     * @SWG\Property(property="mysql_staging_password",type="string")
     * @SWG\Property(property="website_source",type="string")
     * @SWG\Property(property="push_web_key",type="string")
     * @SWG\Property(property="push_web_id",type="integer")
     * @SWG\Property(property="icon",type="string")
     * @SWG\Property(property="is_price_override",type="boolean")
     */
    use SoftDeletes;
    use Mediable;
    protected $fillable = [
        'title',
        'remote_software',
        'website',
        'description',
        'is_published',
        'disable_push',
        'deleted_at',
        'created_at',
        'updated_at',
        'magento_url',
        'magento_username',
        'magento_password',
        'api_token',
        'cropper_color',
        'cropping_size',
        'instagram',
        'instagram_remarks',
        'facebook',
        'facebook_remarks',
        'server_ip',
        'username',
        'password',
        'staging_username',
        'staging_password',
        'mysql_username',
        'mysql_password',
        'mysql_staging_username',
        'mysql_staging_password',
        'website_source',
        'push_web_key',
        'push_web_id',
        'icon',
        'is_price_override',
    ];

    const DB_CONNECTION = [
        'mysql'          => 'Erp',
        'brandsandlabel' => 'Brands and label',
        'avoirchic'      => 'Avoirchic',
        'olabels'        => 'O-labels',
        'sololuxury'     => 'Sololuxury',
        'suvandnet'      => 'Suv and net',
        'thefitedit'     => 'The fitedit',
        'theshadesshop'  => 'The shades shop',
        'veralusso'      => 'Veralusso',
        'upeau'          => 'Upeau',
    ];

    // Append attributes
    protected $appends = ['website_url'];

    function list() {
        return self::pluck("website", "id")->toArray();
    }

    /**
     * Get proper website url
     */
    public function getWebsiteUrlAttribute()
    {
        $url    = $this->website;
        $parsed = parse_url($url);
        if (empty($parsed['scheme'])) {
            return $urlStr = 'http://' . ltrim($url, '/');
        }
        return $url;
    }

    /**
     * Get store brand
     */
    public function brands()
    {
        return $this->belongsToMany('App\Brand', 'store_website_brands', 'store_website_id', 'brand_id');
    }

    /**
     * Get store categories
     */
    public function categories()
    {
        return $this->belongsToMany('App\Category', 'store_website_categories', 'store_website_id', 'category_id');
    }

    public function sizeCategory()
    {
        return $this->belongsToMany('App\Category', 'brand_category_size_charts', 'store_website_id', 'category_id');
    }

    public function sizeBrand()
    {
        return $this->belongsToMany('App\Brand', 'brand_category_size_charts', 'store_website_id', 'brand_id');
    }

    public static function shopifyWebsite()
    {
        return self::where("website_source", "shopify")->pluck("title", "id")->toArray();
    }

    public static function magentoWebsite()
    {
        return self::where("website_source", "magento")->pluck("title", "id")->toArray();
    }

    public function websites()
    {
        return $this->hasMany('App\Website', 'store_website_id', 'id');
    }
}
