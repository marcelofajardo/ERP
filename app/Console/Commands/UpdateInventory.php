<?php

namespace App\Console\Commands;

use App\CronJobReport;
use App\Jobs\CallHelperForZeroStockQtyUpdate;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UpdateInventory extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inventory:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the inventory in the ERP';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //return false;
        try {
            $report = CronJobReport::create([
                'signature'  => $this->signature,
                'start_time' => Carbon::now(),
            ]);
            // Set empty array with SKU
            $arrInventory = [];

            // find all product first

            $products = \App\Supplier::join("scrapers as sc", "sc.supplier_id", "suppliers.id")
                ->join("scraped_products as sp", "sp.website", "sc.scraper_name")
                ->join("products as p", "p.sku", "sp.sku")
                ->where(function ($q) {
                    $q->whereDate("last_cron_check", "!=", date("Y-m-d"))->orWhereNull('last_cron_check');
                })
                ->limit(100)
                ->where("suppliers.supplier_status_id", 1)
                ->select("sp.last_inventory_at", "sp.sku", "sc.inventory_lifetime", "p.id as product_id", "suppliers.id as supplier_id", "sp.id as sproduct_id", "p.isUploaded","p.color")->get()->groupBy("sku")->toArray();

            if (!empty($products)) {
                $zeroStock = [];

                $sproductIdArr = [];
                $statusHistory = [];
                $needToCheck   = [];
                $productIdsArr = [];

                foreach ($products as $sku => $skuRecords) {

                    $hasInventory = false;
                    $today        = date('Y-m-d');
                    $productId    = null;

                    foreach ($skuRecords as $records) {

                        array_push($sproductIdArr, $records['sproduct_id']);

                        // \DB::statement("update `scraped_products` set `last_cron_check` = now() where `id` = '" . $records['sproduct_id'] . "'");
                        $inventoryLifeTime = isset($records["inventory_lifetime"]) && is_numeric($records["inventory_lifetime"])
                        ? $records["inventory_lifetime"]
                        : 0;

                        if (isset($records["product_id"]) && isset($records["supplier_id"])) {
                            $history     = \App\InventoryStatusHistory::where('date', $today)->where('product_id', $records["product_id"])->where('supplier_id', $records["supplier_id"])->first();
                            $lasthistory = \App\InventoryStatusHistory::where('date', '<', $today)->where('product_id', $records["product_id"])->where('supplier_id', $records["supplier_id"])->orderBy('created_at', 'desc')->first();

                            $prev_in_stock = 0;
                            $new_in_stock  = 1;
                            if ($lasthistory) {
                                $prev_in_stock = $lasthistory->in_stock;
                                $new_in_stock  = $lasthistory->in_stock + 1;
                            }
                            if ($history) {
                                $history->update(['in_stock' => $new_in_stock, 'prev_in_stock' => $prev_in_stock]);
                            } else {

                                $statusHistory[] = array(
                                    'product_id'    => $records["product_id"],
                                    'supplier_id'   => $records["supplier_id"],
                                    'date'          => $today,
                                    'in_stock'      => $new_in_stock,
                                    'prev_in_stock' => $prev_in_stock,
                                    'created_at'    => date("Y-m-d H:i:s")
                                );

                                // $history = new \App\InventoryStatusHistory;
                                // $history->product_id  = $records["product_id"];
                                // $history->supplier_id  = $records["supplier_id"];
                                // $history->date  = $today;
                                // $history->in_stock  = $new_in_stock;
                                // $history->prev_in_stock  = $prev_in_stock;
                                // $history->save();
                            }
                            $productId = $records["product_id"];
                        }

                        if (is_null($records["last_inventory_at"]) || strtotime($records["last_inventory_at"]) < strtotime('-' . $inventoryLifeTime . ' days')) {
                            if ($records["isUploaded"] == 1) {
                                $needToCheck[] = ["id" => $records["product_id"], "sku" => $records['sku'] . $records['color']];
                            }
                            continue;
                        }

                        $hasInventory = true;

                        dump("Scraped Product updated : " . $records['sproduct_id']);

                    }

                    if (!$hasInventory && !empty($productId)) {
                        $productIdsArr[] = $productId;

                    }
                }

                if (!empty($sproductIdArr)) {
                    \DB::table('scraped_products')->whereIn('id', $sproductIdArr)->update(array('last_cron_check' => date("Y-m-d H:i:s")));
                }
                if (!empty($productIdsArr)) {
                    \DB::table('products')->whereIn('id', $productIdsArr)->update(array('stock' => 0, 'updated_at' => date("Y-m-d H:i:s")));
                }

                if (!empty($statusHistory)) {
                    \App\InventoryStatusHistory::insert($statusHistory);
                }

                if (!empty($needToCheck)) {
                    try {
                        $time_start = microtime(true);
                        CallHelperForZeroStockQtyUpdate::dispatch($needToCheck)->onQueue('MagentoHelperForZeroStockQtyUpdate');
                        $time_end = microtime(true);
                        //\Log::info('inventory:update :: ForZeroStockQtyUpdate -Total Execution Time => ' . ($execution_time));
                    } catch (\Exception $e) {
                        \Log::error('inventory:update :: CallHelperForZeroStockQtyUpdate :: ' . $e->getMessage());
                    }
                }

            }

            // Update all products in database to inventory = 0
            //Product::where('id', '>', 0)->update(['stock' => 0]);

            // Get all scraped products with stock
            // $sqlScrapedProductsInStock = "
            // SELECT
            //     sp.sku,
            //     COUNT(sp.id) AS cnt
            // FROM suppliers s
            // JOIN scrapers sc on sc.supplier_id = s.id
            // JOIN scraped_products sp ON sp.website=sc.scraper_name
            // WHERE
            //     s.supplier_status_id=1 AND
            //     sp.last_inventory_at < DATE_SUB(NOW(), INTERVAL sc.inventory_lifetime DAY)
            // GROUP BY
            //     sp.sku";

            // $scrapedProducts = DB::select($sqlScrapedProductsInStock);

            // // Loop over scraped products
            // foreach ($scrapedProducts as $scrapedProduct) {
            //     //$arrInventory[$scrapedProduct->sku] = $scrapedProduct->cnt;
            //     \DB::statement("update LOW_PRIORITY `products` set `stock` = 0, `updated_at` = '" . date("Y-m-d H:i:s") . "' where `sku` = '" . $sku . "' and `products`.`deleted_at` is null");
            //     echo "Updated " . $scrapedProduct->sku . "\n";
            // }

            // //foreach ($arrInventory as $sku => $cnt) {
            // // Find product
            // //Product::where('sku', $sku)->update(['stock' => 0]);
            // //}

            // TODO: Update stock in Magento
            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {

            \Log::error($e->getMessage());

            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }
}
