<?php

namespace App\Console\Commands;

use App\Agent;
use App\CronJobReport;
use App\Designer;
use App\Supplier;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ImportFarfetchSupplier extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'farfetch:import-suppliers';

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

            $suppliers = Designer::all();

            foreach ($suppliers as $supplier) {
                $existingSupplier = Supplier::where('supplier', trim($supplier->title))->first();

                $this->info($supplier->title . 'exists');

                if ($existingSupplier) {
                    $brands                = $supplier->designers;
                    $brands                = str_replace('"[', '', $brands);
                    $brands                = str_replace(']"', '', $brands);
                    $brands                = explode(',', $brands);
                    $existingSupplierBrand = $existingSupplier->brands;
                    $existingSupplierBrand = str_replace('"[', '', $existingSupplierBrand);
                    $existingSupplierBrand = str_replace(']"', '', $existingSupplierBrand);
                    $explodedExistingBrand = explode(',', $existingSupplierBrand);
                    foreach ($brands as $brand) {
                        $brand = trim($brand);
                        if (stripos(strtoupper($existingSupplierBrand), strtoupper($brand)) === false) {
                            $explodedExistingBrand[] = $brand;
                        }
                    }
                    $imploded = implode(',', $explodedExistingBrand);
                    $imploded = '"[' . $imploded . ']"';

                    if (trim($supplier->social_handle) !== trim($existingSupplier->social_handle)) {
                        $existingSupplier->social_handle = $existingSupplier->social_handle . ', ' . trim($supplier->social_handle);
                    }

                    if (trim($supplier->instagram_handle) !== trim($existingSupplier->instagram_handle)) {
                        $existingSupplier->instagram_handle = $existingSupplier->instagram_handle . ', ' . trim($supplier->instagram_handle);
                    }

                    if (!$existingSupplier->address) {
                        $existingSupplier->address = $supplier->address;
                    }

                    if (!$existingSupplier->email) {
                        $existingSupplier->email = $supplier->email;
                    }

                    if (!$existingSupplier->phone) {
                        $existingSupplier->phone = $supplier->phone;
                    }

                    if (!$existingSupplier->website) {
                        $existingSupplier->website = $supplier->site_link;
                    }

                    $existingSupplier->brands = $imploded;
                    $existingSupplier->save();

                    $agentPhone = $existingSupplier->agents()->where('phone', $supplier->phone)->first();
                    if (!$agentPhone) {
                        $agent             = new Agent();
                        $agent->model_id   = $existingSupplier->id;
                        $agent->model_type = 'App\Supplier';
                        $agent->name       = 'N/A';
                        $agent->phone      = trim($supplier->phone);
                        $agent->email      = trim($supplier->email);
                        $agent->save();

                        continue;
                    }

                    $email = $existingSupplier->agents()->where('email', $supplier->email)->first();
                    if (!$email) {
                        $agent             = new Agent();
                        $agent->model_id   = $existingSupplier->id;
                        $agent->model_type = 'App\Supplier';
                        $agent->name       = 'N/A';
                        $agent->phone      = trim($supplier->phone);
                        $agent->email      = trim($supplier->email);
                        $agent->save();
                    }

                    continue;

                }

                $this->info('CREATING NEW');

                $existingSupplier                   = new Supplier();
                $existingSupplier->source           = $supplier->website;
                $existingSupplier->supplier         = $supplier->title;
                $existingSupplier->brands           = $supplier->designers;
                $existingSupplier->address          = $supplier->address;
                $existingSupplier->email            = $supplier->email;
                $existingSupplier->phone            = $supplier->phone;
                $existingSupplier->website          = $supplier->site_link;
                $existingSupplier->social_handle    = $supplier->social_handle;
                $existingSupplier->instagram_handle = $supplier->instagram_handle;
                $existingSupplier->save();
            }

            $report->update(['end_time' => Carbon::now()]);
        } catch (\Exception $e) {
            \App\CronJob::insertLastError($this->signature, $e->getMessage());
        }
    }
}
