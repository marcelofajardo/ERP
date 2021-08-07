<?php

/**
 * Class ZoomMeetings | app/Meetings/Meeting/ZoomMeetings.php
 * Zoom Meetings integration for video call purpose using LaravelZoom's REST API
 *
 * @package  Zoom
 * @subpackage Jwt Token
 * @filesource required php 7 as this file contains tokenizer extension which was not stable prior to this version
 * @see https://github.com/saineshmamgain/laravel-zoom
 * @see ZoomMeetings
 * @author   sololux <sololux@gmail.com>
 */

namespace App\Meetings;

use seo2websites\LaravelZoom\LaravelZoom;
use Illuminate\Database\Eloquent\Model;
use App\Supplier;
use App\Customer;
use App\Vendor;
use Dotenv\Dotenv;
use InvalidArgumentException;

/**
 * Class ZoomMeetings - active record
 *
 * A zoom class used to create meetings
 * This class is used to interact with zoom interface.
 *
 * @package  LaravelZoom
 * @subpackage Jwt Token
 */
class ZoomMeetings extends Model
{

    protected $fillable = ['meeting_id', 'meeting_topic', 'meeting_type', 'meeting_agenda', 'join_meeting_url', 'start_meeting_url', 'start_date_time', 'meeting_duration', 'host_zoom_id', 'zoom_recording', 'user_id', 'user_type', 'timezone'];

    /**
     * Create a scheduled and instant meeting with zoom based on the params send through form
     * @param string $zoomKey
     * @param string $zoomSecret
     * @param Array $data
     * @return array $meeting
     * @Rest\Post("LaravelZoom")
     *
     * @uses LaravelZoom
     */
    public function createMeeting($zoomKey, $zoomSecret, $data)
    {
        $zoom = new LaravelZoom($zoomKey, $zoomSecret);
        $time = time() + 7200;
        $token = $zoom->getJWTToken($time);
        if ($token) {

            try {
                $dotEnv = new Dotenv(app()->environmentPath(), app()->environmentFile());
                $dotEnv->load();
            } catch (InvalidArgumentException $e) {
                //
            }

            //$meeting = $zoom->createInstantMeeting($data['user_id'],$data['topic'], '', $data['agenda'], '',$data['settings']);
            $meeting = $zoom->createScheduledMeeting($data[ 'user_id' ], $data[ 'topic' ], $data[ 'startTime' ], $data[ 'duration' ], $data[ 'timezone' ], '', '', $data[ 'agenda' ], [], $data[ 'settings' ]);
            return $meeting;
        } else {
            return false;
        }
    }

    public function getMeetings($zoomKey, $zoomSecret, $data)
    {
        $zoom = new LaravelZoom($zoomKey, $zoomSecret);
        $meeting1 = $zoom->getJWTToken(time() + 7200);
        $meetingAll = $zoom->getMeetings($data[ 'user_id' ], $data[ 'type' ], 10);
        echo "reach";
        echo "<pre>";
        print_r($meetingAll);
        die;
    }

    /**
     * Getting future meetings
     * @param string $type
     * @param carbon $date
     *
     * @return array $meeting
     *
     * @uses vendors
     * @uses customers
     * @uses suppliers
     */
    public function upcomingMeetings($type, $date)
    {
        switch ($type) {
            case 'vendor':
                $meetings = \DB::table('zoom_meetings')
                    ->where('zoom_meetings.user_type', '=', $type)
                    ->whereDate('zoom_meetings.start_date_time', '>=', $date)
                    ->join('vendors', 'zoom_meetings.user_id', '=', 'vendors.id')
                    ->select('zoom_meetings.*', 'vendors.name', 'vendors.phone', 'vendors.email', 'vendors.whatsapp_number')
                    ->orderBy('zoom_meetings.start_date_time', 'ASC')
                    ->get();
                return $meetings;
                break;
            case 'customer':
                $meetings = \DB::table('zoom_meetings')
                    ->where('zoom_meetings.user_type', '=', $type)
                    ->whereDate('zoom_meetings.start_date_time', '>=', $date)
                    ->join('customers', 'zoom_meetings.user_id', '=', 'customers.id')
                    ->select('zoom_meetings.*', 'customers.name', 'customers.phone', 'customers.email', 'customers.whatsapp_number')
                    ->orderBy('zoom_meetings.start_date_time', 'ASC')
                    ->get();
                return $meetings;
                break;
            case 'supplier':
                $meetings = \DB::table('zoom_meetings')
                    ->where('zoom_meetings.user_type', '=', $type)
                    ->whereDate('zoom_meetings.start_date_time', '>=', $date)
                    ->join('suppliers', 'zoom_meetings.user_id', '=', 'suppliers.id')
                    ->select('zoom_meetings.*', 'suppliers.supplier as name', 'suppliers.phone', 'suppliers.email', 'suppliers.whatsapp_number')
                    ->orderBy('zoom_meetings.start_date_time', 'ASC')
                    ->get();
                return $meetings;
                break;
            default:
                break;
        }
    }

    /**
     * Getting past meetings with recordings
     * @param string $type
     * @param carbon $date
     *
     * @return array $meeting
     *
     * @uses vendors
     * @uses customers
     * @uses suppliers
     */
    public function pastMeetings($type, $date)
    {
        switch ($type) {
            case 'vendor':
                $meetings = \DB::table('zoom_meetings')
                    ->where('zoom_meetings.user_type', '=', $type)
                    ->whereDate('zoom_meetings.start_date_time', '<', $date)
                    ->join('vendors', 'zoom_meetings.user_id', '=', 'vendors.id')
                    ->select('zoom_meetings.*', 'vendors.name', 'vendors.phone', 'vendors.email', 'vendors.whatsapp_number')
                    ->orderBy('zoom_meetings.start_date_time', 'ASC')
                    ->get();
                return $meetings;
                break;
            case 'customer':
                $meetings = \DB::table('zoom_meetings')
                    ->where('zoom_meetings.user_type', '=', $type)
                    ->whereDate('zoom_meetings.start_date_time', '<', $date)
                    ->join('customers', 'zoom_meetings.user_id', '=', 'customers.id')
                    ->select('zoom_meetings.*', 'customers.name', 'customers.phone', 'customers.email', 'customers.whatsapp_number')
                    ->orderBy('zoom_meetings.start_date_time', 'ASC')
                    ->get();
                return $meetings;
                break;
            case 'supplier':
                $meetings = \DB::table('zoom_meetings')
                    ->where('zoom_meetings.user_type', '=', $type)
                    ->whereDate('zoom_meetings.start_date_time', '<', $date)
                    ->join('suppliers', 'zoom_meetings.user_id', '=', 'suppliers.id')
                    ->select('zoom_meetings.*', 'suppliers.supplier as name', 'suppliers.phone', 'suppliers.email', 'suppliers.whatsapp_number')
                    ->orderBy('zoom_meetings.start_date_time', 'ASC')
                    ->get();
                return $meetings;
                break;
            default:
                break;
        }
    }

    /**
     * Get meeting recordings based on meeting id
     *
     * @return array $meeting
     * @Rest\Post("LaravelZoom")
     *
     * @uses LaravelZoom
     */
    public function getRecordings($zoomKey, $zoomSecret, $date)
    {
        $allMeetingRecords = ZoomMeetings::WhereNull('zoom_recording')->whereNotNull('meeting_id')->whereDate('start_date_time', '<', $date)->get();
        $zoom = new LaravelZoom($zoomKey, $zoomSecret);
        $token = $zoom->getJWTToken(time() + 36000);
        if (0 != count($allMeetingRecords)) {
            foreach ($allMeetingRecords as $meetings) {
                $meetingId = $meetings->meeting_id;
                //$recordingAll = $zoom->getRecordings('-ISK-roPRUyC3-3N5-AT_g', 10);
                $recordingAll = $zoom->getMeetingRecordings($meetingId);
                if ($recordingAll) {
                    if ('200' == $recordingAll[ 'status' ]) {
                        $recordingFiles = $recordingAll[ 'body' ][ 'recording_files' ];
                        if ($recordingFiles) {
                            $folderPath = public_path() . "/zoom/0/" . $meetings->id;
                            foreach ($recordingFiles as $recordings) {
                                if ('shared_screen_with_speaker_view' == $recordings[ 'recording_type' ]) {
                                    $fileName = $meetingId . '.mp4';
                                    $urlOfFile = $recordings[ 'download_url' ];
                                    $filePath = $folderPath . '/' . $fileName;
                                    if (!file_exists($filePath)) {
                                        mkdir($folderPath, 0777, true);
                                    }
                                    copy($urlOfFile, $filePath);
                                    $meetings->zoom_recording = $fileName;
                                    $meetings->save();
                                } else {
                                    if ('audio_only' == $recordings[ 'recording_type' ]) {
                                        $fileNameAudio = $meetingId . '-audio.mp4';
                                        if (!isset($filePath) || empty($filePath)) {
                                            $filePath = $folderPath . '/' . $fileNameAudio;
                                        }
                                        $filePathAudio = $folderPath . '/' . $fileNameAudio;
                                        $urlOfAudioFile = $recordings[ 'download_url' ];
                                        if (!file_exists($filePath)) {
                                            mkdir($folderPath, 0777, true);
                                        }
                                        copy($urlOfAudioFile, $filePathAudio);
                                    } else {
                                        // Not saving any other files currently
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        return true;
    }

    /**
     * Delete meeting recordings based on meeting id
     *
     * @return array $meeting
     * @Rest\Post("LaravelZoom")
     *
     * @uses LaravelZoom
     */
    public function deleteRecordings($zoomKey, $zoomSecret, $date)
    {
        $allMeetingRecords = ZoomMeetings::where('is_deleted_from_zoom', '!=', 1)->whereNotNull('zoom_recording')->whereNotNull('meeting_id')->whereDate('start_date_time', '<', $date)->get();
        $zoom = new LaravelZoom($zoomKey, $zoomSecret);
        $token = $zoom->getJWTToken(time() + 36000);
        if (0 != count($allMeetingRecords)) {
            foreach ($allMeetingRecords as $meetings) {
                $meetingId = $meetings->meeting_id;
                $folderPath = public_path() . "/zoom/0/" . $meetings->id;
                $fileName = $meetingId . '.mp4';
                $filePath = $folderPath . '/' . $fileName;
                $fileNameAudio = $meetingId . '-audio.mp4';
                $filePathAudio = $folderPath . '/' . $fileNameAudio;
                if (file_exists($filePath) && file_exists($filePathAudio)) {
                    $recordingDelete = $zoom->deleteRecordings($meetingId);
                    if ($recordingDelete && '204' == $recordingDelete[ 'status' ]) {
                        $meetings->is_deleted_from_zoom = 1;
                        $meetings->save();
                    }
                }

            }
        }
    }

    public function getUserDetails($user_id, $user_type)
    {
        switch ($user_type) {
            case 'vendor':
                $vendor = Vendor::find($user_id);
                return $vendor;
                break;
            case 'customer':
                $customer = Customer::find($user_id);
                return $customer;
                break;
            case 'supplier':
                $supplier = Supplier::find($user_id);
                return $supplier;
                break;
            default:
                break;
        }

    }
}