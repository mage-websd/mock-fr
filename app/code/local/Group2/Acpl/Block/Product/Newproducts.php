<?php

class Group2_Acpl_Block_Product_Newproducts extends Mage_Catalog_Block_Product_New {

    /**
     * Prepare collection with new products and applied page limits.
     *
     * return Mage_Catalog_Block_Product_New
     */
   
    protected function _beforeToHtml() {
        $todayStartOfDayDate = Mage::app()->getLocale()->date()
                ->setTime('00:00:00')
                ->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);

        $todayEndOfDayDate = Mage::app()->getLocale()->date()
                ->setTime('23:59:59')
                ->toString(Varien_Date::DATETIME_INTERNAL_FORMAT);

        $collection = Mage::getResourceModel('catalog/product_collection');
        $collection->setVisibility(Mage::getSingleton('catalog/product_visibility')->getVisibleInCatalogIds());


        $collection = $this->_addProductAttributesAndPrices($collection)
                ->addStoreFilter()
                ->addAttributeToFilter('news_from_date', array('or' => array(
                        0 => array('date' => true, 'to' => $todayEndOfDayDate),
                        1 => array('is' => new Zend_Db_Expr('null')))
                        ), 'left')
                ->addAttributeToFilter('news_to_date', array('or' => array(
                        0 => array('date' => true, 'from' => $todayStartOfDayDate),
                        1 => array('is' => new Zend_Db_Expr('null')))
                        ), 'left')
                ->addAttributeToFilter(
                        array(
                            array('attribute' => 'news_from_date', 'is' => new Zend_Db_Expr('not null')),
                            array('attribute' => 'news_to_date', 'is' => new Zend_Db_Expr('not null'))
                        )
                )
                //->addAttributeToSort('news_from_date', 'desc')
                ->setPageSize($this->getProductsCount())
                ->setCurPage(1);

        if ($categoryId = $this->getData('category_id')) {
            $category = Mage::getModel('catalog/category')->load($categoryId);
            $collection->addCategoryFilter($category)->addAttributeToSort('position', 'asc');
        }
        $collection->addAttributeToSort('news_from_date', 'desc');
        $this->setProductCollection($collection);

        return parent::_beforeToHtml();
    }

}
