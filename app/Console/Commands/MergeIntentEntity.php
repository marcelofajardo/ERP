<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\ChatbotQuestion;
use DB;
use App\ChatbotKeyword;
use App\ChatbotQuestionExample;
use App\ChatbotKeywordValue;
use App\ChatbotKeywordValueTypes;
use App\AutoReply;

class MergeIntentEntity extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'keyword:merge';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Merge chatbot_keywords, chatbot_questions and auto_replies in chatbot_questions table also chatbot_keyword_values in chatbot_question_examples table' ;

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
        DB::beginTransaction();
        try {
            $chatbot_questions = ChatbotQuestion::all();
            foreach($chatbot_questions as $question) {
                if(!$question->keyword_or_question) {
                    $question->keyword_or_question = 'intent';
                    $question->erp_or_watson = 'watson';
                    $question->save();
                }
            }
            $keywords = ChatbotKeyword::all();
            foreach($keywords as $keyword) {
                $question = new ChatbotQuestion;
                $question->value = $keyword->keyword;
                $question->workspace_id = $keyword->workspace_id;
                $question->keyword_or_question = 'entity';
                $question->erp_or_watson = 'watson';
                $question->save();
                $keywordVlaues = ChatbotKeywordValue::where('chatbot_keyword_id',$keyword->id)->get();
                foreach($keywordVlaues as $value) {
                    $example = new ChatbotQuestionExample;
                    $example->question = $value->value;
                    $example->chatbot_question_id = $question->id;
                    $example->types = $value->types;
                    $example->save();
                    $types = ChatbotKeywordValueTypes::where('chatbot_keyword_value_id',$value->id)->get();
                    foreach($types as $type) {
                        $type->chatbot_keyword_value_id = $example->id;
                        $type->save();
                    }
                }
            }
            // $autoriplies = AutoReply::all();
            // foreach($autoriplies as $reply) {
            //     $question = new ChatbotQuestion;
            //     $question->value = $keyword->keyword;
            //     $question->workspace_id = $keyword->workspace_id;
            //     $question->keyword_or_question = 'entity';
            //     $question->erp_or_watson = 'watson';
            //     $question->save();
            // }
        } catch (\Exception $e) {
            DB::rollback();
            DB::commit();
            echo "Something went wrong, database rolledback succesfully." . PHP_EOL;
        }
        DB::commit();
        echo "Successful." . PHP_EOL;
    }
}
