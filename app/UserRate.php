<?php

namespace App;
/**
 * @SWG\Definition(type="object", @SWG\Xml(name="User"))
 */
use Illuminate\Database\Eloquent\Model;
use DB;

class UserRate extends Model
{
         /**
     * @var string
      * @SWG\Property(property="user_id",type="integer")
   
      * @SWG\Property(property="start_date",type="datetime")
     */
  protected $fillable = [
    'user_id', 'start_date'
  ];

  static function getRateForUser($userId)
  {
    return self::orderBy('start_date', 'desc')->where('user_id', $userId)->take(1)->first();
  }

  public static function ratesForWeek($week, $year)
  {
    $result = getStartAndEndDate($week, $year);
    $start = $result['week_start'];
    $end = $result['week_end'];

    return self::where('start_date', '>=', $start)
      ->where('start_date', '<', $end)
      ->get();
  }

  public static function rateChangesForDate($start, $end)
  {
    return self::where('start_date', '>=', $start)
      ->where('start_date', '<', $end)
      ->get();
  }

  /**
   * Carry forward the rates from last week to be a part of calculation
   */
  public static function latestRatesForWeek($week, $year)
  {

    $result = getStartAndEndDate($week, $year);
    $start = $result['week_start'];
    $end = $result['week_end'];

    $query =  "SELECT
        *
      from user_rates
      where
        id in (
          SELECT
            GROUP_CONCAT(id) as id
          FROM (
              SELECT
                *
              FROM `user_rates`
              WHERE
                start_date < '$end'
            ) as a
          group by
            user_id
        )";

    $rateData = DB::select($query);

    return self::hydrate($rateData);
  }

  public static function latestRatesBeforeTime($time)
  {
    $query =  "SELECT
        *
      from user_rates
      where
        id in (
          SELECT
            GROUP_CONCAT(id) as id
          FROM (
              SELECT
                *
              FROM `user_rates`
              WHERE
                start_date < '$time'
            ) as a
          group by
            user_id
    )";

    $rateData = DB::select($query);
    return self::hydrate($rateData);
  }


  public static function latestRatesOnDate($time,$user_id)
  {
    return self::where('start_date', '<', $time)
    ->where('user_id', $user_id)
    ->orderBy('start_date','desc')
    ->first();
  }

  
}
