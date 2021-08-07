<?php

namespace App\Console\Commands\Manual;

use Illuminate\Console\Command;
use seo2websites\MagentoHelper\MagentoHelper;

class PushMagentoPagesInDB extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'magento:push-pages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Push Page from magento';

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
        $ids  = $this->ask('Enter Website ids');
        
        if(!empty($ids)) {
            $ids = explode(",", $ids);
            $websites = \App\StoreWebsite::whereIn("id",$ids)->where("api_token", "!=", "")->where('remote_software', '2')->where("website_source", "magento")->get();
        }else{
            $websites = \App\StoreWebsite::where("api_token", "!=", "")->where('remote_software', '2')->where("website_source", "magento")->get();
        }

        if (!$websites->isEmpty()) {
            foreach ($websites as $website) {
                $data = MagentoHelper::pullWebsitePage($website);
                if (!empty($data)) {
                    foreach ($data as $key => $d) {
                        $pages = \App\StoreWebsitePage::where("store_website_id", $website->id)->where('platform_id', $d->id)->first();
                        if (!$pages) {
                            $pages = new \App\StoreWebsitePage;
                        }

                        $pages->store_website_id = $website->id;
                        $pages->platform_id      = $d->id;
                        $pages->title            = isset($d->title) ? $d->title : "";
                        $pages->url_key          = isset($d->identifier) ? $d->identifier : "";
                        $pages->layout           = isset($d->page_layout) ? $d->page_layout : "";
                        $pages->meta_title       = isset($d->meta_title) ? $d->meta_title : "";
                        $pages->meta_keywords    = isset($d->meta_keywords) ? $d->meta_keywords : "";
                        $pages->meta_description = isset($d->meta_description) ? $d->meta_description : "";
                        $pages->content_heading  = isset($d->content_heading) ? $d->content_heading : "";
                        $pages->content          = isset($d->content) ? $d->content : "";
                        $pages->created_at       = isset($d->creation_time) ? $d->creation_time : "";
                        $pages->updated_at       = isset($d->update_time) ? $d->update_time : "";

                        $pages->save();

                    }
                }

            }
        }

    }

}
