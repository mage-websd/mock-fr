<?php
class Teamto_Mirrored_IndexController extends Mage_Core_Controller_Front_Action
{
    public function indexAction()
    {
        /*$collection = Mage::getModel('catalog/product')
            ->getCollection()
            ->joinField('category_id', 'catalog/category_product', 'category_id',
                'product_id = entity_id')
            ->addAttributeToSelect('*')
            ->addAttributeToFilter('category_id', array('eq' => 26));
        foreach ($collection as $p) {
            $idProduct = $p->getData('entity_id');
            var_dump($idProduct);
        }
        exit;*/
        /*$p = Mage::getModel('catalog/product')->load(237);
        $array = $p->getCategoryIds();
        var_dump($array);*/
        $ca = Mage::getModel('catalog/product')->load(237);
        var_dump($ca->getCategoryIds());
        exit;
        //$collection = $category->getProductPosition();//->addAttributeToSort('position');
        //Mage::getModel('catalog/layer')->prepareProductCollection($collection);
        //var_dump($collection);

        $cat_id = 2;

        //$children = Mage::getModel('catalog/category')->getCollection()->getCategories($cat_id);
        //$children = Mage::getModel('catalog/category')->load($cat_id)->getChildrenCategories();

        $id=10;
        $collection = Mage::getResourceModel('catalog/category_collection')
            ->addAttributeToSelect('*')
            ->addAttributeToFilter('is_active', array('in' => array(0,1)))
            ->addAttributeToFilter('parent_id', $cat_id);
        foreach($collection as $qaz) {
            var_dump($qaz->getData('entity_id'));
        }

        exit;
        /*$category = Mage::getModel('catalog/category')->load($cat_id);
        $collection = $category->getProductCollection()->addAttributeToSort('position');
        Mage::getModel('catalog/layer')->prepareProductCollection($collection);

        foreach ($collection as $product) {
            var_dump( $product->getEntityId() );
        }exit;*/

        $collection = Mage::getModel('catalog/product')
            ->getCollection()
            ->joinField('category_id', 'catalog/category_product', 'category_id',
                'product_id = entity_id')
            ->addAttributeToSelect('*')
            ->addAttributeToFilter('category_id', array('eq' => $cat_id));

        //var_dump($collection->getData());exit;
        foreach( $collection as $ttt) {
            $arrayCategory = $ttt->getCategoryIds();
            $arrayCategory[] = '100';
            var_dump($arrayCategory);
        }
        exit;
        //var_dump($category->getData());
        //var_dump(Mage::getResourceModel('catalog/category_tree')->loadNode(2)->getChildren());


        $model = Mage::getModel('catalog/product');
        $pro = $model->load(284);
        var_dump($pro->getCategoryIds(),
            $pro->getWebsiteIds(),
            $pro->getStoreIds(),
            //$pro->getStockData(),
            $pro->getMediaGallery()
            //$pro->getStockData()
        );
        $stock = Mage::getModel('cataloginventory/stock_item')->loadByProduct($pro);
        var_dump($stock->getData());


        var_dump(Mage::getModel('catalog/product')->getCollection()->getLastItem()->getData('entity_id'));
        //var_dump($pro->getData());

        $pN = Mage::getModel('catalog/product')->load(901);
        $stockNew = Mage::getModel('cataloginventory/stock_item')->loadByProduct($pN);
        $stockNew = $stock;
        $stockNew->setProductId(901);
        $stockNew->save();
        var_dump($stockNew->getData());
        /*foreach($pro->getData() as $key => $value) {
            if($key=='entity_id')
                continue;
            $pN->setData($key,$value);
        }
        $pN->setWebsiteIds($pro->getWebsiteIds()var_dump(Mage::getModel('catalog/product')->getCollection()->getLastItem()->getData('entity_id'));)
            ->setStoreIds($pro->getStoreIds())
            ->setCategoryIds($pro->getCategoryIds())
            ->setSku(time())
            ->setName('g test 1');*/

            //->setManufacturer(28); //manufacturer id
        //var_dump($pN->getData());
        $pN->setMediaGallery($pro->getMediaGallery());
        //$pN->save();
        var_dump($pN->getData());
        var_dump(Mage::getModel('catalog/product')->getCollection()->getLastItem()->getData('entity_id'));exit;
         // error kho, image


        //$product = Mage::getModel('catalog/product');
        /*$product
            ->setWebsiteIds($pro->getWebsiteIds()) //website ID the product is assigned to, as an array
            ->setStoreIds($pro->getStoreIds())
            ->setCategoryIds($pro->getCategoryIds()) //assign product to categories
            ->setAttributeSetId($pro->getAttributeSetId()) //ID of a attribute set named 'default'
            ->setTypeId('simple') //product type
            ->setCreatedAt(strtotime('now')) //product creation time
//    ->setUpdatedAt(strtotime('now')) //product update time
            ->setSku(time()) //SKU
            ->setName('product add manual 1') //product name
            ->setWeight(4.0000)
            ->setStatus(1) //product status (1 - enabled, 2 - disabled)
            ->setTaxClassId(0) //tax class (0 - none, 1 - default, 2 - taxable, 4 - shipping)
            ->setVisibility(Mage_Catalog_Model_Product_Visibility::VISIBILITY_BOTH) //catalog and search visibility
            ->setManufacturer(28) //manufacturer id
            //->setColor(24)
            ->setNewsFromDate('06/26/2014') //product set as new from
            ->setNewsToDate('06/30/2014') //product set as new to
            ->setCountryOfManufacture('AF') //country of manufacture (2-letter country code)
            ->setPrice(11.22) //price in form 11.22
            ->setCost(22.33) //price in form 11.22
            ->setSpecialPrice(00.44) //special price in form 11.22
            //->setSpecialFromDate('06/1/2014') //special price from (MM-DD-YYYY)
            //->setSpecialToDate('06/30/2014') //special price to (MM-DD-YYYY)
            ->setMsrpEnabled(2) //enable MAP
            ->setMsrpDisplayActualPriceType(1) //display actual price (1 - on gesture, 2 - in cart, 3 - before order confirmation, 4 - use config)
            ->setMsrp(99.99) //Manufacturer's Suggested Retail Price
            ->setMetaTitle('test meta title 2')
            ->setMetaKeyword('test meta keyword 2')
            ->setMetaDescription('test meta description 2')
            ->setDescription('This is a long description')
            ->setShortDescription('This is a short description')
            ->setMediaGallery (array('images'=>array (), 'values'=>array ())) //media gallery initialization
            //->addImageToMediaGallery('media/catalog/product/1/0/10243-1.png', array('image','thumbnail','small_image'), false, false) //assigning image, thumb and small image to media gallery
            ->setStockData(array(
                    'use_config_manage_stock' => 0, //'Use config settings' checkbox
                    'manage_stock'=>1, //manage stock
                    'min_sale_qty'=>1, //Minimum Qty Allowed in Shopping Cart
                    'max_sale_qty'=>2, //Maximum Qty Allowed in Shopping Cart
                    'is_in_stock' => 1, //Stock Availability
                    'qty' => 999 //qty
                )
            );*/
        //$product->save();
        $idT = Mage::getModel('catalog/product')->getCollection()->getLastItem()->getData('entity_id');
        var_dump($idT);
        var_dump(Mage::getModel('catalog/product')->load($idT)->getData());
        //$idCategory = 4;
        /*$product = Mage::getModel('catalog/product')->getCollection();

        $arrayProduct = array();
        foreach($product as $value) {
            $idProduct = $value->getData('entity_id');
            $productOne = Mage::getModel('catalog/product')->load($idProduct);
            $arrayCategory = $productOne->getCategoryIds();
            if(in_array($idCategory,$arrayCategory)) {
                $arrayProduct[] = $productOne->getData();
            }
        }*/


        /*try{
            $category = Mage::getModel('catalog/category');
            $category->setName('check');
            $category->setUrlKey('new-category');
            $category->setIsActive(1);
            $category->setDisplayMode('PRODUCTS');
            $category->setIsAnchor(1); //for active achor
            $category->setStoreId(Mage::app()->getStore()->getId());
            //$parentCategory = Mage::getModel('catalog/category')->load($parentId);
            //$category->setPath($parentCategory->getPath());
            $category->save();
        } catch(Exception $e) {
            var_dump($e);
        }*/


        /*$cate = Mage::getModel('catalog/category')->getCollection()->getLastItem();
        $id = $cate->getData('entity_id');
        $category = Mage::getModel('catalog/category')->load($id);*/
        //$category->setPath('1/2/69/86');
        //$category->setData('level',3);
        //$category->save();
        //var_dump($category->getData());
    }
}
//Mage::app()->setCurrentStore(Mage_Core_Model_App::ADMIN_STORE_ID);
//->setStoreId(Mage_Core_Model_App::ADMIN_STORE_ID)