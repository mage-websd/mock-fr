<?php
/**
 * Created by PhpStorm.
 * User: kevin
 * Date: 8/1/14
 * Time: 10:37 AM
 */
class Teamto_Featured_IndexController extends Mage_Core_Controller_Front_Action {

    public function indexAction(){
        $this->loadLayout();
        $this->renderLayout();

    }
}