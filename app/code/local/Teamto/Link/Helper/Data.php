<?php

/**
 * Class Teamto_Link_Helper_Data
 *
 * helper link
 */
class Teamto_Link_Helper_Data extends Mage_Core_Helper_Abstract {

    /**
     * return link skin pagckage teamto, theme default
     *
     * @return string
     */
    public function getSkinFrontend(){
        return Mage::getBaseUrl('skin').'frontend/teamto/default/';
    }

    /**
     * return link skin admin
     *
     * @return string
     */
    public function getSkinAdmin(){
        return Mage::getBaseUrl('skin').'adminhtml/default/default/';
    }
}