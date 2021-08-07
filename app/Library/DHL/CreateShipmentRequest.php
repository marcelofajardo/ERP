<?php

namespace App\Library\DHL;

use App\Library\DHL\Response\CreateShipmentResponse;

/**
 * Get Rate request for DHL
 *
 *
 */
class CreateShipmentRequest extends APIAbstract
{

    private $reference;

    private $fromCountryCode;
    private $fromPostalCode;
    private $fromCity;

    private $timeZone;
    private $dimensionUnit;
    private $weightUnit;

    private $products                  = [];
    private $dropOffType               = "REGULAR_PICKUP";
    private $shipper                   = [];
    private $recipient                 = [];
    private $packages                  = [];
    private $shippingTime;
    private $unitOfMeasurement  = "SI";
    private $content            = "DOCUMENTS";
    private $paymentInfo        = "DAP";
    private $serviceType        = "P";
    private $currency           = "USD";
    private $invoiceNumber      = "";
    private $shipmentIdentificationNumber = true;
    private $declaredValue;
    private $declaredValueCurrecyCode;
    private $sendPackage = true;
    private $mobile;
    private $paperLess;
    private $items;
    private $description = "Fashion Products";

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

    public function getDropoffType()
    {
        return $this->dropOffType;
    }

    public function setDropoffType($value)
    {
        $this->dropOffType = $value;
        return $this->dropOffType;
    }

    public function getServiceType()
    {
        return $this->serviceType;
    }

    public function setServiceType($serviceType)
    {
        $this->serviceType = $serviceType; 
        return $this->serviceType;
    }
        
    public function getCurrency()
    {
        return $this->currency;
    }

    public function setCurrency($currency)
    {
        $this->currency = $currency; 
        return $this->currency;
    }

    public function getUnitOfMeasurement()
    {
        return $this->unitOfMeasurement;
    }

    public function setUnitOfMeasurement($unitOfMeasurement)
    {
        $this->unitOfMeasurement = $unitOfMeasurement; 
        return $this->unitOfMeasurement;
    }

    public function getShipmentIdentificationNumber()
    {
        return $this->shipmentIdentificationNumber;
    }

    public function setShipmentIdentificationNumber($shipmentIdentificationNumber)
    {
        $this->shipmentIdentificationNumber = $shipmentIdentificationNumber; 
        return $this->shipmentIdentificationNumber;
    }

    public function getSendPackage()
    {
        return $this->sendPackage;
    }

    public function setSendPackage($sendPackage)
    {
        $this->sendPackage = $sendPackage; 
        return $this->sendPackage;
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

    public function getMobile()
    {
        return $this->mobile;
    }

    public function setMobile($mobile)
    {
        $this->mobile = $mobile;
        return $this->mobile;
    }

    public function getPaperLess()
    {
        return $this->paperLess;
    }

    public function setPaperLess($paperLess)
    {
        $this->paperLess = $paperLess;
        return $this->paperLess;
    }

    public function getInvoiceNumber()
    {
        return $this->invoiceNumber;
    }

    public function setInvoiceNumber($invoiceNumber)
    {
        $this->invoiceNumber = $invoiceNumber;
        return $this->invoiceNumber;
    }

    public function getItems()
    {
        return $this->items;
    }

    public function setItems($items)
    {
        $this->items = $items;
        return $this->items;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
        return $this->description;
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
            $xml->writeAttribute('xmlns:ship', "http://scxgxtt.phx-dc.dhl.com/euExpressRateBook/ShipmentMsgRequest");
            $xml->startElement('soapenv:Header');
                $xml->startElement('wsse:UsernameToken');
                    $xml->writeAttribute('wsu:Id', "Request");
                    $xml->writeElement('wsse:Username', $this->username);
                    $xml->startElement('wsse:Password');
                        $xml->writeAttribute('type', "http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-username-token-profile-1.0#PasswordText");
                        $xml->text($this->password);
                    $xml->endElement();
                    $xml->startElement('wsse:Nonce');
                        $xml->writeAttribute('encodingtype', "http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-soap-message-security-1.0#Base64Binary");
                        $xml->text(md5(time()));
                    $xml->endElement();
                    $xml->writeElement('wsu:Created', date("Y-m-d"));
                $xml->endElement();
            $xml->endElement();
            $xml->startElement('soapenv:Body');
                $xml->startElement('ship:ShipmentRequest');
                    $xml->startElement('RequestedShipment');
                        $xml->startElement('ShipmentInfo');
                            $xml->writeElement('DropOffType', $this->dropOffType);
                            if(!$isDomestic) {
                                $xml->writeElement('ServiceType',$this->serviceType);
                            }else{
                                $xml->writeElement('ServiceType',"N");
                            }
                            $xml->writeElement('Account',$this->accountNumber);
                            $xml->writeElement('Currency',$this->currency);
                            $xml->writeElement('UnitOfMeasurement',$this->unitOfMeasurement);
                            $xml->writeElement('PackagesCount',count($this->packages));
                            $xml->writeElement('SendPackage',$this->sendPackage);
                            if(!$isDomestic) {
                                $xml->writeElement('PaperlessTradeEnabled', $this->paperLess);
                                if($this->paperLess == true) {
                                    $xml->startElement('SpecialServices');
                                        if($this->paymentInfo == "DDP") {
                                            $xml->startElement('Service');
                                                $xml->writeElement('ServiceType', "DD");
                                            $xml->endElement();
                                        }
                                        $xml->startElement('Service');
                                            $xml->writeElement('ServiceType', "WY");
                                        $xml->endElement();
                                    $xml->endElement();
                                }else{
                                    if($this->paymentInfo == "DDP") {
                                        $xml->startElement('SpecialServices');
                                            $xml->startElement('Service');
                                                $xml->writeElement('ServiceType', "DD");
                                            $xml->endElement();
                                        $xml->endElement();
                                    }
                                }
                                $xml->startElement('LabelOptions');
                                    $xml->writeElement('RequestDHLCustomsInvoice', "Y");
                                $xml->endElement();
                            }
                        $xml->endElement();
                        $xml->writeElement('ShipTimestamp', $this->shippingTime);
                        $xml->writeElement('PaymentInfo', $this->paymentInfo);
                        
                        $xml->startElement('InternationalDetail');
                            $xml->startElement('Commodities');
                                $xml->writeElement('NumberOfPieces',count($this->items));
                                $xml->writeElement('Description',$this->description);
                                $xml->writeElement('CustomsValue',$this->declaredValue);
                            $xml->endElement();
                            if(!$isDomestic) {
                                $xml->writeElement('Content',"NON_DOCUMENTS");
                            }else{
                                $xml->writeElement('Content',"DOCUMENTS");
                            }
                            $xml->startElement('ExportDeclaration');
                                $xml->writeElement('InvoiceDate',date("Y-m-d"));
                                $xml->writeElement('InvoiceNumber',$this->invoiceNumber);
                                if(!empty($this->items)) {
                                    $xml->startElement('ExportLineItems');
                                        foreach($this->items as $i => $item) {
                                            $xml->startElement('ExportLineItem');
                                                $xml->writeElement('ItemNumber',$i+1);
                                                $xml->writeElement('Quantity',$item['qty']);
                                                $xml->writeElement('QuantityUnitOfMeasurement','PCS');
                                                $xml->writeElement('ItemDescription',$item['name']);
                                                $xml->writeElement('UnitPrice',$item['unit_price']);
                                                $xml->writeElement('NetWeight',$item['net_weight']);
                                                $xml->writeElement('GrossWeight',$item['gross_weight']);
                                                $xml->writeElement('CommodityCode',$item['hs_code']);
                                                $xml->writeElement('ManufacturingCountryCode',$item['manufacturing_country_code']);
                                            $xml->endElement();
                                        }
                                    $xml->endElement();
                                }
                            $xml->endElement();
                        $xml->endElement();
                        // section for the  shiping and recipient
                        $xml->startElement('Ship');
                            // shipper section started 
                            if(!empty($this->shipper)) {
                                $shipper = $this->shipper;
                                $xml->startElement('Shipper');
                                    $xml->startElement('Contact');
                                        $xml->writeElement('PersonName',!empty($shipper["person_name"]) ? $shipper["person_name"] : '');
                                        $xml->writeElement('CompanyName',!empty($shipper["company_name"]) ? $shipper["company_name"] : '');
                                        $xml->writeElement('PhoneNumber',!empty($shipper["phone"]) ? $shipper["phone"] : '');
                                    $xml->endElement();
                                    $xml->startElement('Address');
                                        $xml->writeElement('StreetLines',!empty($shipper["street"]) ? $shipper["street"] : '');
                                        $xml->writeElement('City',!empty($shipper["city"]) ? $shipper["city"] : '');
                                        $xml->writeElement('PostalCode',!empty($shipper["postal_code"]) ? $shipper["postal_code"] : '');
                                        $xml->writeElement('CountryCode',!empty($shipper["country_code"]) ? $shipper["country_code"] : '');
                                    $xml->endElement();
                                $xml->endElement();
                            }
                            // shipper section ended
                            //  recipient section srarted
                            $email = "";
                            if(!empty($this->recipient)) {
                                $recipient = $this->recipient;
                                $email = !empty($recipient["email"]) ? $recipient["email"] : "";
                                $xml->startElement('Recipient');
                                    $xml->startElement('Contact');
                                        $xml->writeElement('PersonName',!empty($recipient["person_name"]) ? $recipient["person_name"] : '');
                                        $xml->writeElement('CompanyName',!empty($recipient["company_name"]) ? $recipient["company_name"] : '');
                                        $xml->writeElement('PhoneNumber',!empty($recipient["phone"]) ? $recipient["phone"] : '');
                                    $xml->endElement();
                                    $xml->startElement('Address');
                                        $xml->writeElement('StreetLines',!empty($recipient["street"]) ? $recipient["street"] : '');
                                        $xml->writeElement('City',!empty($recipient["city"]) ? $recipient["city"] : '');
                                        $xml->writeElement('PostalCode',!empty($recipient["postal_code"]) ? $recipient["postal_code"] : '');
                                        $xml->writeElement('CountryCode',!empty($recipient["country_code"]) ? $recipient["country_code"] : '');
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
                                    $xml->writeElement('Weight',(string)$package["weight"]);
                                    $xml->startElement('Dimensions');
                                        $xml->writeElement('Length',$package["length"]);
                                        $xml->writeElement('Width',$package["width"]);
                                        $xml->writeElement('Height',$package["height"]);
                                    $xml->endElement();
                                    $xml->writeElement('CustomerReferences',(string)$package["note"]);
                                $xml->endElement();
                            }
                            $xml->endElement();
                        }
                        $xml->startElement('ShipmentNotifications');
                            $xml->startElement('ShipmentNotification');
                                $xml->writeElement('NotificationMethod', 'EMAIL');
                                if(!empty($email)) {
                                    $xml->writeElement('EmailAddress', $email);
                                }else{
                                    $xml->writeElement('EmailAddress', 'info@theluxuryunlimited.com');
                                }
                                $xml->writeElement('MobilePhoneNumber', $this->mobile);
                            $xml->endElement();
                        $xml->endElement();
                    $xml->endElement();
                $xml->endElement();
            $xml->endElement();
        $xml->endElement();
        //$xml->endDocument();
        return $this->document = $xml->outputMemory();
    }

    public function call($reset = false)
    {
        if($reset) {
            $this->document = $this->toXML();
        }

        $result = $this->doCurlPost();

        return new CreateShipmentResponse($result);
    }

}
