<?php
namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;
use App\Loggers\LogScraper;
use Plank\Mediable\Mediable;

class ScrapRemark extends Model
{
    /**
     * @var string
     * @SWG\Property(property="user_name",type="string")
     * @SWG\Property(property="scraper_name",type="string")
     * @SWG\Property(property="remark",type="string")
     * @SWG\Property(property="module_type",type="string")
     * @SWG\Property(property="scrap_field",type="string")
     * @SWG\Property(property="scrap_id",type="integer")
     */
    use Mediable;
    protected $fillable = [
        'user_name',
        'scraper_name',
        'remark',
        'scrap_id',
        'module_type',
        'scrap_field',
        'old_value',
        'new_value',
    ];

    public function scraps()
    {
        return $this->belongsTo(LogScraper::class);
    }
}
