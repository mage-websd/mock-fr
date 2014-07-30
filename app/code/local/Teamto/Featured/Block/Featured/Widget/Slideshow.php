<?php

/**
 * Class Teamto_Featured_Block_Featured_Widget_Slideshow
 *
 * block widget show slideshow featured product
 */
class Teamto_Featured_Block_Featured_Widget_Slideshow
    extends Mage_Core_Block_Template
    implements Mage_Widget_Block_Interface
{
    /**
     * __construct: set template for widget
     */
    public function __construct()
    {
        $this->setTemplate('teamto/featured/widget/slideshow.phtml');
    }
}