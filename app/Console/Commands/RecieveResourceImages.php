<?php

namespace App\Console\Commands;

use App\CronJobReport;
use App\ResourceCategory;
use App\ResourceImage;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Webklex\IMAP\Client;

class RecieveResourceImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'resource:image';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recieves Resource Image and Category From Email';

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

            $oClient = new Client([
                'host'          => env('IMAP_HOST_RESOURCEIMAGE'),
                'port'          => env('IMAP_PORT_RESOURCEIMAGE'),
                'encryption'    => env('IMAP_ENCRYPTION_RESOURCEIMAGE'),
                'validate_cert' => env('IMAP_VALIDATE_CERT_RESOURCEIMAGE'),
                'username'      => env('IMAP_USERNAME_RESOURCEIMAGE'),
                'password'      => env('IMAP_PASSWORD_RESOURCEIMAGE'),
                'protocol'      => env('IMAP_PROTOCOL_RESOURCEIMAGE'),
            ]);

            $oClient->connect();

            $folder = $oClient->getFolder('INBOX');

            $message = $folder->query()->unseen()->setFetchBody(true)->get()->all();
            if (count($message) == 0) {
                echo 'No New Mail Found';
                echo '<br>';
                die();
            }

            foreach ($message as $messages) {

                if (session()->has('resource.image')) {
                    session()->forget('resource.image');
                }
                $subject = $messages->getSubject();
                $subject = strtolower($subject);
                $subject = explode(" ", $subject);
                //Getting Category
                foreach ($subject as $value) {
                    $category = ResourceCategory::where('parent_id', 0)->where('title', $value)->first();
                    if ($category != null) {
                        $categoryId = $category->id;
                        break;
                    } else {
                        $categoryId = '';
                    }
                }
                //Getting Sub Category
                foreach ($subject as $value) {
                    $subCategory = ResourceCategory::where('parent_id', '!=', 0)->where('title', $value)->first();
                    if ($subCategory != null) {
                        $subCategoryId = $subCategory->id;
                        break;
                    } else {
                        $subCategoryId = '';
                    }
                }
                //Fetching Images

                if ($messages->hasAttachments()) {
                    $aAttachment = $messages->getAttachments();
                    $imageArray  = array();
                    $aAttachment->each(function ($oAttachment) {
                        $name = $oAttachment->getName();
                        if (!file_exists(public_path('/category_images'))) {
                            mkdir(public_path('/category_images'), 0777, true);
                        }
                        $oAttachment->save(public_path('/category_images'), $name);

                        session()->push('resource.image', $name);
                    });

                    $images = json_encode(session()->get('resource.image'));

                }

                //Getting URL
                $body = $messages->getHTMLBody(true);

                preg_match_all('#\bhttps?://[^,\s()<>]+(?:\([\w\d]+\)|([^,[:punct:]\s]|/))#', $body, $match);

                if ($match != null && $match[0] != null && $match[0][0] != null) {
                    $url = $match[0][0];
                } else {
                    $url = '';
                }

                $description = strip_tags($body);

                $resourceimg              = new ResourceImage;
                $resourceimg->cat_id      = $categoryId;
                $resourceimg->sub_cat_id  = $subCategoryId;
                $resourceimg->images      = $images;
                $resourceimg->url         = $url;
                $resourceimg->description = $description;
                $resourceimg->created_by  = 'Email Reciever';
                $resourceimg->is_pending  = 1;
                $resourceimg->save();
                echo "Resource Image Saved";
                session()->forget('resource.image');

            }

            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }

    }
}
