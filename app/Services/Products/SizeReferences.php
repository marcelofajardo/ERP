<?php

namespace App\Services\Products;

class SizeReferences {

    private $woman;
    private $man;

    private $supplierSizes = [
        '' => ''
    ];

    public function refineForFemaleUSShoes($product): void
    {
        $sizes = (string) $product->size;
        $sizes = explode(',', $sizes);
        $finalSizes = [];

        $category = $product->product_category ? $product->product_category->id : 'N/A';
        if (!isset($this->woman[$category])) {
            $category = $product->product_category->parent_id;
        }
        if (!isset($this->woman[$category])) {
            return;
        }

        dump($product->brand);

        foreach ($sizes as $size) {
            $size = str_replace(
                array('US', 'us', 'Us', 'M', 'm', '++', '+'),
                array('', '', '', '.5', '.5', '.5', '.5'), $size);
            $size = trim($size);
            if ($size < 30) {
                if (isset($this->woman[$category]['US'][$size])) {
                    $size = $this->woman[$category]['US'][$size] ?? $size;
                }
            }
            $finalSizes[] = $size;
        }

        $finalSizes = implode(',', $finalSizes);
        $product->size = $finalSizes;
        $product->save();
    }

    public function refineForMaleUSShoes($product) {
        $sizes = (string) $product->size;
        $sizes = explode(',', $sizes);
        $finalSizes = [];

        $category = $product->product_category ? $product->product_category->id : 'N/A';
        if (!isset($this->man[$category])) {
            $category = $product->product_category->parent_id;
        }
        if (!isset($this->man[$category])) {
            return;
        }

        dump($product->brand);


        foreach ($sizes as $size) {
            $size = str_replace(
                array('US', 'us', 'Us', 'M', 'm', '++', '+'),
                array('', '', '', '.5', '.5', '.5', '.5'), $size);
            $size = trim($size);
            if ($size < 30) {
                if (isset($this->man[$category]['US'][$size])) {
                    $size = $this->man[$category]['US'][$size] ?? $size;
                }
            }
            $finalSizes[] = $size;
        }

        $finalSizes = implode(',', $finalSizes);
        $product->size = $finalSizes;
        $product->save();
    }

    public function refineForFemaleUKShoes($product): void
    {
        $sizes = (string) $product->size;
        $sizes = explode(',', $sizes);
        $finalSizes = [];

        $category = $product->product_category ? $product->product_category->id : 'N/A';
        if (!isset($this->woman[$category])) {
            $category = $product->product_category->parent_id;
        }
        if (!isset($this->woman[$category])) {
            return;
        }


        foreach ($sizes as $size) {
            $size = str_replace(
                array('UK', 'uk', 'Uk', 'M', 'm', '++', '+'),
                array('', '', '', '.5', '.5', '.5', '.5'), $size);
            $size = trim($size);
            dump($size);
            if ($size < 30) {
                if (isset($this->woman[$category]['UK'][$size])) {
                    $size = $this->woman[$category]['UK'][$size] ?? $size;
                }
            }
            $finalSizes[] = $size;
        }

        $finalSizes = implode(',', $finalSizes);
        $product->size = $finalSizes;
        $product->save();
    }

    public function refineForMaleUKShoes($product) {
        $sizes = (string) $product->size;
        $sizes = explode(',', $sizes);
        $finalSizes = [];

        $category = $product->product_category ? $product->product_category->id : 'N/A';
        if (!isset($this->man[$category])) {
            $category = $product->product_category->parent_id;
        }

        if (!isset($this->man[$category])) {
            return;
        }



        foreach ($sizes as $size) {
            $size = str_replace(
                array('UK', 'uk', 'Uk', 'M', 'm', '++', '+'),
                array('', '', '', '.5', '.5', '.5', '.5'), $size);
            $size = trim($size);
            if ($size < 30) {
                if (isset($this->man[$category]['UK'][$size])) {
                    $size = $this->man[$category]['UK'][$size] ?? $size;
                }
            }
            $finalSizes[] = $size;
        }

        $finalSizes = implode(',', $finalSizes);
        $product->size = $finalSizes;
        $product->save();
    }

    public function basicRefining($product) {
        $sizes = (string) $product->size;
        $sizes = explode(',', $sizes);
        $finalSizes = [];
        foreach ($sizes as $size) {
            $size = trim($size);
            if ($size != 0 && $size != 1) {
                $finalSizes[] = trim($size);
            }
        }

        $finalSizes  = implode(',', $finalSizes);
        $product->size = $finalSizes;
        $product->save();
    }

    public function refineSizeToPintFive($product): void
    {
        $size = (string) $product->size;
        $size = str_replace(['+', '½', '0.5','½'], '.5', $size);

        if(1 === preg_match('~[0-9]~', $size)){
            $size = str_replace(['M', 'm'], '.5', $size);
        }

        $product->size = $size;
        dump($size);
        $product->save();
    }

    public function refineForFr($product) {
        $sizes = strtolower($product->size);
        if (stripos($sizes, 'fr') === false) {
            return;
        }
        $category = $product->product_category ? $product->product_category->id : 'N/A';
        if (!isset($this->woman[$category])) {
            $category = $product->product_category->parent_id;
        }
        if (!isset($this->woman[$category])) {
            return;
        }

        $sizes = explode(',', $sizes);
        $finalSizing = [];
        foreach ($sizes as $size) {
            $size = trim(str_replace(['FR', 'fr'], '', $size));
            if (isset($this->woman[$category]['FR'][$size])) {
                $size = $this->woman[$category]['FR'][$size];
            }
            $finalSizing[] = $size;
        }

        $finalSizing = implode(',', $finalSizing);
        $product->size = $finalSizing;
        dump($finalSizing, '===============================================');
        $product->save();

    }

    public function refineSizeForIt($product) {
        $sizes = $product->size;
        $sizes = explode(',', $sizes);
        $finalSize = [];
        foreach ($sizes as $size) {
            $finalSize[] = trim(str_replace(['IT', 'it'], '', $size));
        }
        $finalSize = implode(',', $finalSize);
        $product->size = $finalSize;
        dump($finalSize);
        $product->save();
    }

    public function getSizeWithReferenceRoman($product) {
        $sizes = $product->size;
        $sizes = explode(',', $sizes);
        $finalSizes = [];
        foreach ($sizes as $size) {
            $size = trim($size);
            if (isset($this->uni[$size])) {
                $size = $this->uni[$size];
            }
            $finalSizes[] = $size;
        }

        $finalSizes = implode(',', $finalSizes);
        $product->size = $finalSizes;
        dump($finalSizes);
        $product->save();
    }

    public function __construct()
    {
        $this->woman[41] = [
            'UK' => [
                '2' => '35',
                '2.5' => '35.5',
                '3' => '36',
                '3.5' => '36.5',
                '4' => '37',
                '4.5' => '37.5',
                '5' => '38',
                '5.5' => '38.5',
                '6' => '39',
                '6.5' => '39.5',
                '7' => '40',
                '7.5' => '40.5',
                '8' => '41',
                '8.5' => '41.5',
                '9' => '42',
            ],
            'US' => [
                '5' => '35',
                '5.5' => '35.5',
                '6' => '36',
                '6.5' => '36.5',
                '7' => '37',
                '7.5' => '37.5',
                '8' => '38',
                '8.5' => '38.5',
                '9' => '39',
                '9.5' => '39.5',
                '10' => '40',
                '10.5' => '40.5',
                '11' => '41',
                '11.5' => '41.5',
                '12' => '42',
            ],
            'JP' => [
                '22' => '35',
                '22.5' => '35.5',
                '23' => '36',
                '23.5' => '7',
                '24' => '37.5',
                '24.5' => '38',
                '25' => '39',
                '25.5' => '39.5',
                '26' => '40',
                '26.5' => '40.5',
                '27' => '41',
                '27.5' => '41.5',
                '28' => '42',
            ],
            'FR' => [
                '36' => '35',
                '36.5' => '35.5',
                '37' => '36',
                '37.5' => '36.5',
                '38' => '37',
                '38.5' => '37.5',
                '39' => '38',
                '39.5' => '38.5',
                '40' => '39',
                '40.5' => '39.5',
                '41' => '40',
                '41.5' => '40.5',
                '42' => '41',
                '42.5' => '41.5',
                '43' => '42',
            ]
        ];
        $this->man[5] = [
            'UK' => [
                '4' => '38',
                '4.5' => '38.5',
                '5' => '39',
                '5.5' => '39.5',
                '6' => '40',
                '6.5' => '40.5',
                '7' => '41',
                '7.5' => '41.5',
                '8' => '42',
                '8.5' => '42.5',
                '9' => '43',
                '9.5' => '43.5',
                '10' => '44',
                '10.5' => '44.5',
                '11' => '45',
                '11.5' => '45.5',
                '12' => '46',
                '12.5' => '46.5',
                '13' => '47',
                '13.5' => '47.5',
                '14' => '48',
            ],
            'US' => [
                '5' => '38',
                '5.5' => '38.5',
                '6' => '39',
                '6.5' => '39.5',
                '7' => '40',
                '7.5' => '40.5',
                '8' => '41',
                '8.5' => '41.5',
                '9' => '42',
                '9.5' => '42.5',
                '10' => '43',
                '10.5' => '43.5',
                '11' => '44',
                '11.5' => '44.5',
                '12' => '45',
                '12.5' => '45.5',
                '13' => '46',
                '13.5' => '46.5',
                '14' => '47',
                '14.5' => '47.5',
                '15' => '48',
            ],
            'JP' => [
                '22' => '35',
                '22.5' => '35.5',
                '23' => '36',
                '23.5' => '7',
                '24' => '37.5',
                '24.5' => '38',
                '25' => '39',
                '25.5' => '39.5',
                '26' => '40',
                '26.5' => '40.5',
                '27' => '41',
                '27.5' => '41.5',
                '28' => '42',
            ],
            'FR' => [
                '36' => '35',
                '36.5' => '35.5',
                '37' => '36',
                '37.5' => '36.5',
                '38' => '37',
                '38.5' => '37.5',
                '39' => '38',
                '39.5' => '38.5',
                '40' => '39',
                '40.5' => '39.5',
                '41' => '40',
                '41.5' => '40.5',
                '42' => '41',
                '42.5' => '41.5',
                '43' => '42',
            ]
        ];
        $this->uni = [
            '000' => '40',
            '00' => '42',
            '0' => '44',
            'I' => '46',
            'II' => '48',
            'III' => '50',
            'IV' => '52',
            'V' => '54',
            'VI' => '56',
            '000/0' => '40',
            '00/0' => '42',
            '0/0' => '44',
            '1/I' => '46',
            '2/II' => '48',
            '3/III' => '50',
            '4/IV' => '52',
            '5/V' => '54',
            '6/VI' => '56',
        ];
    }
}