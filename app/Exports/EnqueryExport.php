<?php

namespace App\Exports;

use App\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
class EnqueryExport implements FromArray, WithHeadings, ShouldAutoSize, WithEvents
{
    /***
    * @return \Illuminate\Support\Collection
    */

    protected $products;
    protected $orders;
    protected $count = 0;
    protected $path = null;

    public function __construct(array $products,array $orders, $path)
    {
      $this->products = $products;
      $this->orders = $orders;
      $this->path = $path;
    }


    // public function collection()
    // {
    //     return Product::all();
    // }


    public function array(): array
    {
      $products_array = [];
      //$products = Product::whereIn('id', $this->products)->get();

      $products = Product::join('order_products','order_products.product_id','products.id')
        ->join('product_suppliers','product_suppliers.product_id','products.id')
        ->join('brands','brands.id','products.brand')
        ->select('product_suppliers.price as product_price','products.*','brands.name as brand_name')
        ->whereIn('products.id',$this->products)->whereIn('order_products.id',$this->orders)->groupBy("order_products.sku")->get();

      foreach($products as $product) {
        $arr = [];
            $arr['name'] = $product->name;
            $arr['brand'] = $product->brand_name;
            $arr['sku'] = $product->sku;
            $arr['short_description'] = $product->short_description;
            // $arr['price'] = $product->price;
            $arr['product_price'] = $product->price;
            $arr['composition'] = $product->composition;
            $arr['product_link'] = $product->product_link;
            $products_array[] = $arr;
}

      return $products_array;
    }

    public function headings(): array
    {
      return [
        'Name',
        'Brand',
        'SKU Code',
        'Description',
        'Price',
        'Composition',
        'Product Link'
      ];
    }

    public function registerEvents(): array
    {
        return [
            // Handle by a closure.
            AfterSheet::class => function(AfterSheet $event) {
             
              $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(30);
              $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(30);
              $event->sheet->getDelegate()->getColumnDimension('C')->setWidth(30);
              $event->sheet->getDelegate()->getColumnDimension('D')->setAutoSize(true);
              $event->sheet->getDelegate()->getColumnDimension('E')->setWidth(20);
              $event->sheet->getDelegate()->getColumnDimension('F')->setWidth(40);
              $event->sheet->getDelegate()->getColumnDimension('G')->setWidth(40);
            },
        ];
    }

    // public function registerEvents(): array
    // {
    //     return [
    //         // Handle by a closure.
    //         AfterSheet::class => function(AfterSheet $event) {
    //           for ($i = 1; $i <= count($this->products); $i++) {

    //             $coordinates = "A" . (string) ($i + 1);
    //             $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
    //             $drawing->setName('Logo');
    //             $drawing->setDescription('Logo');
    //             $drawing->setPath($this->path);
    //             $drawing->setCoordinates($coordinates);
    //             $drawing->setHeight('100');
    //             $drawing->setOffsetY('10');
    //             $drawing->setWorksheet($event->sheet->getDelegate());
    //             $event->sheet->getDelegate()->getRowDimension($i + 1)->setRowHeight(100);
    //           }

    //           $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(12);
    //         },
    //     ];
    // }
}
