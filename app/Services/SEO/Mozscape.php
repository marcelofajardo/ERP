<?php

/*
 * Author : Ashan Ghimire
 * Date : 2019-07-23
 * Description : Wrapper for SEOstats/Mozscape package for Mozscape Analyrics
 */

namespace App\Services\SEO;

use SEOstats\Services\Mozscape as Moz;

class Mozscape extends Moz{


    /*
     * @params $url - (string|false)
     * @returns int
     */
    public static function getDomainAuthority($url = false){
        return parent::getDomainAuthority($url);
    }

    /*
     * @params $url - (string|false)
     * @returns int
     */
    public static function getPageAuthority($url = false){
        return parent::getPageAuthority($url);
    }

    /*
     * @params $url - (string|false)
     * @returns int
     */
    public static function getEquityLinkCount($url = false){
        return parent::getEquityLinkCount($url);
    }

    /*
     * @params $url - (string|false)
     * @returns int
     */
    public static function getExternalEquityLinkCount($url = false){
        $data = static::getCols('32', $url);
        return (parent::noDataDefaultValue() == $data) ? $data :
            $data['ueid'];
    }


    /*
     * @params $url - (string|false)
     * @returns int
     */
    public static function getRankingKeywordCount($url = false){
        $data = static::getCols('32', $url);
        return (parent::noDataDefaultValue() == $data) ? $data :
            $data['ueid'];
    }

    /*
     * @params $url - (string|false)
     * @returns array
     */
    public static function getSiteDetails($url = false){
        return [
            'domain_authority' => static::getDomainAuthority($url),
            'linking_authority' => static::getLinkCount($url),
            'inbound_links' => static::getExternalEquityLinkCount($url),
            'ranking_keywords' => static::getRankingKeywordCount($url)
        ];
    }



}