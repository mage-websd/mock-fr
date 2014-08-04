<?php

class Teamto_Mirrored_Model_Observer_Category
{
    public function category_before_save($observer)
    {
        //get cate before save
        $cate = $observer->getEvent();
        $cate = $cate['category'];

        if (!$cate->getData('entity_id')) { //if add new category, do not nothing
            $cate->setData('mirrored_to','');
            return;
        }

        $mirrored_to_id = $cate->getData('mirrored_to'); //mirrored id parent submit
        //if mirrored to self, exit
        if($mirrored_to_id == $cate->getData('entity_id')) {
            return;
        }

        $cate_old = $this->_getModelCategory()->load($cate->getData('entity_id')); // category old before save
        $mirrored_to_old_id = $cate_old->getData('mirrored_to'); //mirrored old before save

        if ($mirrored_to_old_id != $mirrored_to_id) { //if mirrored change
            if (!$mirrored_to_old_id) { //if mirrored old is null
                if ($mirrored_to_id) { //if mirrored submit not null
                    $this->_copyData($this->_getModelCategory()->load($mirrored_to_id), $cate);
                }

            } else { //mirrored old is not null
                //$this->_deleteAllProduct($cate);
                //$this->_deleteCateSub($cate);

                if ($mirrored_to_id) { //if mirrored submit not null
                    $this->_copyData(
                        $this->_getModelCategory()->load($mirrored_to_id),
                        $cate
                    );
                }
            }
        }
    }

    /**
     * copy all data from mirrored parent to mirrored child
     *      include: setting, sub category and product
     *
     * @param $cate_parent
     * @param $cate_child
     */
    private function _copyData($cate_parent, &$cate_child)
    {
        $this->_copySetting($cate_parent, $cate_child);

        //add category for product of category mirrored parent
        $this->_addCateForPro($cate_parent->getData('entity_id'), $cate_child);

        //$this->_copyCateSub($cate_parent, $cate_child);
    }

    /**
     * copy setting mirrored parent to mirrored child
     *
     * @param $copy
     * @param $paste
     */
    private function _copySetting($copy, &$paste)
    {
        //setting of category parent not copy
        $array_attr_not = array(
            'entity_id',
            'parent_id',
            'created_at',
            'updated_at',
            'name',
            //'children_count',
            'path',
            'url_key',
            'url_path',
            'level',
            'mirrored_to',
            'mirrored_copy_from_cate'
        );
        foreach ($copy->getData() as $key => $value) {
            if (in_array($key, $array_attr_not))
                continue;
            $paste->setData($key, $value);
        }
    }

    /**
     * copy Subcategory mirrored parent to mirrored child
     *
     * @param $mirrored_parent
     * @param $mirrored_child
     */
    private function _copyCateSub($mirrored_parent, $mirrored_child)
    {
        $array_not_copy = array(
            'entity_id',
            'parent_id',
            'created_at',
            'updated_at',
            'level',
            'mirrored_to',
            'mirrored_copy_from_cate',
            'path',
            //'url_key',
            'url_path',
            //'children_count',
        );

        $this->_addNewAllCateSub(
            $mirrored_parent->getData('entity_id'),
            $mirrored_child->getData('entity_id'),
            $mirrored_child->getData('level'),
            $array_not_copy
        ); //call function add new all category

    }

    /**
     * add new all category mirrored
     *      function recursive
     * @param $id_mirrored_parent : id of category copy
     * @param $id_mirrored_child : id of mirrored child new category
     * @param $level_mirrored_child : level of mirrored child
     * @param array $array_not_copy - fix
     */
    private function _addNewAllCateSub($id_mirrored_parent, $id_mirrored_child, $level_mirrored_child, $array_not_copy = array())
    {
        $category = $this->_getModelCategory()->load($id_mirrored_parent);

        $str_cateSub = $category->getChildren();

        if ($str_cateSub) {
            foreach (explode(',', $str_cateSub) as $id_sub) {
                $cateSub = $this->_getModelCategory()->load($id_sub);
                $cateNew = $this->_getModelCategory();
                foreach ($cateSub->getData() as $key => $value) {
                    if (in_array($key, $array_not_copy)) //not copy data
                        continue;
                    $cateNew->setData($key, $value);
                }
                $cateNew->setData('level', ($level_mirrored_child + 1));

                $cateNew->setData('mirrored_copy_from_cate', $id_sub);

                $parent = $this->_getModelCategory()->load($id_mirrored_child); //parent of new
                $cateNew->setPath($parent->getData('path'));
                $cateNew->save();

                //add category for product of sub category mirrored parent
                $this->_addCateForPro($id_sub, $cateNew);


                //$this->console($this->toString($cateNew->getData()));

                $this->_addNewAllCateSub(
                    $id_sub,
                    $cateNew->getData('entity_id'),
                    ($level_mirrored_child + 1),
                    $array_not_copy
                ); //call recursive add new all category
            }
        }
    }

    private function _addCateForPro($id_cate_parent, $cate_child)
    {
        $this->console('sub: '.$id_cate_parent.',, '.$cate_child->getData('entity_id'));
        //get all product of category $cate_parent
        $collection = $this->_getModelProduct()
            ->getCollection()
            ->joinField('category_id', 'catalog/category_product', 'category_id',
                'product_id = entity_id')
            ->addAttributeToSelect('*')
            ->addAttributeToFilter('category_id', array('eq' => $id_cate_parent));

        if($collection)
            foreach ($collection as $p) {
                $idProduct = $p->getData('entity_id');
                $this->console('idfor: '.$idProduct);
                $product = $this->_getModelProduct()->load($idProduct);

                $arrayCategory = $product->getCategoryIds();
                $arrayCategory[] = $cate_child->getData('entity_id'); //add category for product
                $this->console('array cate: '.$this->toString($arrayCategory));
                $product->setCategoryIds($arrayCategory);
                $product->save();
                $this->console('after save: '.$this->toString($product->getCategoryIds()));
            }

    }


    private function _deleteCateSub(&$cate_mirrored_child)
    {

    }

    private function _deleteAllProduct($id_cate_mirrored_child)
    {
        $collection = $this->_getModelProduct()
            ->getCollection()
            ->joinField('category_id', 'catalog/category_product', 'category_id',
                'product_id = entity_id')
            ->addAttributeToSelect('*')
            ->addAttributeToFilter('category_id', 
                array('eq' => $id_cate_mirrored_child)
            );

        if($collection){
            foreach ($collection as $p) {
                $idProduct = $p->getData('entity_id');
                $product = $this->_getModelProduct()->load($idProduct);

                $arrayCategory = $product->getCategoryIds();

                $key = array_search($id_cate_mirrored_child,$arrayCategory);
                unset($arrayCategory[$key]);

                $product->setCategoryIds($arrayCategory);
                $product->save();
            }
        }

        $categoryResource = Mage::getResourceModel('catalog/category_collection')
            ->addAttributeToSelect('*')
            ->addAttributeToFilter('is_active', array('in' => array(0,1)))
            ->addAttributeToFilter('parent_id', $id_cate_mirrored_child);
        if($categoryResource)
            foreach($categoryResource as $_cate) {
                $this->_deleteAllProduct(
                    $_cate->getData('entity_id')
                );
            }
    }

    /**
     * get all product of category
     *
     * @param $idCategory
     * @return array
     */
    private function _getAllProduct($idCategory)
    {
        $arrayProduct = array(); //array data product

        $productCollection = $this->_getModelProduct()->getCollection();

        foreach ($productCollection as $pro) {
            $idProduct = $pro->getData('entity_id');
            $product = Mage::getModel('catalog/product')->load($idProduct);
            $arrayCategory = $product->getCategoryIds();
            if (in_array($idCategory, $arrayCategory)) {
                $arrayProduct[] = $product->getData();
            }
        }

        return $arrayProduct;
    }

    /**
     * return Model 'catalog/category'
     *
     * @return false|Mage_Core_Model_Abstract
     */
    private function _getModelCategory()
    {
        return Mage::getModel('catalog/category');
    }

    private function _getModelProduct()
    {
        return Mage::getModel('catalog/product');
    }


    private function toString($array)
    {
        $str = '';
        foreach ($array as $key => $value) {
            $str .= "{$key}:{$value} , ";
        }
        $str .= ' -- \n --';
        return $str;
    }

    private function console($string)
    {
        echo '<script>console.log("' . $string . '");</script>';
    }
}
