<?php


namespace App\Services\Listing;


use GuzzleHttp\Client;
use Wa72\HtmlPageDom\HtmlPageCrawler;

class Scrapper
{
    private $request;
    public function __construct(Client $client)
    {
        $this->request = $client;
    }

    public function getFromFarfetch($product) {
        $skus = $product->many_scraped_products()->pluck('original_sku')->toArray();
        $skus[] = $product->sku;

        try {
            $response = $this->request->post('http://104.207.139.74/farfetch', [
                'form_params' => [
                    'id' => $product->id,
                    'brand' => $product->brands->name,
                    'supplier' => $product->suppliers()->pluck('supplier')->toArray(),
                    'sku' => $skus
                ],
            ]);
        } catch (\Exception $exception) {
            return false;
        }

        $data = json_decode($response->getBody()->getContents(), true);

        if (!isset($data['description'])) {
            return false;
        }

        $product->short_description = $data['description'];
        $product->composition = $data['material_used'];
        if ($data['dimension'] !== []) {
            $product->lmeasurement = $data['dimension'][0] ?? $product->lmeasurement;
            $product->hmeasurement = $data['dimension'][1] ?? $product->hmeasurement;
            $product->dmeasurement = $data['dimension'][2] ?? $product->dmeasurement;
            $product->save();
        }
        $product->color = $data['color'] ?? $product->color;

        $product->save();

        return true;

    }
}