<?php
class Teamto_Mirrored_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        $single = Mage::getSingleton('catalog/category');
        $cate = $single->load(4);
        var_dump($cate->getData());
    }
}