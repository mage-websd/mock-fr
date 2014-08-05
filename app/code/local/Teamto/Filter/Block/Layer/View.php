<?php
//Mage_Catalog_Block_Layer_Filter_Price
class Teamto_Filter_Block_Layer_View extends Mage_Catalog_Block_Layer_View {

    public function getPriceRange() {
        return Mage::getModel('catalog/layer_filter_price')->getMaxPriceInt();

//        $currentCategory = Mage::getModel('catalog/layer')->getCurrentCategory()->getData('url_path');
//        $maxPrice = Mage::getModel('catalog/layer_filter_price')->getMaxPriceInt();
//
//        $aryPriceData = array(
//            'max_price' => $maxPrice,
//            'current_category' => $currentCategory
//        );
//
//        $arySesPriceData = Mage::getSingleton('core/session')->getAryPriceData();
//        $arySession = Mage::getSingleton('core/session')->getData();
//
//        if(!in_array($currentCategory, $arySesPriceData)){
//            return 'dat';
//            Mage::getSingleton('core/session')->setAryPriceData($aryPriceData);
//        }
//
//        return Mage::getSingleton('core/session')->getData('max_price');
    }
}