<?php
/**
 * Created by PhpStorm.
 * User: Tuan-Kakhasi
 * Date: 8/4/14
 * Time: 3:42 PM
 */
class Teamto_Featured_Block_Featured_Product extends Mage_Catalog_Block_Product_List
{


    public function getLoadedProductCollection()
    {
        $this->_productCollection = Mage::getModel('catalog/product')->getCollection()
                                            ->addAttributeToSelect('*')
                                            ->addFieldToFilter('featured', array('eq' => '1'));

        return $this->_productCollection;

    }

}