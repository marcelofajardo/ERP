<?php

namespace App\Library\Instagram;

use App\Models\Account;
use App\Models\Message;
use InstagramAPI\Constants;
use InstagramAPI\Exception\AccountDisabledException;
use InstagramAPI\Exception\ChallengeRequiredException;
use InstagramAPI\Exception\CheckpointRequiredException;
use InstagramAPI\Exception\ConsentRequiredException;
use InstagramAPI\Exception\FeedbackRequiredException;
use InstagramAPI\Exception\IncorrectPasswordException;
use InstagramAPI\Exception\InvalidUserException;
use InstagramAPI\Exception\SentryBlockException;
use InstagramAPI\Instagram;
use InstagramAPI\Media\Photo\InstagramPhoto;
use InstagramAPI\Media\Video\InstagramVideo;
use InstagramAPI\Utils;

class SendMessage
{
    public function __construct(Message $message)
    {
        // Lookup for matching account
        $account = Account::withoutGlobalScopes()->find($message->account_id);

        // If account/user deleted, but message still exists
        if (is_null($account)) {
            $message->delete();
            return false;
        }

        // Delete if no subscription or trial expired
        if (!$account->user->subscribed() && !$account->user->onTrial()) {
            $message->delete();
            return false;
        }

        // ToDo: Delete messages if reached package messages limit

        // Create instance
        $instagram = new Instagram(config('pilot.debug'), config('pilot.truncatedDebug'), config('pilot.storageConfig'));

        // Set proxy if exists
        if ($account->proxy) {
            $instagram->setProxy($account->proxy->server);
        }

        // Login to Instagram
        try {

            $instagram->login($account->username, $account->password);

        } catch (IncorrectPasswordException $e) {

            $message->status  = config('pilot.MESSAGE_STATUS_FAILED');
            $message->comment = __('The password you entered is incorrect. Please try again.');
            $message->save();

            return false;

        } catch (InvalidUserException $e) {

            $message->status  = config('pilot.MESSAGE_STATUS_FAILED');
            $message->comment = __('The username you entered doesn\'t appear to belong to an account. Please check your username and try again.');
            $message->save();

            return false;

        } catch (SentryBlockException $e) {

            $message->status  = config('pilot.MESSAGE_STATUS_FAILED');
            $message->comment = __('Your account has been banned from Instagram API for spam behaviour or otherwise abusing.');
            $message->save();

            return false;

        } catch (AccountDisabledException $e) {

            $message->status  = config('pilot.MESSAGE_STATUS_FAILED');
            $message->comment = __('Your account has been disabled for violating Instagram terms.');
            $message->save();

            return false;

        } catch (FeedbackRequiredException $e) {

            $message->status  = config('pilot.MESSAGE_STATUS_FAILED');
            $message->comment = __('Feedback required. It looks like you were misusing this feature by going too fast.');
            $message->save();

            return false;

        } catch (CheckpointRequiredException $e) {

            $message->status  = config('pilot.MESSAGE_STATUS_FAILED');
            $message->comment = __('Your account is subject to verification checkpoint. Please go to instagram.com and pass checkpoint!');
            $message->save();

            return false;

        } catch (ChallengeRequiredException $e) {

            $message->status  = config('pilot.MESSAGE_STATUS_FAILED');
            $message->comment = __('Challenge required. Please re-add your account to confirm it.');
            $message->save();

            return false;

        } catch (ConsentRequiredException $e) {

            $message->status  = config('pilot.MESSAGE_STATUS_FAILED');
            $message->comment = __('You should verify and agree terms using your mobile device.');
            $message->save();

            return false;

        } catch (\Exception $e) {

            $message->status  = config('pilot.MESSAGE_STATUS_FAILED');
            $message->comment = $e->getMessage();
            $message->save();

            return false;
        }

        // Case 1: Provided only thread_id and users as array
        // Case 2: Provided only username and NO pk (from user's list)
        // Case 3: Provided both username & pk (autopilot)

        $recipient = [];

        // Case 1
        if (isset($message->options['to']['thread_id'])) {

            $recipient['thread'] = $message->options['to']['thread_id'];

        } else {

            if (isset($message->options['to']['users'])) {

                $recipient['users'] = [];

                foreach ($message->options['to']['users'] as $user) {

                    // Case 2
                    if (is_null($user['pk'])) {

                        // Get user's pk
                        try {

                            $__user = $instagram->people->getInfoByName($user['username'])->getUser();

                            $user['pk']       = $__user->getPk();
                            $user['username'] = $__user->getUsername();
                            $user['fullname'] = $__user->getFullName();

                            // Simulate real human behaviour
                            sleep(rand(config('pilot.SLEEP_MIN'), config('pilot.SLEEP_MAX')));

                        } catch (\Exception $e) {

                            $message->status  = config('pilot.MESSAGE_STATUS_FAILED');
                            $message->comment = $e->getMessage();
                            $message->save();

                            return false;
                        }
                    }

                    // Case 3
                    array_push($recipient['users'], $user['pk']);

                    // Tmp. collect
                    $__users['users'][] = [
                        'pk'       => $user['pk'],
                        'username' => $user['username'],
                        'fullname' => $user['fullname'],
                    ];

                }

                // Fill empty keys
                $message->options = array_merge($message->options, [
                    'to' => [
                        'users' => $__users['users'],
                    ],
                ]);

            }
        }

        // Parse mentions
        if (isset($message->options['message']) && isset($message->options['to']['users'])) {

            $fullname_mentions = $username_mentions = [];
            foreach ($message->options['to']['users'] as $mention) {
                $username_mentions[] = $mention['username'];
                $fullname_mentions[] = $mention['fullname'];
            }

            $username_replace = '@' . join(', @', $username_mentions);
            $fullname_replace = join(', ', $fullname_mentions);

            $message->options = array_merge($message->options, [
                'message' => str_replace('@username', $username_replace, $message->options['message']),
            ]);

            $message->options = array_merge($message->options, [
                'message' => str_replace('@fullname', $fullname_replace, $message->options['message']),
            ]);
        }

        switch ($message->message_type) {

            case config('pilot.MESSAGE_TYPE_TEXT'):

                try {

                    $instagram->direct->sendText($recipient, $message->options['message']);

                    $message->status = config('pilot.MESSAGE_STATUS_SUCCESS');
                    $message->save();

                    return true;

                } catch (\Exception $e) {

                    $message->status  = config('pilot.MESSAGE_STATUS_FAILED');
                    $message->comment = $e->getMessage();
                    $message->save();

                    return false;
                }

                break;

            case config('pilot.MESSAGE_TYPE_POST'):

                $media_type = null;

                try {

                    $media       = $instagram->media->getInfo($message->options['media_id']);
                    $media_items = $media->getItems();

                    if (count($media_items)) {
                        $media_type_code = $media_items[0]->getMediaType();
                        $media_type      = Utils::checkMediaType($media_type_code);
                    }

                    switch ($media_type) {
                        case 'PHOTO':
                        case 'ALBUM':
                            $media_type = 'photo';
                            break;
                        case 'VIDEO':
                            $media_type = 'video';
                            break;
                    }

                    sleep(rand(config('pilot.SLEEP_MIN'), config('pilot.SLEEP_MAX')));

                } catch (\Exception $e) {

                    $message->status  = config('pilot.MESSAGE_STATUS_FAILED');
                    $message->comment = $e->getMessage();
                    $message->save();

                    return false;
                }

                try {

                    $instagram->direct->sendPost($recipient, $message->options['media_id'], [
                        'media_type' => $media_type,
                        'text'       => $message->options['message'],
                    ]);

                    $message->status = config('pilot.MESSAGE_STATUS_SUCCESS');
                    $message->save();

                    return true;

                } catch (\Exception $e) {

                    $message->status  = config('pilot.MESSAGE_STATUS_FAILED');
                    $message->comment = $e->getMessage();
                    $message->save();

                    return false;
                }

                break;

            case config('pilot.MESSAGE_TYPE_PHOTO'):

                try {
                    $photo = new InstagramPhoto($message->options['filename'], [
                        'targetFeed' => Constants::FEED_DIRECT,
                    ]);

                    $instagram->direct->sendPhoto($recipient, $photo->getFile());

                    $message->status = config('pilot.MESSAGE_STATUS_SUCCESS');
                    $message->save();

                    return true;

                } catch (\Exception $e) {

                    $message->status  = config('pilot.MESSAGE_STATUS_FAILED');
                    $message->comment = $e->getMessage();
                    $message->save();

                    return false;
                }

                break;

            case config('pilot.MESSAGE_TYPE_DISAPPEARING_PHOTO'):

                try {

                    $photo = new InstagramPhoto($message->options['filename'], [
                        'targetFeed' => Constants::FEED_DIRECT_STORY,
                    ]);

                    $instagram->direct->sendDisappearingPhoto($recipient, $photo->getFile());

                    $message->status = config('pilot.MESSAGE_STATUS_SUCCESS');
                    $message->save();

                    return true;

                } catch (\Exception $e) {

                    $message->status  = config('pilot.MESSAGE_STATUS_FAILED');
                    $message->comment = $e->getMessage();
                    $message->save();

                    return false;
                }

                break;

            case config('pilot.MESSAGE_TYPE_VIDEO'):

                try {

                    $video = new InstagramVideo($message->options['filename'], [
                        'targetFeed' => Constants::FEED_DIRECT,
                    ]);

                    $instagram->direct->sendVideo($recipient, $video->getFile());

                    $message->status = config('pilot.MESSAGE_STATUS_SUCCESS');
                    $message->save();

                    return true;

                } catch (\Exception $e) {

                    $message->status  = config('pilot.MESSAGE_STATUS_FAILED');
                    $message->comment = $e->getMessage();
                    $message->save();

                    return false;
                }

                break;

            case config('pilot.MESSAGE_TYPE_DISAPPEARING_VIDEO'):

                try {

                    $video = new InstagramVideo($message->options['filename'], [
                        'targetFeed' => Constants::FEED_DIRECT_STORY,
                    ]);

                    $instagram->direct->sendDisappearingVideo($recipient, $video->getFile());

                    $message->status = config('pilot.MESSAGE_STATUS_SUCCESS');
                    $message->save();

                    return true;

                } catch (\Exception $e) {

                    $message->status  = config('pilot.MESSAGE_STATUS_FAILED');
                    $message->comment = $e->getMessage();
                    $message->save();

                    return false;
                }

                break;

            case config('pilot.MESSAGE_TYPE_LIKE'):

                try {

                    $instagram->direct->sendLike($recipient);

                    $message->status = config('pilot.MESSAGE_STATUS_SUCCESS');
                    $message->save();

                    return true;

                } catch (\Exception $e) {

                    $message->status  = config('pilot.MESSAGE_STATUS_FAILED');
                    $message->comment = $e->getMessage();
                    $message->save();

                    return false;
                }

                break;

            case config('pilot.MESSAGE_TYPE_HASHTAG'):

                try {

                    $instagram->direct->sendHashtag($recipient, $message->options['hashtag'], [
                        'text' => $message->options['message'],
                    ]);

                    $message->status = config('pilot.MESSAGE_STATUS_SUCCESS');
                    $message->save();

                    return true;

                } catch (\Exception $e) {

                    $message->status  = config('pilot.MESSAGE_STATUS_FAILED');
                    $message->comment = $e->getMessage();
                    $message->save();

                    return false;
                }

                break;

            case config('pilot.MESSAGE_TYPE_LOCATION'):

                // ToDo

                break;

            case config('pilot.MESSAGE_TYPE_PROFILE'):

                // ToDo

                break;
        }
    }

}
