<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ProductExport implements FromView
{
    public $data;

    public function __construct($data)
    {
      $this->data = $data;
    }

    public function view(): View
    {
        $brands = \App\Brand::all()->pluck("name","id")->toArray();
        return view('exports.products',["data" => $this->data,"brands" => $brands]);
    }
}
