<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Category;

class CategoryMergeWithProduct extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'category:merge';

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

        //cleaning reference with product name and color and composition or any other database files
        $categories = Category::where('parent_id','!=',0)->get();

        foreach ($categories as $category) {
            
            if($category->references){

                try {
                    $word = $category->title;
                    $word = preg_replace('/\s+/', '', $word);
                    $word = preg_replace('/[^a-zA-Z0-9_ -]/s', '', $word);
                    $referenceArray = explode(',', $category->references);
                    $matches = [];
                    foreach ($referenceArray as $input) {
                        if(!empty($input)){
                            $input = preg_replace('/\s+/', '', $input);
                            $input = preg_replace('/[^a-zA-Z0-9_ -]/s', '', $input);
                            similar_text(strtolower($input), strtolower($word), $percent);
                            if ($percent >= 60) {
                                $matches[] = $input;
                            }
                        }

                    }
                    if(count($matches) == 0){
                        $category->references = '';
                        $category->update();
                    }else{
                        $category->references = implode(',',$matches);
                        $category->update();
                    }
                    // dd($referenceArray);
                } catch (\Exception $e) {
                    
                }

            }
            # code...
        }

        

        
    }
}
