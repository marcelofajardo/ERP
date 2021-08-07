<?php


namespace App\Services\BulkCustomerMessage;


use App\BulkCustomerRepliesKeyword;
use App\ChatMessage;
use Illuminate\Support\Facades\DB;

class KeywordsChecker
{
    /**
     * @param $keywords
     * @param $customers
     * @purpose This method gets the messages, and then checks if keywords is in that string or not...
     */
    public function assignCustomerAndKeyword($keywords, $customers): void
    {
        foreach ($customers as $customer) {

            $message = $this->getCustomerMessages($customer);

            if (!$message) {
                continue;
            }

            //dump($message);
            $this->makeKeywordEntryForCustomer($customer, $message, $keywords);
        }
    }

    /**
     * @param $customer
     * @param $message
     * @param $keywords
     * @purpose Checks if the message is in string, and creates keywords like that...
     */
    private function makeKeywordEntryForCustomer($customer, $message, $keywords): void
    {
        $dataToInsert = [];

        foreach ($keywords as $keyword) {
            $keywordValue = strtolower($keyword->value);
            // dump($message . " => " .$keywordValue);
            if (stripos($message, $keywordValue) !== false) {
                $dataToInsert[] = ['keyword_id' => $keyword->id, 'customer_id' => $customer->id];
            }

        }

        if ($dataToInsert === []) {
            return;
        }

        //dump($dataToInsert);

        DB::table('bulk_customer_replies_keyword_customer')->insert($dataToInsert);
        $customer->is_categorized_for_bulk_messages = 1;
        $customer->save();
    }

    /**
     * @param $message
     * @param $customer
     * @purpose create customer and keyword relationship for new incoming messages...
     */
    public function assignCustomerAndKeywordForNewMessage($message, $customer): void
    {
        $keywords = BulkCustomerRepliesKeyword::all();
        $this->makeKeywordEntryForCustomer($customer, $message, $keywords);
    }

    /**
     * @param $customer
     * @return string
     * @purpose To return the latest 3 non-replied messages, this will ignore the auto-generated message...
     */
    private function getCustomerMessages($customer): string
    {
        $messageText = '';
        $messages = ChatMessage::whereNotIn('status', [7,8,9,10])->where('customer_id', $customer->id)->orderBy('id', 'DESC')->take(3)->get();

        foreach ($messages as $message) {
            if ($message->user_id) {
                break;
            }

            $messageText .= $message->message;
        }

        return $messageText;
    }

}