<?php

namespace App\Console\Commands;

use App\CronJobReport;
use App\Services\Bots\Prada;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class SaveImageOnServer extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'save:image-to-server';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    protected $country;
    protected $IP;

    public function handle(): void
    {
        try {
            $report = CronJobReport::create([
                'signature'  => $this->signature,
                'start_time' => Carbon::now(),
            ]);

            $this->authenticate();

            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }

    private function authenticate()
    {
        $url = 'http://shop.cuccuini.it/it/register.html';

        $duskShell = new Prada(new Client());
        $this->setCountry('IT');
        $duskShell->prepare();

        try {
            $content = $duskShell->emulate($this, $url, '');
        } catch (Exception $exception) {
            $content = ['', ''];
        }
    }

    private function setCountry(): void
    {

        $this->country = 'IT';
    }

    private function setIP(): void
    {
        $this->IP = '5.61.4.70  ' . ':' . '8080';
    }
}
