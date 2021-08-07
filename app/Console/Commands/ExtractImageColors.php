<?php

namespace App\Console\Commands;

use App\ColorNamesReference;
use App\Colors;
use App\CronJobReport;
use App\Product;
use Carbon\Carbon;
use ColorThief\ColorThief;
use Illuminate\Console\Command;
use League\ColorExtractor\Color;
use ourcodeworld\NameThatColor\ColorInterpreter as NameThatColor;

class ExtractImageColors extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'extract:image-colors';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        try {
            $report = CronJobReport::create([
                'signature'  => $this->signature,
                'start_time' => Carbon::now(),
            ]);

            $ourColors       = ColorNamesReference::pluck('erp_name', 'color_name')->toArray();
            $availableColors = (new Colors())->all();

            Product::where('is_approved', 1)->where('id', '183946')->chunk(1000, function ($products) use ($ourColors, $availableColors) {
                foreach ($products as $product) {

//                if (isset($availableColors[$product->color])) {
                    //                    dump('skipped');
                    //                    continue;
                    //                }

                    $imageUrl = $product->getMedia(config('constants.media_tags'))->first();

                    if (!$imageUrl) {
                        continue;
                    }

                    $image = $product->getMedia(config('constants.media_tags'))->first();

                    $imageUrl = $image->getUrl();

                    try {
                        $rgb = ColorThief::getColor($imageUrl);
                    } catch (\Exception $exception) {
                        continue;
                    }

                    $rgbColor = [
                        'r' => $rgb[0],
                        'g' => $rgb[1],
                        'b' => $rgb[2],
                    ];

                    $hex = Color::fromIntToHex(Color::fromRgbToInt($rgbColor));
                    dump($hex);
                    $nameThatColor = new NameThatColor();
                    $color         = $nameThatColor->name($hex)['name'];
                    $color         = $ourColors[$color];

                    dump($color);

                    $product->color = $color;
                    $product->save();

                    dump('Saved: ' . $color);

                }
            });

            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }
}
