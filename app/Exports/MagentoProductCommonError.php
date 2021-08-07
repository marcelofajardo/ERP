<?php

namespace App\Exports;

use App\Customer;
use App\DeveloperTask;
use App\User;
use App\Task;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use App\DeveloperTaskHistory;

class MagentoProductCommonError implements FromArray, ShouldAutoSize, WithHeadings
{
  protected $lists;

  public function __construct(array $lists)
  {
    $this->lists = $lists;

  }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function array(): array
    {

      return $this->lists;
    }

    public function headings() : array
    {
        return [
            'count',
            'Message',
        ];
    }
}
