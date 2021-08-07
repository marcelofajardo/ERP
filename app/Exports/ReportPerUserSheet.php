<?php

namespace App\Exports;

use App\DailyActivity;
use Carbon\Carbon;
use App\Helpers;
use App\User;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeExport;
use Maatwebsite\Excel\Events\AfterSheet;

class ReportPerUserSheet implements FromQuery, WithTitle, WithHeadings, ShouldAutoSize, WithEvents
{
    protected $user_id;
    protected $time_slots;
    protected $users_array;

    public function __construct(int $user_id, array $time_slots)
    {
      $this->user_id = $user_id;
      $this->time_slots = $time_slots;
      $this->users_array = Helpers::getUserArray(User::all());
    }

    public function query()
    {
      return DailyActivity::query()
                          ->where('user_id', $this->user_id)
                          ->where('for_date', Carbon::now()->format('Y-m-d'))
                          ->whereIn('time_slot', $this->time_slots)
                          ->select(['time_slot', 'activity']);

    }

    public function title(): string
    {
      if (array_key_exists($this->user_id, $this->users_array)) {
        return $this->users_array[$this->user_id];
      }

      return 'User Not Exists';
    }

    public function headings(): array
    {
      if (array_key_exists($this->user_id, $this->users_array)) {
        $username = $this->users_array[$this->user_id];
      } else {
        $username = 'User Not Exists';
      }

      return [
        [$username],
        ['Time',
        'Activity']
      ];
    }

    public function registerEvents(): array
    {
      return [
        AfterSheet::class    => function(AfterSheet $event) {
          $event->sheet->getStyle('A1')->applyFromArray([
            'font' => [
              'bold' => true
            ],
            'fill'  => [
              'fillType'  => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
              'startColor'  => [
                'argb'  => 'FFFF00'
              ]
            ]
          ]);

          $event->sheet->getStyle('A2:B2')->applyFromArray([
            'font' => [
              'bold' => true
            ]
          ]);
        },
      ];
    }
}
