<?php

namespace App\Exports;

use App\DailyActivity;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class HourlyReportsExport implements WithMultipleSheets
{
    public function sheets(): array
    {
      $sheets = [];
      $now = Carbon::now();
      $date = Carbon::now()->format('Y-m-d');
      $nine = Carbon::parse('09:00');
      $one = Carbon::parse('13:00');
      $four = Carbon::parse('16:00');

      if ($now->between($nine, $one)) {
        $time_slots = [
          '09:00am - 10:00am',
          '10:00am - 11:00am',
          '11:00am - 12:00pm',
        ];
      } elseif ($now->between($one, $four)) {
        $time_slots = [
          '12:00pm - 01:00pm',
          '01:00pm - 02:00pm',
          '02:00pm - 03:00pm',
        ];
      } else {
        $time_slots = [
          '03:00pm - 04:00pm',
          '04:00pm - 05:00pm',
          '05:00pm - 06:00pm',
        ];
      }
      
      $daily_activities = DailyActivity::where('for_date', $date)
                                        ->whereIn('time_slot', $time_slots)
                                        ->get()->groupBy('user_id');

      foreach ($daily_activities as $user_id => $activity) {
        $sheets[] = new ReportPerUserSheet($user_id, $time_slots);
      }

      return $sheets;
    }
}
