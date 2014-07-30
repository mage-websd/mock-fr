<?php
class Teamto_Featured_Helper_Link extends Mage_Core_Helper_Abstract
{
    public function getSkin()
    {
        return Mage::getBaseUrl('skin').'frontend/default/default/';
    }
}