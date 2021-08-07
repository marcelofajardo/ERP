<?php

namespace App\Console\Commands;

use App\ChatMessagePhrase;
use App\ChatMessageWord;
use Carbon\Carbon;
use Illuminate\Console\Command;

class MostUsedWordsInChat extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'message:most-used-words';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

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
            // start to get the most used words from chat messages
            $mostUsedWords = \App\Helpers\MessageHelper::getMostUsedWords();
            ChatMessagePhrase::truncate();
            ChatMessageWord::truncate();

            if (!empty($mostUsedWords["words"])) {
                ChatMessageWord::insert($mostUsedWords["words"]);
            }

            // Dump
            // var_dump($mostUsedWords);

            // start to phrases
            $allwords = ChatMessageWord::all();

            $phrasesRecords = [];
            foreach ($allwords as $words) {
                $phrases = isset($mostUsedWords["phrases"][$words->word]) ? $mostUsedWords["phrases"][$words->word]["phrases"] : [];
                if (!empty($phrases)) {
                    foreach ($phrases as $phrase) {
                        if (isset($phrase['txt'])) {
                            // Split message into phrases
                            $split = preg_split('/(\.|\!|\?)/', $phrase['txt'], 10, PREG_SPLIT_DELIM_CAPTURE);

                            // Loop over split
                            foreach ($split as $sentence) {
                                ChatMessagePhrase::insert([
                                    "word_id" => $words->id,
                                    "phrase"  => $sentence,
                                    "chat_id" => $phrase["id"],
                                ]);
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
