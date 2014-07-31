<?php
class Teamto_Megamenu_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        $catalog = Mage::getModel('catalog/category')->load(10);
            //->getCollection()->getLastItem(); id 44
        var_dump($catalog->getData());
    }
}