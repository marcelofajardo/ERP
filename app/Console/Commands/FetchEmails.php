<?php

namespace App\Console\Commands;

use App\CronJobReport;
use App\Email;
use App\Supplier;
use Carbon\Carbon;
use Illuminate\Console\Command;
use seo2websites\ErpExcelImporter\ErpExcelImporter;
use Webklex\IMAP\Client;

class FetchEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:emails';

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
        try {
            $report = CronJobReport::create([
                'signature'  => $this->signature,
                'start_time' => Carbon::now(),
            ]);

            $imap = new Client([
                'host'          => env('IMAP_HOST_PURCHASE'),
                'port'          => env('IMAP_PORT_PURCHASE'),
                'encryption'    => env('IMAP_ENCRYPTION_PURCHASE'),
                'validate_cert' => env('IMAP_VALIDATE_CERT_PURCHASE'),
                'username'      => env('IMAP_USERNAME_PURCHASE'),
                'password'      => env('IMAP_PASSWORD_PURCHASE'),
                'protocol'      => env('IMAP_PROTOCOL_PURCHASE'),
            ]);

            $imap->connect();

            // $supplier = Supplier::find($request->supplier_id);
            $suppliers = Supplier::whereHas('Agents')->orWhereNotNull('email')->get();

            dump(count($suppliers));

            $types = [
                'inbox' => [
                    'inbox_name' => 'INBOX',
                    'direction'  => 'from',
                    'type'       => 'incoming',
                ],
                'sent'  => [
                    'inbox_name' => 'INBOX.Sent',
                    'direction'  => 'to',
                    'type'       => 'outgoing',
                ],
            ];

            foreach ($suppliers as $supplier) {
                foreach ($types as $type) {
                    dump($type['type']);
                    $inbox        = $imap->getFolder($type['inbox_name']);
                    $latest_email = Email::where('type', $type['type'])->where('model_id', $supplier->id)->where(function ($query) {
                        $query->where('model_type', 'App\Supplier')->orWhere('model_type', 'App\Purchase');
                    })->latest()->first();

                    if ($latest_email) {
                        $latest_email_date = Carbon::parse($latest_email->created_at);
                    } else {
                        $latest_email_date = Carbon::parse('1990-01-01');
                    }

                    dump($latest_email_date);

                    if ($supplier->agents()->count() > 0) {
                        if ($supplier->agents()->count() > 1) {
                            dump('Multiple Agents');

                            foreach ($supplier->agents as $key => $agent) {
                                if ($key == 0) {
                                    $emails = $inbox->messages()->where($type['direction'], $agent->email)->where([
                                        ['SINCE', $latest_email_date->format('d M y H:i')],
                                    ]);
                                    // $emails = $emails->setFetchFlags(false)
                                    //                 ->setFetchBody(false)
                                    //                 ->setFetchAttachment(false)->leaveUnread()->get();

                                    $emails = $emails->leaveUnread()->get();

                                    foreach ($emails as $email) {
                                        if ($email->hasHTMLBody()) {
                                            $content = $email->getHTMLBody();
                                        } else {
                                            $content = $email->getTextBody();
                                        }

                                        if ($email->getDate()->format('Y-m-d H:i:s') > $latest_email_date->format('Y-m-d H:i:s')) {
                                            dump('NEW EMAIL First');
                                            $attachments_array = [];
                                            $attachments       = $email->getAttachments();

                                            $attachments->each(function ($attachment) use (&$attachments_array, $supplier) {
                                                $attachment->name = preg_replace("/[^a-z0-9\_\-\.]/i", '', $attachment->name);
                                                file_put_contents(storage_path('app/files/email-attachments/' . $attachment->name), $attachment->content);
                                                $path = "email-attachments/" . $attachment->name;

                                                if ($attachment->getExtension() == 'xlsx' || $attachment->getExtension() == 'xls') {
                                                    if (class_exists('\\seo2websites\\ErpExcelImporter\\ErpExcelImporter')) {
                                                        $excel = $supplier->getSupplierExcelFromSupplierEmail();
                                                        ErpExcelImporter::excelFileProcess($attachment->name, $excel, $supplier->email);
                                                    }
                                                } elseif ($attachment->getExtension() == 'zip') {
                                                    if (class_exists('\\seo2websites\\ErpExcelImporter\\ErpExcelImporter')) {
                                                        $excel             = $supplier->getSupplierExcelFromSupplierEmail();
                                                        $attachments       = ErpExcelImporter::excelZipProcess($attachment, $attachment->name, $excel, $supplier->email, $attachments_array);
                                                        $attachments_array = $attachments;
                                                    }
                                                }

                                                $attachments_array[] = $path;
                                            });

                                            $params = [
                                                'model_id'        => $supplier->id,
                                                'model_type'      => Supplier::class,
                                                'type'            => $type['type'],
                                                'seen'            => $email->getFlags()['seen'],
                                                'from'            => $email->getFrom()[0]->mail,
                                                'to'              => array_key_exists(0, $email->getTo()) ? $email->getTo()[0]->mail : $email->getReplyTo()[0]->mail,
                                                'subject'         => $email->getSubject(),
                                                'message'         => $content,
                                                'template'        => 'customer-simple',
                                                'additional_data' => json_encode(['attachment' => $attachments_array]),
                                                'created_at'      => $email->getDate(),
                                            ];

                                            Email::create($params);
                                        }
                                    }
                                } else {
                                    $additional = $inbox->messages()->where($type['direction'], $agent->email)->since(Carbon::parse($latest_email_date)->format('Y-m-d H:i:s'));
                                    // $additional = $additional->setFetchFlags(false)
                                    //                 ->setFetchBody(false)
                                    //                 ->setFetchAttachment(false)->leaveUnread()->get();

                                    $additional = $additional->leaveUnread()->get();

                                    foreach ($additional as $email) {
                                        if ($email->hasHTMLBody()) {
                                            $content = $email->getHTMLBody();
                                        } else {
                                            $content = $email->getTextBody();
                                        }

                                        if ($email->getDate()->format('Y-m-d H:i:s') > $latest_email_date->format('Y-m-d H:i:s')) {
                                            dump('NEW EMAIL Second');

                                            $attachments_array = [];
                                            $attachments       = $email->getAttachments();

                                            $attachments->each(function ($attachment) use (&$attachments_array, $supplier) {
                                                $attachment->name = preg_replace("/[^a-z0-9\_\-\.]/i", '', $attachment->name);
                                                file_put_contents(storage_path('app/files/email-attachments/' . $attachment->name), $attachment->content);
                                                $path = "email-attachments/" . $attachment->name;

                                                if ($attachment->getExtension() == 'xlsx' || $attachment->getExtension() == 'xls') {
                                                    if (class_exists('\\seo2websites\\ErpExcelImporter\\ErpExcelImporter')) {
                                                        $excel = $supplier->getSupplierExcelFromSupplierEmail();
                                                        ErpExcelImporter::excelFileProcess($attachment->name, $excel, $supplier->email);
                                                    }
                                                } elseif ($attachment->getExtension() == 'zip') {
                                                    if (class_exists('\\seo2websites\\ErpExcelImporter\\ErpExcelImporter')) {
                                                        $excel             = $supplier->getSupplierExcelFromSupplierEmail();
                                                        $attachments       = ErpExcelImporter::excelZipProcess($attachment, $attachment->name, $excel, $supplier->email, $attachments_array);
                                                        $attachments_array = $attachments;
                                                    }
                                                }

                                                $attachments_array[] = $path;
                                            });

                                            $params = [
                                                'model_id'        => $supplier->id,
                                                'model_type'      => Supplier::class,
                                                'type'            => $type['type'],
                                                'seen'            => $email->getFlags()['seen'],
                                                'from'            => $email->getFrom()[0]->mail,
                                                'to'              => array_key_exists(0, $email->getTo()) ? $email->getTo()[0]->mail : $email->getReplyTo()[0]->mail,
                                                'subject'         => $email->getSubject(),
                                                'message'         => $content,
                                                'template'        => 'customer-simple',
                                                'additional_data' => json_encode(['attachment' => $attachments_array]),
                                                'created_at'      => $email->getDate(),
                                            ];

                                            Email::create($params);
                                        }
                                    }

                                    $emails = $emails->merge($additional);
                                }
                            }
                        } else if ($supplier->agents()->count() == 1) {
                            dump('1 Agent');

                            $emails = $inbox->messages()->where($type['direction'], $supplier->agents[0]->email)->since(Carbon::parse($latest_email_date)->format('Y-m-d H:i:s'));

                            $emails = $emails->leaveUnread()->get();

                            foreach ($emails as $email) {
                                if ($email->hasHTMLBody()) {
                                    $content = $email->getHTMLBody();
                                } else {
                                    $content = $email->getTextBody();
                                }

                                if ($email->getDate()->format('Y-m-d H:i:s') > $latest_email_date->format('Y-m-d H:i:s')) {
                                    dump('NEW EMAIL third');

                                    $attachments_array = [];
                                    $attachments       = $email->getAttachments();

                                    $attachments->each(function ($attachment) use (&$attachments_array, $supplier) {
                                        $attachment->name = preg_replace("/[^a-z0-9\_\-\.]/i", '', $attachment->name);

                                        file_put_contents(storage_path('app/files/email-attachments/' . $attachment->name), $attachment->content);
                                        $path = "email-attachments/" . $attachment->name;

                                        if ($attachment->getExtension() == 'xlsx' || $attachment->getExtension() == 'xls') {
                                            if (class_exists('\\seo2websites\\ErpExcelImporter\\ErpExcelImporter')) {
                                                $excel = $supplier->getSupplierExcelFromSupplierEmail();
                                                ErpExcelImporter::excelFileProcess($attachment->name, $excel, $supplier->email);
                                            }
                                        } elseif ($attachment->getExtension() == 'zip') {
                                            if (class_exists('\\seo2websites\\ErpExcelImporter\\ErpExcelImporter')) {
                                                $excel             = $supplier->getSupplierExcelFromSupplierEmail();
                                                $attachments       = ErpExcelImporter::excelZipProcess($attachment, $attachment->name, $excel, $supplier->email, $attachments_array);
                                                $attachments_array = $attachments;
                                            }
                                        }
                                        $attachments_array[] = $path;
                                    });

                                    $params = [
                                        'model_id'        => $supplier->id,
                                        'model_type'      => Supplier::class,
                                        'type'            => $type['type'],
                                        'seen'            => $email->getFlags()['seen'],
                                        'from'            => $email->getFrom()[0]->mail,
                                        'to'              => array_key_exists(0, $email->getTo()) ? $email->getTo()[0]->mail : $email->getReplyTo()[0]->mail,
                                        'subject'         => $email->getSubject(),
                                        'message'         => $content,
                                        'template'        => 'customer-simple',
                                        'additional_data' => json_encode(['attachment' => $attachments_array]),
                                        'created_at'      => $email->getDate(),
                                    ];

                                    Email::create($params);
                                }
                            }
                        } else {
                            dump('No Agents Emails');

                            $emails = $inbox->messages()->where($type['direction'], 'nonexisting@email.com');
                            $emails = $emails->setFetchFlags(false)
                                ->setFetchBody(false)
                                ->setFetchAttachment(false)->leaveUnread()->get();
                        }
                    } else {
                        dump('No Agent just Supplier emails');

                        $emails = $inbox->messages()->where($type['direction'], $supplier->email)->since(Carbon::parse($latest_email_date)->format('Y-m-d H:i:s'));

                        $emails = $emails->leaveUnread()->get();

                        foreach ($emails as $email) {
                            if ($email->hasHTMLBody()) {
                                $content = $email->getHTMLBody();
                            } else {
                                $content = $email->getTextBody();
                            }

                            if ($email->getDate()->format('Y-m-d H:i:s') > $latest_email_date->format('Y-m-d H:i:s')) {
                                //dump('NEW EMAIL fourth');

                                $attachments_array = [];
                                $attachments       = $email->getAttachments();

                                $attachments->each(function ($attachment) use (&$attachments_array, $supplier) {
                                    $attachment->name = preg_replace("/[^a-z0-9\_\-\.]/i", '', $attachment->name);
                                    file_put_contents(storage_path('app/files/email-attachments/' . $attachment->name), $attachment->content);
                                    $path = "email-attachments/" . $attachment->name;

                                    if ($attachment->getExtension() == 'xlsx' || $attachment->getExtension() == 'xls') {
                                        if (class_exists('\\seo2websites\\ErpExcelImporter\\ErpExcelImporter')) {
                                            $excel = $supplier->getSupplierExcelFromSupplierEmail();
                                            ErpExcelImporter::excelFileProcess($attachment->name, $excel, $supplier->email);
                                        }
                                    } elseif ($attachment->getExtension() == 'zip') {
                                        if (class_exists('\\seo2websites\\ErpExcelImporter\\ErpExcelImporter')) {
                                            $excel             = $supplier->getSupplierExcelFromSupplierEmail();
                                            $attachments       = ErpExcelImporter::excelZipProcess($attachment, $attachment->name, $excel, $supplier->email, $attachments_array);
                                            $attachments_array = $attachments;
                                        }
                                    }

                                    $attachments_array[] = $path;

                                });

                                $params = [
                                    'model_id'        => $supplier->id,
                                    'model_type'      => Supplier::class,
                                    'type'            => $type['type'],
                                    'seen'            => $email->getFlags()['seen'],
                                    'from'            => $email->getFrom()[0]->mail,
                                    'to'              => array_key_exists(0, $email->getTo()) ? $email->getTo()[0]->mail : $email->getReplyTo()[0]->mail,
                                    'subject'         => $email->getSubject(),
                                    'message'         => $content,
                                    'template'        => 'customer-simple',
                                    'additional_data' => json_encode(['attachment' => $attachments_array]),
                                    'created_at'      => $email->getDate(),
                                ];

                                Email::create($params);
                            }
                        }
                    }
                }

                dump('__________');
            }

            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }
}
