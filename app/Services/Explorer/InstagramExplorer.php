<?php

namespace App\Services\Explorer;

use InstagramAPI\Instagram;
use InstagramAPI\Signatures;

class InstagramExplorer {


    /**
     * @var Instagram $instagram
     */
    private $instagram;
    private $signature;
    private $topTags = 'luxurylifestyle luxurylife bmw luxurycars mercedes ferrari bhfyp fashionblogger inspiration millionaire Accessories Accessory Accessorize Jewelry Bling InstaJewelry HandmadeJewelry JewelryGram Jewels Necklace Bracelet Rings Earrings StatementJewelry JewelryAddict Diamond DiamondRing Purse Clutch FineJewelry Choker Silver Gold Birthstone InstaModel InstagramModel IGModel IGInfluencer InstagramInfluencer FashionInfluencer Model InfluencerStyle ModelInfluencer ModelLife Modeling FashionModel ModelSearch Photoshoot IGers Influencer ModelPhotography Portrait_IG FashionPhoto FashionPhotography FashionPhotographer Fashiongram FashionShoot FashionShow Photoshoot FashionStylist FashionModel FashionMagazine Photographer PhotoArt Shoes NewShoes Sneakers SneakerHead Boots Booties Heels HighHeels Kicks InstaKicks IGSneakerCommunity Kickstagram ShoePorn Shoestagram InstaShoes SoleCollector Shoegasm SoleOnFire NiceKicks RedBottoms ShoeStyle ShoeGame';
    private $similarhashtags = [];

    public function loginToInstagram() {
        $instagram = new Instagram();
        $instagram->login('sololuxury.official', "NcG}4u'z;Fm7");

        $this->instagram = $instagram;
        $this->signature = Signatures::generateUUID();
    }

    public function getSimilarHashtags() {
        $topTags = explode(' ', $this->topTags);

        $hashtags = [];

        foreach ($topTags as $key =>$tag) {
            $hashtagResults = $this->instagram->hashtag->getInfo($tag)->asArray();
            $relatedHashtags = $this->instagram->hashtag->getRelated($tag)->asArray();
            $hashtags[$tag] = [
                'related_hashtags' => $relatedHashtags['related'],
                'media_count' => $hashtagResults['media_count']
            ];
        }

        $this->similarhashtags = $hashtags;
    }



}