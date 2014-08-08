<?php

/**
 * Class Teamto_Configcustom_Model_Select_Template
 *
 * option select type widget
 */
class Teamto_Configcustom_Model_Select_Template{
    public function toOptionArray()
    {
        $option = array(
            array('value'=>1, 'label'=> 'Slideshow Template'),
        );
        return $option;
    }
}