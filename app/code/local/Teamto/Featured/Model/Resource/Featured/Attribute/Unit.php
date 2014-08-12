<?php

class Teamto_Featured_Model_Resource_Featured_Attribute_Unit extends Mage_Eav_Model_Entity_Attribute_Source_Abstract
{
    public function getAllOptions()
    {
        if (!$this->_options) {
            $this->_options = array(
                array(
                    'value' => '0',
                    'label' => 'no',
                ),
                array(
                    'value' => '1',
                    'label' => 'yes',
                )
            );
        }
        return $this->_options;
    }
}