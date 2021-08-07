<?php

namespace App\Console\Commands;

use App\Product;
use App\ProductSupplier;
use App\Supplier;
use Illuminate\Console\Command;

class ProductSupplierData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product:supplier';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Supplier set in products Table, but the Supplier is not set for this product in product_suppliers Table';

    /**
     * Create a new command instance.
     *
     * @return void
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
        //
        $product_data = Product::get();

        $product_suppliers_data = ProductSupplier::get();

        $supplier_data = Supplier::get();
        $supplier_arr  = array();
        foreach ($supplier_data as $key => $value) {
            $supplier_arr[$value->id] = $value->supplier;
        }

        $product_suppliers_arr = array();
        foreach ($product_suppliers_data as $key => $value) {
            $product_suppliers_arr[$value->product_id][$value->supplier_id] =  ($supplier_arr[$value->supplier_id] ?? $value->supplier_id );
        }

        $product_not_available_product_supplier_table = array();
        $supplier_exist_product_supplier_table = array();
        $supplier_not_exist_product_supplier_table = array();

        foreach($product_data as $key => $value) {

            if($value->supplier_id != '' && $value->supplier_id != null)
            {
                $supplier_id = $value->supplier_id;
                $product_id = $value->id;
                if (array_key_exists($product_id,$product_suppliers_arr))
                {
                    if (array_key_exists($supplier_id,$product_suppliers_arr[$product_id]))
                    {
                        $supplier_exist_product_supplier_table[$key]['product_id'] = $product_id;
                        $supplier_exist_product_supplier_table[$key]['product_name'] = ($value->name ?? '');
                        $supplier_exist_product_supplier_table[$key]['supplier_id'] = $supplier_id;
                        $supplier_exist_product_supplier_table[$key]['supplier_name'] = ($product_suppliers_arr[$product_id][$supplier_id] ?? '-' );

                    }else{
                        $supplier_not_exist_product_supplier_table[$key]['product_id'] = $product_id;
                        $supplier_not_exist_product_supplier_table[$key]['product_name'] = ($value->name ?? '');
                        $supplier_not_exist_product_supplier_table[$key]['supplier_id'] = $supplier_id;
                    }
                }
                else
                {
                    $product_not_available_product_supplier_table[$key]['product_id'] = $product_id;
                    $product_not_available_product_supplier_table[$key]['product_name'] = ($value->name ?? '');
                }
            }
        }

        $chatFileData = '';
        $chatFileData .= html_entity_decode("Product Supplier Data", ENT_QUOTES, 'UTF-8');
        $chatFileData .= "\n" . "\n";

        foreach ($supplier_not_exist_product_supplier_table as $k => $v) {
            $chatFileData .= html_entity_decode("Product Id : " . $v['product_id'], ENT_QUOTES, 'UTF-8');
            $chatFileData .= "\n";
            $chatFileData .= html_entity_decode("Prodcuct Name : " . $v['product_name'], ENT_QUOTES, 'UTF-8');
            $chatFileData .= "\n";
            $chatFileData .= html_entity_decode("Supplier Id : " . $v['supplier_id'], ENT_QUOTES, 'UTF-8');
            $chatFileData .= "\n" . "\n";
        }
        
        $date = date('Y_m_d_H_i_s');
        $storagelocation = storage_path() . '/logs/not_mapping_product_supplier';
        if (!is_dir($storagelocation)) {
            mkdir($storagelocation, 0777, true);
        }
        $filename = "not_mapping_supplier_".$date.".txt";
        $file = $storagelocation . '/' . $filename;
        $txt = fopen($file, "w") or die("Unable to open file!");
      
        fwrite($txt, $chatFileData);
        fclose($txt);

        // header('Content-Description: File Transfer');
        // header('Content-Disposition: attachment; filename='.basename($file));
        // header('Expires: 0');
        // header('Cache-Control: must-revalidate');
        // header('Pragma: public');
        // header('Content-Length: ' . filesize($file));
        // header("Content-Type: text/plain");
        // readfile($file);
        // unlink($file);
       
        dd("Please Check This File : ".$file);

    
    }
}
