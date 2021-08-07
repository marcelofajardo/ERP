<?php

namespace App\Exports;

use App\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class FileExcelExport implements FromArray, WithHeadings, ShouldAutoSize
{
    /***
    * @return \Illuminate\Support\Collection
    */

    protected $report_data;
    protected $sheet;
    protected $count = 0;
    protected $path = null;

    public function __construct(array $report_data,$sheet, $path)
    {
      $this->report_data = $report_data;
      $this->sheet = $sheet;
      $this->path = $path;
    }

    

    public function array(): array
    {
        $report_data_array = [];

        foreach ($this->report_data as $key => $data) {
            $arr = [];
            foreach($data as $kk => $vv){
                
                if($key != 1)
                {
                    if($this->report_data[1][$kk] != 'undefined')
                        $arr[$this->report_data[1][$kk]]=$vv;
                   
                }
               
            }
            $report_data_array[] = $arr;
        }

        return $report_data_array;
    }

    public function headings(): array
    {
        $heading = [];
        foreach ($this->report_data as $key => $data) {
            foreach($data as $kk => $vv){
                if($key == 1)
                {
                    $heading[] = $vv;
                }
            }
        }

        return $heading;

    }
    

}
