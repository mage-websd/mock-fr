<?php

class Teamto_Featured_Block_FeatureProduct extends Mage_Catalog_Block_Product_List
{


    public function getLoadedProductCollection()
    {

        $dir    =   $_GET['dir'];
        $order  =   $_GET['order'];

        $this->_productCollection = Mage::getModel('catalog/product')
                                            ->getCollection()
                                            ->addAttributeToSelect('*')
                                            ->addFieldToFilter('featured','1')
                                            ->joinField('inventory_in_stock',
                                                        'cataloginventory_stock_item',
                                                        'is_in_stock',
                                                        'product_id=entity_id','is_in_stock>=0', 'left'
                                                        )
                                            ->addFieldToFilter('inventory_in_stock','1');


            if($dir && $order){
                $this->_productCollection ->addAttributeToSort($order,$dir);
            }

            /*echo '<pre>';
            print_r($this->_productCollection->getData());
            die;*/

         return $this->_productCollection;


    }

}