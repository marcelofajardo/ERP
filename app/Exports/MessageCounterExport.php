<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MessageCounterExport implements FromCollection, WithHeadings
{

        protected $header = null;
        protected $data = null;

    public function __construct($header, $data)
    {
        $this->header = $header;
        $this->data = $data;
    }



    public function headings(): array
    {
        return [
            $this->header
            ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return collect($this->data);
    }
}
