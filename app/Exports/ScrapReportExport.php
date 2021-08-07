<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ScrapReportExport implements FromView
{
    public $data;

    public function __construct($data)
    {
      $this->data = $data;
    }

    public function view(): View
    {
        return view('exports.scrap-report',["reportData" => $this->data]);
    }
}
