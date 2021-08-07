<?php

namespace App\Console\Commands;

use App\Brand;
use App\Category;
use App\ChatMessage;
use App\Compositions;
use App\CronJobReport;
use App\Customer;
use App\Product;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SendAutoReplyToCustomers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'customers:send-auto-reply';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    private $activeMessage = '';

    private $specificCategories = [];

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

            $messagesIds = DB::table('chat_messages')
                ->selectRaw('MAX(id) as id, customer_id')
                ->groupBy('customer_id')
                ->whereNotNull('message')
//            ->where('customer_id', 2272)
                ->where('customer_id', '>', '0')
                ->where(function ($query) {
                    $query->whereNotIn('status', [7, 8, 9]);
                })
                ->get();

            foreach ($messagesIds as $messagesId) {
                $customer = Customer::where('id', $messagesId->customer_id)->whereNotNull('gender')->first();

                if (!$customer) {
                    continue;
                }
//            dump($customer->name);

                $message = ChatMessage::where('id', $messagesId->id)
                    ->where(function ($query) {
                        $query->where('user_id', '=', '0')
                            ->orWhereNull('user_id');
                    })
                    ->first();

                if (!$message) {
                    continue;
                }

                $this->activeMessage = $message->message;

                $extractedCategory    = $this->extractCategory($customer->gender);
                $extractedBrands      = $this->extractBrands();
                $extractedComposition = $this->extractCompositions();

                if ($this->specificCategories !== []) {
                    $extractedCategory = $this->specificCategories;
                }

                if ($extractedCategory === [] && $extractedBrands === [] && $extractedComposition === []) {
                    continue;
                }

                if (!$this->isMessageAskingForProducts($message->message)) {
                    continue;
                }

                $products = new Product();

                if ($extractedBrands !== []) {
                    $products = $products->whereIn('brand', $extractedBrands);
                }

                if ($extractedCategory !== []) {
                    $products = $products->whereIn('category', $extractedCategory);
                }

                if ($extractedComposition !== []) {
                    $products->where(function ($query) use ($extractedComposition) {
                        foreach ($extractedComposition as $key => $composition) {
                            if ($key === 0) {
                                $query = $query->where('composition', 'LIKE', $composition);
                                continue;
                            }

                            $query = $query->orWhere('composition', 'LIKE', $composition);
                        }
                    });

                }

                $products = $products->where('is_without_image', 0)->take(25)->get();

                $messageToSend = ' ';

                $chatMessage              = new ChatMessage();
                $chatMessage->customer_id = $customer->id;
                $chatMessage->message     = $messageToSend;
                $chatMessage->user_id     = 109;
                $chatMessage->status      = 10;
                $chatMessage->approved    = 0;
                $chatMessage->save();

                foreach ($products as $product) {
                    $image = $product->getMedia(config('constants.media_tags'))->first();

                    if (!$image) {
                        continue;
                    }

                    $chatMessage->attachMedia($image, config('constants.media_tags'));

                }

            }

            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }

    }

    private function extractBrands(): array
    {
        $message     = $this->activeMessage;
        $brands      = Brand::whereNull('deleted_at')->get();
        $brandsFound = [];

        foreach ($brands as $brand) {
            $brandName = $brand->name;
            if (stripos(strtoupper($message), strtoupper($brandName)) !== false) {
                $brandsFound[] = $brand->id;
            }
        }

        return $brandsFound;

    }

    private function extractCompositions(): array
    {
        $compositions = Compositions::all();
        $message      = $this->activeMessage;

        $compositionsFound = [];

        foreach ($compositions as $composition) {
            $name  = $composition->name;
            $name2 = $composition->replace_with;
            if (stripos($message, $name) !== false || (stripos($message, $name2) !== false && $name2)) {
                $compositionsFound[] = $name;
                if ($name2) {
                    $compositionsFound[] = $name2;
                }
            }
        }

        return $compositionsFound;
    }

    private function extractCategory($gender)
    {
        if (strtoupper($gender) === 'MALE') {
            return $this->extractMaleCategory();
        }

        return $this->extractFemaleCategory();
    }

    private function extractFemaleCategory()
    {
        $extractedCats  = [];
        $femaleCategory = Category::find(2);
        foreach ($femaleCategory->childs as $femaleCategoryChild) {
            foreach ($femaleCategoryChild->childs as $subSubCategory) {
                if ($this->extractCategoryIdWithReferences($subSubCategory)) {
                    $extractedCats[]            = $subSubCategory->id;
                    $this->specificCategories[] = $subSubCategory->id;
                }
            }
        }

        return $extractedCats;
    }

    private function extractMaleCategory(): array
    {
        $extractedCats  = [];
        $femaleCategory = Category::find(3);
        foreach ($femaleCategory->childs as $femaleCategoryChild) {
            foreach ($femaleCategoryChild->childs as $subSubCategory) {
                if ($this->extractCategoryIdWithReferences($subSubCategory)) {
                    $extractedCats[]            = $subSubCategory->id;
                    $this->specificCategories[] = $subSubCategory->id;
                }
            }
        }

        return $extractedCats;
    }

    private function extractCategoryIdWithReferences($category): bool
    {
        $name    = strlen($category->title) > 3 ? substr($category->title, 0, -1) : $category->title;
        $message = $this->activeMessage;
        return stripos(strtoupper($message), strtoupper($name)) !== false;
    }

    private function isMessageAskingForProducts($message): bool
    {
        $possibleText = [
            'WHERE IS',
            'WHEN WILL YOU',
            'AM I GETTING',
            'WHEN ARE YOU',
            'REFUND',
            'ORDERED',
            'GONNA',
            'GOING TO',
        ];

        foreach ($possibleText as $item) {
            if (stripos($message, $item) !== false) {
                return false;
            }
        }

        return true;
    }

}
