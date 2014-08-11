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
        //if enable config slideshow widget
        if(Mage::getStoreConfig('slideshow_option/messages/slide_enable'))
            $this->setTemplate('teamto/featured/widget/slideshow.phtml');
    }

    /**
     * get featured product for widget slideshow
     *
     * @return Mage_Eav_Model_Entity_Collection_Abstract
     */
    public function getLoadedProductCollection()
    {
        $limit = Mage::getStoreConfig('slideshow_option/messages/slide_number');
        $this->_productCollection = Mage::getModel('catalog/product')
                                        ->getCollection()
                                        ->addAttributeToSelect('*')
                                        ->addFieldToFilter('featured','1')
                                        ->joinField('inventory_in_stock',
                                            'cataloginventory_stock_item',
                                            'is_in_stock',
                                            'product_id=entity_id','is_in_stock>=0', 'left'
                                        )
                                        ->addFieldToFilter('inventory_in_stock','1')
                                        ->setOrder('updated_at','desc')
                                        ->setPageSize($limit);
        return $this->_productCollection;
    }
}