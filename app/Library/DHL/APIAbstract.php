<?php

namespace App\Library\DHL;

abstract class APIAbstract
{
    protected $_stagingUrl    = 'https://wsbexpress.dhl.com/sndpt/expressRateBook';
    protected $_productionUrl = 'https://wsbexpress.dhl.com/gbl/expressRateBook';

    protected $document;
    protected $results;
    protected $resultsRAW;

    protected $username;
    protected $password;
    protected $accountNumber;

    protected $_mode;

    public function __construct()
    {
        // $this->username      = getenv('DHL_ID') ?: config('dhl.DHL_ID');
        // $this->password      = getenv('DHL_KEY') ?: config('dhl.DHL_KEY');
        // $this->_mode         = getenv('DHL_MODE') ?: config('app.env');
        // $this->accountNumber = getenv('DHL_ACCOUNT') ?: config('dhl.api.accountNumber');
        $this->username      = config('env.DHL_ID') ?: config('dhl.DHL_ID');
        $this->password      = config('env.DHL_KEY') ?: config('dhl.DHL_KEY');
        $this->_mode         = config('env.DHL_MODE') ?: config('app.env');
        $this->accountNumber = config('env.DHL_ACCOUNT') ?: config('dhl.api.accountNumber');
        $this->type          = "curl";
    }

    /**
     * [setType] curl or soap call
     * @param string $type
     */
    public function setType($type = "curl")
    {
        $this->type = $type;
        return true;
    }

    public function doCurlPost()
    { 
        if ($this->_mode == "production") {
            $ch = curl_init($this->_productionUrl);
        } else {
            $ch = curl_init($this->_stagingUrl);
        }
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); //ssl
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0); //ssl
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: text/xml']);
        //            curl_setopt($ch, CURLOPT_HEADER, FALSE);
        //            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        //            curl_setopt($ch, CURLOPT_USERPWD, $this->username . ':' . $this->password);
        //            curl_setopt($ch, CURLOPT_NOBODY, FALSE);
        //            curl_setopt($ch, CURLOPT_REFERER, $_SERVER['HTTP_HOST']);
        curl_setopt($ch, CURLOPT_PORT, 443);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $this->document());
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);

        curl_close($ch);
        $this->resultsRAW = $result;
        try {
            $result = str_ireplace(['xmlSOAP-ENV','ser-root:','SOAP-ENV:', 'SOAP:','rateresp:','shipresp:','trac:','dhl:','ns:'], '', $result);
            $this->results = simplexml_load_string($result)->children();
        } catch (\Exception $exception) {
            return false;
        }

        return $this->results;
    }

    public function call()
    {
        return $this->doCurlPost();
    }

    public function mode($value = null)
    {
        if (empty($value)) {
            return $this->_mode;
        }

        $this->_mode = $value;

        return $this;
    }

    public function document()
    {
        if (!isset($this->document)) {
            $this->toXML();
        }

        return $this->document;
    }

    public function getResultsRAW()
    {
        if (empty($this->resultsRAW)) {
            $this->doCurlPost();
        }

        return $this->resultsRAW;
    }

    public function getResults()
    {
        if (empty($this->result)) {
            $this->doCurlPost();
        }
        
        return $this->result;
    }

    // if shipper is from dubai then it will domestic shipment

    public function isDomestic()
    {

        if($this->getShipper()['country_code'] == $this->getRecipient()['country_code'] && $this->getShipper()['country_code'] == "AE") {
            return true;
        }
        return false;
    }

}
