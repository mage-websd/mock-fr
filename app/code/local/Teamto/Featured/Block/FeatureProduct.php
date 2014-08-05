<?php

class Teamto_Featured_Block_FeatureProduct extends Mage_Catalog_Block_Product_List
{


    public function getLoadedProductCollection()
    {

        $dir    =   $_GET['dir'];
        $order  =   $_GET['order'];

        $this->_productCollection = Mage::getModel('catalog/product')
                                            ->getCollection()
                                            ->addAttributeToSelect('*');

            if($dir && $order){
                $this->_productCollection ->addAttributeToSort($order,$dir);
            }

         $this->_productCollection->addFieldToFilter('featured','1');

        return $this->_productCollection;

    }

}