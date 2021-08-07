<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class FileExcelSheetExport implements WithMultipleSheets
{
    use Exportable;

    protected $report_data;
    protected $sheet;
    protected $path = null;

    public function __construct(array $report_data,$sheet, $path)
    {
        $this->report_data = $report_data;
        $this->sheet = $sheet;
        $this->path = $path;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        
    }
}
