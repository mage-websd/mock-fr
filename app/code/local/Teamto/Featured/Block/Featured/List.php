<?php

class Teamto_Featured_Block_Featured_List extends Mage_Adminhtml_Block_Widget_Container {

    protected function _prepareLayout()
    {
        $this->_addButton('save', array(
            'label'   => Mage::helper('catalog')->__('Save Featured Products'),
            'onclick' => "FeaturedProduct.save('{$this->getUrl('*/*/save')}')",
            'class'   => 'save',
//            'type' => 'submit'
        ));

        $this->setChild('grid', $this->getLayout()->createBlock('teamto_featured/featured_grid', 'admin.teamto.grid"'));
        return parent::_prepareLayout();
    }

    /**
     * Set template
     */
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('teamto/list.phtml');
    }

    /**
     * Deprecated since 1.3.2
     *
     * @return string
     */
    public function getAddNewButtonHtml()
    {
        return $this->getChildHtml('add_new_button');
    }

    /**
     * Render grid
     *
     * @return string
     */
    public function getGridHtml()
    {
        return $this->getChildHtml('grid');
    }

    /**
     * Check whether it is single store mode
     *
     * @return bool
     */
    public function isSingleStoreMode()
    {
        if (!Mage::app()->isSingleStoreMode()) {
            return false;
        }
        return true;
    }

}