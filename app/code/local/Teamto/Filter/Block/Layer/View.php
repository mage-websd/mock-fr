<?php
//Mage_Catalog_Block_Layer_Filter_Price
class Teamto_Filter_Block_Layer_View extends Mage_Catalog_Block_Layer_View {

    public function getPriceRange() {
        return Mage::getModel('catalog/layer_filter_price')->getMaxPriceInt();

//        $maxPrice = Mage::getModel('catalog/layer_filter_price')->getMaxPriceInt();
//
//        var_dump(Mage::getSingleton('core/session'));die;
//
//        if(!iss){
//            Mage::getSingleton('core/session')->setMaxPrice($maxPrice);
//        }
//        return ;
    }
}