<?php

/**
 * Class Teamto_Link_Helper_Data
 *
 * heler link
 */
class Teamto_Link_Helper_Data extends Mage_Core_Helper_Abstract {
    public function getSkinFrontend(){
        return Mage::getBaseUrl('skin').'frontend/default/default/';
    }

    public function getSkinAdmin(){
        return Mage::getBaseUrl('skin').'adminhtml/default/default/';
    }
}