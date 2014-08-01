<?php

class Teamto_Mirrored_Model_Resource_Category_Mirrored extends
    Mage_Eav_Model_Entity_Attribute_Source_Abstract
{
    public function getAllOptions()
    {
        if (!$this->_options) {
            $singleton = Mage::getSingleton('catalog/category');
            $categories = $singleton->getCollection();
            $array_option = array();
            $array_option[] = array('value'=>'','label'=>'--Choose category parrent--');
            foreach($categories as $cate) {
                if($cate->getData('level') == 0) //not mirrored root category
                    continue;
                $id_cate = $cate->getData('entity_id');
                $cate_one = $singleton->load($id_cate);


                $array_option[] = array(
                    'value'=> $id_cate,
                    'label'=> $cate_one->getData('name').' - '.$id_cate,
                );
            }
            $this->_options = $array_option;
        }
        return $this->_options;
    }
}