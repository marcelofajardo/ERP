<?php

namespace App\Console\Commands;

use App\Size;
use Illuminate\Console\Command;

class MoveSizeToTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'size:move-to-table';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Move size to table';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $allsizes = \DB::table("products")->where("stock", ">", 0)->where("size", "!=", "")->groupBy("size")->select("size")->get();
        $sizes    = [];

        if (!empty($allsizes)) {
            foreach ($allsizes as $s) {
                $isJson = self::isJson($s->size);
                $ex     = null;

                if ($isJson) {
                    $ex = json_decode($s->size, true);
                }

                if (empty($ex) && !is_array($ex)) {
                    $ex = explode(",", $s->size);
                }

                $ex = !is_array($ex) ? [$ex] : $ex;

                $ex = array_filter($ex);

                if (!empty($ex)) {
                    foreach ($ex as $e) {

                        try {

                            if (strlen($e) >= 4 || $this->dontNeedThisWords($e) || strpos($e, "cm") !== false || strpos($e, "$") !== false || strpos($e, '"') !== false) {
                                continue;
                            }

                            if (strpos($e, "½") !== false) {
                                $parts   = explode('½', $e);
                                $sizes[] = (int) trim($parts[0]) + 0.5;
                                continue;
                            }

                            if (strpos($e, "/2") !== false) {
                                $parts   = explode(' ', $e);
                                $sizes[] = (int) trim($parts[0]) + 0.5;
                                continue;
                            }

                            if (strpos($e, "1/2") !== false) {
                                $parts = explode(' ', $e);
                                if (isset($parts[0])) {
                                    $sizes[] = (int) $parts[0] + 0.5;
                                }
                                continue;
                            }

                            if (strpos($e, "+") !== false) {
                                $parts   = explode('+', $e);
                                $sizes[] = (int) trim($parts[0]) + 0.5;
                                continue;
                            }

                            if (in_array(trim($e), ['I', 'II', 'III', 'IV', 'V', 'VI', 'VII', 'VIII', 'IX', 'X'])) {
                                $sizes[] = $this->romanToNumber($e);
                                continue;
                            }

                            if (strpos($e, "IT") !== false) {
                                $parts = explode(' ', $e);
                                if (isset($parts[1])) {
                                    $parts   = explode('/', $parts[1]);
                                    $sizes[] = $parts[0] + 0.5;
                                }
                                continue;
                            }

                            if (strpos($e, "UK INCH") !== false) {
                                $prefix = 'UK INCH ';
                                if (substr($e, 0, strlen($prefix)) == $prefix) {
                                    $sizes[] = substr($e, strlen($prefix));
                                }
                                continue;
                            }

                            if (strpos($e, "UK-") !== false) {
                                $prefix = 'UK-';
                                if (substr($e, 0, strlen($prefix)) == $prefix) {
                                    $sizes[] = substr($e, strlen($prefix));
                                }
                                continue;
                            }

                            $e = preg_replace("/\s+/", " ", $e);
                            if (is_string($e)) {
                                $sizes[] = trim(str_replace(["// Out of stock", "bold'>", "</span>"], "", $e));
                            }

                        } catch (\Exception $e) {

                        }
                    }
                }

            }
        }
        $sizes = array_unique($sizes);

        if (!empty($sizes)) {
            foreach ($sizes as $size) {
                Size::updateOrCreate([
                    "name" => $size,
                ], [
                    "name" => $size,
                ]);
            }
        }

    }

    public static function isJson($string)
    {
        json_decode($string);
        return (json_last_error() == JSON_ERROR_NONE);
    }

    private function romanToNumber($e)
    {
        $convertions = [
            'I'    => 1,
            'II'   => 2,
            'III'  => 3,
            'IV'   => 4,
            'V'    => 5,
            'VI'   => 6,
            'VII'  => 7,
            'VIII' => 8,
            'IX'   => 9,
            'X'    => 10,
        ];
        return $convertions[trim($e)];
    }

    private function dontNeedThisWords($e)
    {
        $words = [
            "++",
            "JEANDS",
            "Sold Out",
            "waist",
            "collar",
        ];
        return in_array(trim($e), $words);
    }
}
