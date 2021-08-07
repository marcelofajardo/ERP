<?php


namespace App\Services\Listing;


use App\AttributeReplacement;
use App\Brand;
use App\Product;
use App\Services\Grammar\GrammarBot;

class ShortDescriptionChecker implements CheckerInterface
{

    private $grammerBot;

    public function __construct(GrammarBot $bot)
    {
        $this->grammerBot = $bot;
    }

    public function check($product): bool {
        $data = $product->short_description;
//        dump($data);
        if (strlen($data) < 60) {
            return false;
        }
        $data = $this->improvise($data);
        $product->short_description = $data;
        $product->save();
        $state = $this->grammerBot->validate($data);

        if ($state !== false) {
            $product->short_description = $state;
            $product->save();

            return true;
        }

        return false;

    }

    public function improvise($sentence, $data2 = null): string
    {

        //Remove words that needs to be removed...
        $sentence = strtolower($sentence);
        $replacements = AttributeReplacement::where('field_identifier', 'short_description')->get();
        foreach ($replacements as $replacement) {
            $sentence = str_replace(strtolower($replacement->first_term), $replacement->replacement_term, $sentence);
        }

        //Now remove special characters..
        $characters = array (
            "\n",
            '\n',
            '&excl;',
            '&quot;',
            '&num;',
            '&dollar;',
            '&percnt;',
            '&amp;',
            '&apos;',
            '&lpar;',
            '&rpar;',
            '&ast;',
            '&plus;',
            '&comma;',
            '&sol;',
            '&colon;',
            '&semi;',
            '&lt;',
            '&equals;',
            '&gt;',
            '&nbsp;',
            '&quest;',
            '&commat;',
            '&lbrack;',
            '&bsol;',
            '&rsqb;',
            '&Hat;',
            '&hat;',
            '&lowbar;',
            '&grave;',
            '&lbrace;',
            '&vert;',
            '&rcub;',
            '&sect;',
            '&copy;',
            '&para;',
            '\\',
            '/',
            '-'
        )
        ;

        $sentence = strtolower($sentence);

        $sentence = str_replace($characters, ' ', $sentence);
        $sentence = str_replace("&rsquo;", "'", $sentence);
        $sentence = str_replace("&Eacute;", "E", $sentence);
        $sentence = str_replace("&eacute;", "e", $sentence);


        $thingsToRemove = ['Made In', 'Italy', 'Portugal', 'London', 'Madein'];

        foreach ($thingsToRemove as $rem) {
            $sentence = str_replace([strtoupper($rem), strtolower($rem), $rem], '', $sentence);
        }

        $sentence = preg_replace('/(\d+)% (\w+)/', '', $sentence);
        $sentence = preg_replace('/(\d+)%(\w+)/', '', $sentence);
        $sentence = preg_replace('/(\d+)(\w+)/', '', $sentence);


        return $this->sentenceCase($sentence);
    }

    private function sentenceCase($string) {
        $sentences = preg_split('/([.?!]+)/', $string, -1,PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE);
        $newString = '';
        foreach ($sentences as $key => $sentence) {
            $newString .= ($key & 1) == 0?
                ucfirst(strtolower(trim($sentence))) :
                $sentence.' ';
        }
        return trim($newString);
    }
}