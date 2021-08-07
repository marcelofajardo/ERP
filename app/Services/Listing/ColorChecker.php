<?php


namespace App\Services\Listing;


use App\ColorReference;
use App\Colors;

class ColorChecker implements CheckerInterface
{

    private $availableColors;
    private $colorTracks;

    public function __construct()
    {
        $this->setAvailableColors();
        $this->setColorTracks();
    }

    public function check($product): bool {
        $color = title_case($product->color);
        dump('COL...'. $color);
        if (in_array($color, $this->availableColors, false)) {
            $product->color = $color;
            $product->save();
            return true;
        }

        $color = $this->improvise($product->name);
        dump('sec_'. $color);

        if (in_array($color, $this->availableColors, false)) {
            $product->color = title_case($color);
            $product->save();
            return true;
        }

        $color = $this->improvise($product->short_description);
        dump('third_'. $color);


        if (in_array($color, $this->availableColors, false)) {
            $product->color = title_case($color);
            $product->save();
            return true;
        }

        return false;
    }

    public function improvise($data, $data2 = null)
    {
        foreach ($this->availableColors as $color) {
            if (stripos(strtoupper($data), strtoupper($color)) !== false) {
                return $color;
            }
        }

        foreach ($this->colorTracks as $colorReference) {
            if (stripos(strtoupper($data), strtoupper($colorReference->original_color)) !== false) {
                return $colorReference->erp_color;
            }
        }

        return false;
    }


    public function setAvailableColors(): void
    {
        $this->availableColors = (new Colors)->all();
    }

    private function setColorTracks()
    {
        $this->colorTracks = ColorReference::all();
    }

}