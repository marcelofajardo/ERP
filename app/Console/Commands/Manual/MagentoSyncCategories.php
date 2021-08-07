<?php

namespace App\Console\Commands\Manual;

use App\Category;
use App\MagentoSoapHelper;
use Carbon\Carbon;
use Illuminate\Console\Command;

class MagentoSyncCategories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'magento:sync-categories';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronise all categories with magento';

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
            $report = \App\CronJobReport::create([
                'signature'  => $this->signature,
                'start_time' => Carbon::now(),
            ]);
            // Set memory limit
            ini_set('memory_limit', '2048M');

            // Get all products queued for AI
            $categories = Category::where('parent_id', 0)->get();

            // Set Magento soap helper
            $magentoSoapHelper = new MagentoSoapHelper();

            // Loop over top level categories
            foreach ($categories as $category) {
                // Ignore category ID 1
                if ($category->id != 1) {
                    // Set magento ID
                    $topLevelId = $category->magento_id;

                    // Output name
                    echo $category->title . " > ";
                    if ((int) $category->magento_id > 0) {
                        $result = $magentoSoapHelper->catalogCategoryInfo($category->magento_id);

                        if ($result === false) {
                            echo "\n";
                        } else {
                            echo $result->name . "\n";
                        }
                    } else {
                        echo "Category not exists. Missing Magento ID.\n";
                    }

                    // Get sub-categories
                    $levelTwoCategories = Category::where('parent_id', $category->id)->get();

                    // Loop over level two categories
                    foreach ($levelTwoCategories as $levelTwoCategory) {
                        echo '|-' . $levelTwoCategory->title . " > ";

                        if ((int) $levelTwoCategory->magento_id > 0) {
                            $result = $magentoSoapHelper->catalogCategoryInfo($levelTwoCategory->magento_id);

                            if ($result === false) {
                                echo "\n";
                            } else {
                                echo $result->name . "\n";
                            }
                        } else {
                            echo "Category not exists. Missing Magento ID.\n";
                        }

                        // Get level three categories
                        $levelThreeCategories = Category::where('parent_id', $levelTwoCategory->id)->get();

                        // Loop over level three categories
                        foreach ($levelThreeCategories as $levelThreeCategory) {
                            echo '|---' . $levelThreeCategory->title . " > ";

                            if ((int) $levelThreeCategory->magento_id > 0) {
                                $result = $magentoSoapHelper->catalogCategoryInfo($levelThreeCategory->magento_id);

                                if ($result === false) {
                                    echo "\n";

                                    // Create new category
                                    $arrCategoryData = [
                                        'name'              => ucwords($levelThreeCategory->title),
                                        'is_active'         => 1,
                                        'include_in_menu'   => 1,
                                        'available_sort_by' => ['position'],
                                        'url_key'           => str_replace(' ', '-', strtolower($levelThreeCategory->title)),
                                        'default_sort_by'   => 'position',
                                    ];
                                    $newId = $magentoSoapHelper->catalogCategoryCreate($levelTwoCategory->magento_id, $arrCategoryData);

                                    // Store new ID
                                    $levelThreeCategory->magento_id;
                                    $levelThreeCategory->save();
                                } else {
                                    echo $result->name . "\n";
                                }
                            } else {
                                echo "Category not exists. Missing Magento ID.\n";
                            }
                        }
                    }
                }
            }
            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }
}
