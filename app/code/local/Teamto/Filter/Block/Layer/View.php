<?php

class Teamto_Filter_Block_Layer_View extends Mage_Catalog_Block_Layer_View {

    /*
     *  get price range
     */
    public function getPriceRange() {
        $currentCategory = Mage::getModel('catalog/layer')->getCurrentCategory()->getData('url_path');
        $maxPrice = Mage::getModel('catalog/layer_filter_price')->getMaxPriceInt();

        $aryPriceData = array(
            'max_price' => $maxPrice,
            'current_category' => $currentCategory
        );

        $arySesPriceData = Mage::getSingleton('core/session')->getAryPriceData();

        if(is_null($arySesPriceData) || $currentCategory != $arySesPriceData['current_category']){
            Mage::getSingleton('core/session')->setAryPriceData($aryPriceData);
            $arySesPriceData = Mage::getSingleton('core/session')->getAryPriceData();
        }
        return $arySesPriceData['max_price'];
    }

    /*
     *  get attributes to filter
     */
    public function getFilters(){
        $filters = array();
        if ($categoryFilter = $this->_getCategoryFilter()) {
            $filters[] = $categoryFilter;
        }

        $filterableAttributes = $this->_getFilterableAttributes();
        foreach ($filterableAttributes as $attribute) {
            $filterableCategoryName = $this->getChild($attribute->getAttributeCode() . '_filter')->getName();
            if($filterableCategoryName == 'Price'){
                $filters[1] = $this->getChild($attribute->getAttributeCode() . '_filter');
            } else if($filterableCategoryName == 'Manufacturer'){
                $filters[2] = $this->getChild($attribute->getAttributeCode() . '_filter');
            } else if($filterableCategoryName == 'Color'){
                $filters[3] = $this->getChild($attribute->getAttributeCode() . '_filter');
            } else if($filterableCategoryName == 'Size'){
                $filters[4] = $this->getChild($attribute->getAttributeCode() . '_filter');
            }
        }

        return $filters;
    }
}