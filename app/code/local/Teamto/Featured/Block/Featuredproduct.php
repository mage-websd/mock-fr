<?php

class Teamto_Featured_Block_Featuredproduct extends Mage_Catalog_Block_Product_List
{


    public function getLoadedProductCollection()
    {

        $dir    =   $_GET['dir'];
        $order  =   $_GET['order'];
        $limit  =   $_GET['limit'];

        if($dir =='' && $order == '' && $limit == ''){
            $dir        = 'desc';
            $order      =   'price';
            $limit      =   12;
        }
        //echo $dir,$order,$limit; die;
        $store_id = Mage::app()->getStore()->getId();
        $this->_productCollection = Mage::getModel('catalog/product')->getCollection()
                                            ->addAttributeToSelect('*')
                                            ->addStoreFilter($store_id)
                                            ->addAttributeToFilter('status', 1) // enabled
                                            ->addAttributeToFilter('visibility', 4) //visibility in catalog,search
                                            ->addAttributeToFilter('featured', '1')
                                            ->setPageSize($limit)
                                            ->addAttributeToSort($order,$dir);


         return $this->_productCollection;


    }

}