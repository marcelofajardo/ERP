<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;

class InventoryStatusHistory extends Model
{
	  /**
     * @var string
    
     * @SWG\Property(property="in_stock",type="boolean")
     * @SWG\Property(property="product_id",type="integer")
      * @SWG\Property(property="date",type="datetime")
     * @SWG\Property(property="prev_in_stock",type="integer")
     * @SWG\Property(property="supplier_id",type="integer")

     */
    protected $fillable = ['product_id','date','in_stock','prev_in_stock','supplier_id'];

    public static function getInventoryHistoryFromProductId($product_id)
    {

        $columns = array('in_stock','prev_in_stock','date','supplier_id');

        return \App\InventoryStatusHistory::where('product_id',$product_id)->get($columns);
    }

    public function product()
    {
    	return $this->belongsTo('App\Product','product_id','id');
    }

    public function supplier()
    {
    	return $this->belongsTo('App\Supplier','supplier_id','id');
    }

    public function product_count()
    {

        //return self::select('product_id')->distinct()->get();
        return $this->hasMany('App\InventoryStatusHistory','supplier_id','supplier_id');
    }

    public function totalBrandsLink($date, $brandID = 0)
    {
        $supplier = $this->supplier;
        $scps = [];
        if($supplier) {
            $scrapers = $this->scrapers;
            if(!$scrapers->isEmpty())  {
                foreach($scrapers as $scraper) {
                    $scps[] = $scraper->scraper_name;
                }
            }
        }

        $brandStatus = \App\BrandScraperResult::whereDate("date",$date)->where("brand_id",$brandID)->whereIn("scraper_name",$scps)->groupBy("date")->select(\DB::raw("SUM(total_urls) as count"))->first();
        
        return $brandStatus ? $brandStatus->count : 0;

    }

}
 