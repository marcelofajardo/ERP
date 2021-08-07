<?php

namespace App\Library\DHL;
use App\Library\DHL\Response\GetRateResponse;

/**
 * Get Rate request for DHL
 *
 *
 */
class GetRateRequest extends APIAbstract
{

    private $reference;

    private $fromCountryCode;
    private $fromPostalCode;
    private $fromCity;

    private $timeZone;
    private $dimensionUnit;
    private $weightUnit;


    private $products = [];
    private $rateEstimates = "N";
    private $detailedBreakDown = "N";
    private $includeAdditionalCurrency = "N";
    private $dropOffType = "REGULAR_PICKUP";
    private $shipper = [];
    private $recipient = [];
    private $packages = [];
    private $shippingTime;
    private $unitOfMeasurement = "SI";
    private $content = "NON_DOCUMENTS";
    private $declaredValue;
    private $declaredValueCurrecyCode;
    private $paymentInfo = "DAP";


    public function __construct($requestType = "soap")
    {
        parent::__construct();
        // $this->fromCountryCode = getenv('DHL_COUNTRYCODE') ?: config('dhl.tas.DHL_COUNTRYCODE');
        // $this->fromPostalCode  = getenv('DHL_POSTALCODE') ?: config('dhl.tas.DHL_POSTALCODE');
        // $this->fromCity        = getenv('DHL_CITY') ?: config('dhl.tas.DHL_CITY');
        $this->fromCountryCode = config('env.DHL_COUNTRYCODE') ?: config('dhl.tas.DHL_COUNTRYCODE');
        $this->fromPostalCode  = config('env.DHL_POSTALCODE') ?: config('dhl.tas.DHL_POSTALCODE');
        $this->fromCity        = config('env.DHL_CITY') ?: config('dhl.tas.DHL_CITY');
        $this->timeZone        = "+02:00";
        $this->dimensionUnit   = 'CM';
        $this->weightUnit      = 'KG';
        $this->setType($requestType);
    }

    public function getRateEstimates()
    {
        return $this->rateEstimates;
    }

    public function setRateEstimates($value)
    {
        $this->rateEstimates = $value;
        return $this->rateEstimates;
    }

    public function getDetailedBreakDown()
    {
        return $this->detailedBreakDown;
    }

    public function setDetailedBreakDown($value)
    {
        $this->detailedBreakDown = $value;
        return $this->detailedBreakDown;
    }

    public function getIncludeAdditionalCurrency()
    {
        return $this->includeAdditionalCurrency;
    }

    public function setIncludeAdditionalCurrency($value)
    {
        $this->includeAdditionalCurrency = $value;
        return $this->includeAdditionalCurrency;
    }

    public function getDropoffType()
    {
        return $this->dropOffType;
    }

    public function setDropoffType($value)
    {
        $this->dropOffType = $value;
        return $this->dropOffType;
    }

    public function getShipper()
    {
        return $this->shipper;
    }

    public function setShipper($value = [])
    {
        $this->shipper = $value;
        return $this->shipper;
    }

    public function getRecipient()
    {
        return $this->recipient;
    }

    public function setRecipient($value = [])
    {
        $this->recipient = $value;
        return $this->recipient;
    }

    public function getPackages()
    {
        return $this->packages;
    }

    public function setPackages($value = [])
    {
        $this->packages = $value;
        return $this->packages;
    }

    public function getShippingTime()
    {
        return $this->shippingTime;
    }

    public function setShippingTime($value)
    {
        $this->shippingTime = $value;
        return $this->shippingTime;
    }

    public function getUnitOfMeasurement()
    {
        return $this->unitOfMeasurement;
    }

    public function setUnitOfMeasurement($value)
    {
        $this->unitOfMeasurement = $value;
        return $this->unitOfMeasurement;
    }

    public function getContent($value)
    {
        return $this->content;
    }

    public function setContent($value)
    {
        $this->content = $value;
        return $this->content;
    }

    public function getDeclaredValue()
    {
        return $this->declaredValue;
    }

    public function setDeclaredValue($value)
    {
        $this->declaredValue = $value;
        return $this->declaredValue;
    }

    public function getDeclaredValueCurrencyCode()
    {
        return $this->declaredValueCurrecyCode;
    }

    public function setDeclaredValueCurrencyCode($value)
    {
        $this->declaredValueCurrecyCode = $value;
        return $this->declaredValueCurrecyCode;
    }

    public function getPaymentInfo()
    {
        return $this->paymentInfo;
    }

    public function setPaymentInfo($value)
    {
        $this->paymentInfo = $value;
        return $this->paymentInfo;
    }

    public function toXML()
    {
        $isDomestic = $this->isDomestic();

        $xml = new \XmlWriter();
        $xml->openMemory();
        $xml->setIndent(true);
        $xml->setIndentString("  ");
        //$xml->startDocument('1.0', 'UTF-8');

        $xml->startElement('soapenv:Envelope');
            $xml->writeAttribute('xmlns:wsu', "http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-utility-1.0.xsd");
            $xml->writeAttribute('xmlns:wsse', "http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd");
            $xml->writeAttribute('xmlns:soapenv', "http://schemas.xmlsoap.org/soap/envelope/");
            $xml->writeAttribute('xmlns:rat', "http://scxgxtt.phx-dc.dhl.com/euExpressRateBook/RateMsgRequest");
            $xml->startElement('soapenv:Header');
                $xml->startElement('wsse:UsernameToken');
                    $xml->writeAttribute('wsu:Id', "Request");
                    $xml->writeElement('wsse:Username',$this->username);
                    $xml->startElement('wsse:Password');
                        $xml->writeAttribute('Type', "PasswordText");
                        $xml->text($this->password);
                    $xml->endElement();
                    $xml->startElement('wsse:Nonce');
                        $xml->writeAttribute('EncodingType', "UTF-8");
                        $xml->text(md5(time()));
                    $xml->endElement();
                    $xml->writeElement('wsu:Created',date("Y-m-d"));
                $xml->endElement(); 
            $xml->endElement();
            $xml->startElement('soapenv:Body');
                $xml->startElement('rat:RateRequest');
                    $xml->startElement('Request');
                        $xml->startElement('ServiceHeader');
                            $xml->writeElement('MessageTime',gmdate("Y-m-d\TH:i:s-05:00",strtotime("now")));
                            $xml->writeElement('MessageReference',GUID());
                        $xml->endElement();
                    $xml->endElement();
                    $xml->startElement('RequestedShipment');
                        $xml->writeElement('GetRateEstimates',$this->rateEstimates);
                        $xml->writeElement('GetDetailedRateBreakdown',$this->detailedBreakDown);
                        $xml->writeElement('IncludeAdditionalCurrencies',$this->includeAdditionalCurrency);
                        $xml->writeElement('DropOffType',$this->dropOffType);
                        if($isDomestic) {
                           //$xml->writeElement('Content',$this->content); 
                        }
                        // section for the  shiping and recipient
                        $xml->startElement('Ship');
                            // shipper section started 
                            if(!empty($this->shipper)) {
                                $shipper = $this->shipper;
                                $xml->startElement('Shipper');
                                    $xml->writeElement('City',!empty($shipper["city"]) ? $shipper["city"] : '');
                                    $xml->writeElement('PostalCode',!empty($shipper["postal_code"]) ? $shipper["postal_code"] : '');
                                    $xml->writeElement('CountryCode',!empty($shipper["country_code"]) ? $shipper["country_code"] : '');
                                    $xml->startElement('Contact');
                                        $xml->writeElement('PersonName',!empty($shipper["person_name"]) ? $shipper["person_name"] : '');
                                        $xml->writeElement('CompanyName',!empty($shipper["company_name"]) ? $shipper["company_name"] : '');
                                        $xml->writeElement('PhoneNumber',!empty($shipper["phone"]) ? $shipper["phone"] : '');
                                    $xml->endElement();
                                $xml->endElement();
                            }
                            // shipper section ended
                            //  recipient section srarted
                            if(!empty($this->recipient)) {
                                $recipient = $this->recipient;
                                $xml->startElement('Recipient');
                                    $xml->writeElement('City',!empty($recipient["city"]) ? $recipient["city"] : '');
                                    $xml->writeElement('PostalCode',!empty($recipient["postal_code"]) ? $recipient["postal_code"] : '');
                                    $xml->writeElement('CountryCode',!empty($recipient["country_code"]) ? $recipient["country_code"] : '');
                                    $xml->startElement('Contact');
                                        $xml->writeElement('PersonName',!empty($recipient["person_name"]) ? $recipient["person_name"] : '');
                                        $xml->writeElement('CompanyName',!empty($recipient["company_name"]) ? $recipient["company_name"] : '');
                                        $xml->writeElement('PhoneNumber',!empty($recipient["phone"]) ? $recipient["phone"] : '');
                                    $xml->endElement();
                                $xml->endElement();
                            }
                            // recipient section ended
                        $xml->endElement();
                        // section end for shipping and recipient
                        if(!empty($this->packages)) {
                            $xml->startElement('Packages');
                            foreach($this->packages as $k => $package) {
                                $xml->startElement('RequestedPackages');
                                    $xml->writeAttribute('number', ($k + 1));
                                    $xml->startElement('Weight');
                                        $xml->writeElement('Value',$package["weight"]);
                                    $xml->endElement();
                                    $xml->startElement('Dimensions');
                                        $xml->writeElement('Length',$package["length"]);
                                        $xml->writeElement('Width',$package["width"]);
                                        $xml->writeElement('Height',$package["height"]);
                                    $xml->endElement();
                                $xml->endElement();
                            }
                            $xml->endElement();
                        }
                    $xml->writeElement('ShipTimestamp',$this->shippingTime);
                    $xml->writeElement('UnitOfMeasurement',$this->unitOfMeasurement);
                    //$xml->writeElement('PayerCountryCode','IN');
                    if(!$isDomestic) {
                        $xml->writeElement('Content',"NON_DOCUMENTS");
                    }else{
                        $xml->writeElement('Content',"DOCUMENTS");
                    }
                    $xml->writeElement('DeclaredValue',$this->declaredValue);
                    $xml->writeElement('DeclaredValueCurrecyCode',$this->declaredValueCurrecyCode);
                    $xml->writeElement('PaymentInfo',$this->paymentInfo);
                    $xml->writeElement('Account',$this->accountNumber);
                $xml->endElement();
            $xml->endElement();
        $xml->endElement();
        $xml->endElement();
        //$xml->endDocument();
        //echo $xml->outputMemory();die;
        return $this->document = $xml->outputMemory();
    }

    public function call()
    {
        $result = $this->doCurlPost();

        return new GetRateResponse($result); 
    }



}
