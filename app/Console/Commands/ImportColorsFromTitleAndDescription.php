<?php

namespace App\Console\Commands;

use App\Colors;
use App\CronJobReport;
use App\Product;
use Illuminate\Console\Command;
use Carbon\Carbon;

class ImportColorsFromTitleAndDescription extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:colors-from-title-description';

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

    private $colors;
    public function handle()
    {

        try {
            $report = CronJobReport::create([
                'signature'  => $this->signature,
                'start_time' => Carbon::now(),
            ]);

            $this->colors = (new Colors)->all();
            unset($this->colors['Red']);
            Product::where('is_approved', 0)->where('color', '')->orderBy('id', 'DESC')->chunk(1000, function ($products) {
                foreach ($products as $product) {
                    $scrapedProducts = $product->many_scraped_products;

                    foreach ($scrapedProducts as $scrapedProduct) {
                        $property = $scrapedProduct->properties;

                        $color = $property['color'] ?? '';
                        $color = $this->getColorsFromText($color);
                        $color = title_case($color);

                        if ($color && strlen($color) < 18 && stripos($color, 'Leather') === false && preg_match('/\d/', $color) === 0 && stripos($color, 'Fabric') === false) {
                            dump($color . '--ing...');
                            $product->color = $color;
                            $product->save();
                            break;
                        }

                        $color = $property['colors'] ?? '';
                        $color = $this->getColorsFromText($color);
                        $color = title_case($color);

                        if ($color && strlen($color) < 18 && stripos($color, 'Leather') === false && preg_match('/\d/', $color) === 0 && stripos($color, 'Fabric') === false) {
                            dump($color . '--ing...');

                            $product->color = $color;
                            $product->save();
                            break;
                        }

                        $color = $this->getColorsFromText($product->title);
                        $color = trim(str_replace(['-', '_'], '', $color));
                        $color = title_case($color);

                        if ($color && strlen($color) < 18 && stripos($color, 'Leather') === false && preg_match('/\d/', $color) === 0 && stripos($color, 'Fabric') === false) {
                            dump($color . '--ing...');
                            $product->color = $color;
                            $product->save();
                            break;
                        }

                        $color = $this->getColorsFromText($product->short_description);
                        $color = trim(str_replace(['-', '_'], '', $color));
                        $color = title_case($color);

                        if ($color && strlen($color) < 18 && stripos($color, 'Leather') === false && preg_match('/\d/', $color) === 0 && stripos($color, 'Fabric') === false) {
                            dump($color . '--ing...');
                            $product->color = $color;
                            $product->save();
                            break;
                        }

                    }
                }
            });

            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }

    private function getColorsFromText($text)
    {
        $availableColors = [];
        $text            = strtolower($text);

        if (strpos($text, 'multi') !== false) {
            return 'Multi';
        }
        foreach ($this->colors as $color) {
            if (!in_array($color, $availableColors, false) && (stripos($text, strtolower($color)) !== false)) {
                $availableColors[] = $color;
            }
        }

        if (count($availableColors) > 1) {
            return 'Multi';
        }

        if ($availableColors !== []) {
            return $availableColors[0];
        }

        return null;
    }
}
