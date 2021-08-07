<?php

namespace App\Observers;

use App\Email;
use App\GmailDataList;
use App\GmailDataMedia;

class EmailObserver
{
    /**
     * Handle the email "created" event.
     *
     * @param  \App\Email  $email
     * @return void
     */
    public function created(Email $email)
    {
        return $this->gmailData($email);
    }

    /**
     * Handle the email "updated" event.
     *
     * @param  \App\Email  $email
     * @return void
     */
    public function updated(Email $email)
    {
        //
    }

    /**
     * Handle the email "deleted" event.
     *
     * @param  \App\Email  $email
     * @return void
     */
    public function deleted(Email $email)
    {
        //
    }

    /**
     * Handle the email "restored" event.
     *
     * @param  \App\Email  $email
     * @return void
     */
    public function restored(Email $email)
    {
        //
    }

    /**
     * Handle the email "force deleted" event.
     *
     * @param  \App\Email  $email
     * @return void
     */
    public function forceDeleted(Email $email)
    {
        //
    }

    public function gmailData(Email $email)
    {
        $a = preg_match_all('/<a[^>]+href=([\'"])(?<href>.+?)\1[^>]*>/i', $email->message, $aTags);
        $img = preg_match_all('/<img[^>]+src=([\'"])(?<src>.+?)\1[^>]*>/i', $email->message, $imgTags);

        if ($a > 0) {
            $gmail = new GmailDataList;
            $gmail->sender = $email->from;
            $gmail->domain = substr($email->from, strpos($email->from, "@") + 1);
            $gmail->received_at = $email->created_at->format('m/d/Y');
            $gmail->save();

            for ($i = 0; $i < count($imgTags[0]); $i++) {
                if (file_get_contents($imgTags['src'][$i]) != '') {
                    $gmail_media = new GmailDataMedia;
                    $gmail_media->gmail_data_list_id = $gmail->id;
                    $gmail_media->page_url = $aTags['href'][$i];
                    $gmail_media->images = $imgTags['src'][$i];
                    $gmail_media->save();
                }
            }
        }
    }
}
