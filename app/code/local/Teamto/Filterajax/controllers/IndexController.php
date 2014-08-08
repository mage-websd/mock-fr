<?php
class Teamto_Filterajax_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        if($this->getRequest()->getParam('isajax')) {
            $params = $this->getRequest()->getParams();
            /*foreach($params as $name => $value) {
                echo "$name: $value<br/>";
            }exit;*/
            $prices = $params['price'];
            if($prices) {
                $pos = strripos($prices,'-'); //postion int of suybol - in price
                $priceFrom = substr($prices,0,$pos);
                $priceTo = substr($prices,$pos+1);
            }

            /*$products = Mage::getModel('catalog/product')
                ->getCollection()
                ->addAttributeToSelect('*')
                ->joinField('category_id', 'catalog/category_product', 'category_id',
                    'product_id = entity_id')
                ->joinField('manages_stock','cataloginventory/stock_item','use_config_manage_stock',
                    'product_id=entity_id','{{table}}.use_config_manage_stock=1 ') //or {{table}}.manage_stock=1
                ->joinField('stock_status','cataloginventory/stock_status','stock_status',
                    'product_id=entity_id', array(
                        'stock_status' => Mage_CatalogInventory_Model_Stock_Status::STATUS_IN_STOCK,
                        'website_id' => Mage::app()->getWebsite()->getWebsiteId(),
                ))*/
            $products = Mage::getModel('catalog/category')->load($params['category'])
                ->getProductCollection()
                ->joinField('stock_status','cataloginventory/stock_status','stock_status',
                    'product_id=entity_id', array(
                        'stock_status' => Mage_CatalogInventory_Model_Stock_Status::STATUS_IN_STOCK
                    ))
                ->addAttributeToSelect('*')
                ->addAttributeToFilter('status','1')
                ->addAttributeToFilter('visibility',array('in'=>array(2,4)));//array('eq'=>2),array('eq'=>4)

            //them loc, stock, so luong
            if($params['color']) {
                $products = $products->addAttributeToFilter('color',$params['color']);
            }
            if($params['size']) {
                $products = $products->addAttributeToFilter('size',$params['size']);
            }
            $products->setOrder('price', 'DESC');

            if(!count($products)) {
                echo '<p class="note-msg">'.$this->__('There are no products matching the selection.').'</p>';
            }
            else {
                Mage::register('products',$products);
                if($prices){
                    Mage::register('filter_price',array('from'=>$priceFrom,'to'=>$priceTo));
                }
                $this->loadLayout();
                $this->renderLayout();
            }
        }
    }
    public function testAction()
    {




        $productsMax = Mage::getModel('catalog/category')->load(8)
            ->getProductCollection()
            ->addAttributeToFilter('status', 1) // enabled
            ->addAttributeToFilter('visibility', 4)
            ->addAttributeToFilter('color', array('in' => array('27')))
            ->setOrder('price', 'DESC');
            //
            ; //visibility in catalog,search
            //$productsMax->;
            //$productsMax->addAttributeToFilter('final_price',array('gt',300));
        foreach($productsMax as $p) {
            $maxPrice = $p['entity_id']; //final_price
            var_dump($p->getData('final_price'));
        }


    }
}