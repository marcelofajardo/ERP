<?php


namespace App\Services\Listing;


use App\Compositions;

class CompositionChecker implements CheckerInterface
{

    private $compositionReplacementData;

    public function __construct()
    {
        $this->setCompositionReplacementData();

    }

    public function check($product): bool {
        $data = $product->composition;

        dump('Real composition: ' . $data);
        $hasExtracted = preg_match_all('/(\d+)% (\w+)/', $data, $extractedData);

        if ($hasExtracted) {
            $extractedData = $extractedData[0];
            $extractedData = $this->improvise($extractedData);
            $extractedData = implode(', ', $extractedData);
            $product->composition = $extractedData;
            $product->save();
            dump($product->composition);
            return true;
        }

        $additionalData = $this->getCompositionFromList($data);
        dump($additionalData);
        if (0 === count($additionalData))
        {
            return false;
        }

        $additionalData = $this->improvise($additionalData);
        $additionalData = implode(', ', $additionalData);
        $product->composition = $additionalData;
        $product->save();

        dump($product->composition);

        return true;
    }

    public function improvise($data, $allCompositionNames = null): array
    {
        $correctedData = [];
        foreach ($data as $datum) {
            $rd = str_replace($this->compositionReplacementData[0], $this->compositionReplacementData[1], $datum);
            if (!in_array($rd, $correctedData, true)) {
                $correctedData[] = $rd;
            }
        }

        dump('corrected composition :' , $correctedData);

        return $correctedData;
    }


    private function setCompositionReplacementData(): void
    {
        $cs = Compositions::all();
        $original = [];
        $replaces = [];
        foreach ($cs as $c) {
            $original[] = $c->name;
            $replaces[] = $c->replace_with;
        }

        $this->compositionReplacementData = [$original, $replaces];

    }

    private function getCompositionFromList($properties): array
    {

        $compositionList = Compositions::pluck('name')->toArray();

        $allCompositions = [];

        foreach ($compositionList as $comp) {
            if (stripos($properties, $comp) !== false) {
                $allCompositions[] = $comp;
            }
        }

        return $allCompositions;

    }
}