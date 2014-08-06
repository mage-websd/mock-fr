<?php
class Teamto_Customhome_Block_Blocknews extends Mage_Core_Block_Template
{
    // Get top 5 news newest
    public function getListNews()
    {
        $collection = Mage::getModel('cms/page')->getCollection()->addStoreFilter(Mage::app()->getStore()->getId());
        $collection->getSelect()
                   ->where('is_active = 1')
                   ->where('is_news IS NOT NULL')
                   ->order('update_time', 'DESC')
                   ->limit(5);
        return $collection;
    }
}
