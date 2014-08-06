<?php

/**
 * Class Teamto_Featured_Block_Featured_Widget_Slideshow
 *
 * block widget show slideshow featured product
 */
class Teamto_Featured_Block_Featured_Widget_Slideshow
    extends Mage_Catalog_Block_Product_List
    implements Mage_Widget_Block_Interface
{
    /**
     * __construct: set template for widget
     */
    public function __construct()
    {
        $this->setTemplate('teamto/featured/widget/slideshow.phtml');
    }


    public function getLoadedProductCollection()
    {
        $this->_productCollection = Mage::getModel('catalog/product')
                                        ->getCollection()
                                        ->addAttributeToSelect('*')
                                        ->addFieldToFilter('featured','1');

        return $this->_productCollection;

    }

}