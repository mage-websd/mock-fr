<?php

class Teamto_Geoip_Model_Observer {

    public function geoip_store() {
        $storeCode = "";
        $geoip = Mage::getSingleton('geoip/country');
        $countryCode = $geoip->getCountry();
        var_dump($countryCode);
        switch ($countryCode)
        {
            case 'US':
                $storeCode ='default';
                break;
            case 'AU':
                $storeCode ='french';
                break;
            default :
                $storeCode ='default';
        }
        $storeCodeCurrent = Mage::app()->getStore()->getCode();
        if($storeCodeCurrent != $storeCode)
        {
            $response = Mage::app()->getResponse();
            $response->setRedirect(Mage::getBaseUrl()."?___store=".$storeCode."&___from_store=default");
            Mage::app()->getResponse()->sendResponse();
            exit;
        }
    }

}
