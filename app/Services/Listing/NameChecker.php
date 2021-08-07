<?php


namespace App\Services\Listing;


use App\AttributeReplacement;
use App\Brand;
use App\Colors;
use App\Product;
use App\Services\Grammar\GrammarBot;

class NameChecker implements CheckerInterface
{

    private $grammerBot;

    public function __construct(GrammarBot $bot)
    {
        $this->grammerBot = $bot;
    }

    public function check($product): bool {
        $data = $product->name;
        $data = $this->improvise($data);
        $product->name = $data;
        $product->save();
        $state = $this->grammerBot->validate($data);


        if ($state === false) {
            return false;
        }

        $product->name = $state;
        $product->save();

        return true;
    }

    public function improvise($sentence, $data2 = null): string
    {
        $sentence = strtolower($sentence);
        $replacements = AttributeReplacement::where('field_identifier', 'name')->get();
        foreach ($replacements as $replacement) {
            $sentence = str_replace(strtolower($replacement->first_term), $replacement->replacement_term, $sentence);
        }

        $sentence = strtoupper($sentence);
        $brands = Brand::whereNull('deleted_at')->get();

        foreach ($brands as $brand) {
            $sentence = str_replace(strtoupper($brand->name), '', $sentence);
            $brand->name = str_replace("'", '', $brand->name);
            $sentence = str_replace(strtoupper($brand->name), '', $sentence);
        }

        $colors = (new Colors)->all();

        $sentence = title_case($sentence);
        $sentence = str_replace($colors, '', $sentence);

        return title_case($sentence);
    }
}