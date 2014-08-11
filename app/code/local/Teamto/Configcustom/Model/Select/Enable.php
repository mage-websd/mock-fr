<?php

/**
 * Class Teamto_Configcustom_Model_Select_Enable
 *
 * option select enable or disable slideshow widget
 */
class Teamto_Configcustom_Model_Select_Enable
{
    public function toOptionArray()
    {
        $option = array(
            array('value'=>'0','label'=>'No'),
            array('value'=>'1','label'=>'Yes'),
        );
        return $option;
    }
}