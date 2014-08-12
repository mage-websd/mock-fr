<?php

class Teamto_Mirrored_Model_Resource_Category_Mirrored extends
    Mage_Eav_Model_Entity_Attribute_Source_Abstract
{
    private $_arrayOption = array(); //array all category

    /**
     * option select input
     *
     * @return array
     */
    public function getAllOptions()
    {
        if(!$this->_options) {
            $this->_arrayOption[] = array('value'=>'','label'=>'--Choose category--');
            $this->_treeCateFormat(1);
            $this->_options = $this->_arrayOption;
        }
        return $this->_options;
    }

    /**
     * get all category with format tree
     *
     * @param $idRoot: id category root
     */
    private function _treeCateFormat($idRoot) {
        $cate = Mage::getModel('catalog/category')->load($idRoot);
        if($cate->getData('level') != 0) { //dont show catgory 'Root category'
            $label = '';
            $numberSymbol = (int)$cate->getLevel() -1 ; // 1 grade level add symbol '__|'
            for($i = 0 ; $i < $numberSymbol ; $i++) {
                $label .= '__|'; //symbol is space
            }
            $label .= " {$cate->getName()} - id:{$idRoot}";
            $this->_arrayOption[] = array(
                'value' => $idRoot,
                'label' => $label,
            );
        }

        //find all sub category
        $collection = Mage::getResourceModel('catalog/category_collection')
            ->addAttributeToFilter('is_active', array('in' => array(0,1)))
            ->addAttributeToFilter('parent_id', $idRoot)
            ->getColumnValues('entity_id');
        if($collection)
            foreach($collection as $idSub) {
                $this->_treeCateFormat($idSub);
            }
    }
}