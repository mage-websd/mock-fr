<?php

class Teamto_Mirrored_Model_Observer_Category
{
    public function category_before_save($observer)
    {
        //get cate before save
        $cate = $observer->getEvent();
        $cate = $cate['category'];

        //onle mirrored category exists
        if (!$cate->getData('entity_id')) { //if add new category, do not nothing
            $cate->setData('mirrored_to','');

        }
        else{//eles edit category

            $this->_copySetting($cate);//copy setting $cate to mirrored child

            $mirrored_to_id = $cate->getData('mirrored_to'); //mirrored id parent submit

            if($mirrored_to_id == $cate->getData('entity_id')) {//if mirrored to self, exit

            }
            else{
                $cate_old = $this->_getSingletonCategory()->load($cate->getData('entity_id')); // category old before save
                $mirrored_to_old_id = $cate_old->getData('mirrored_to'); //mirrored old before save
                if ($mirrored_to_old_id != $mirrored_to_id) { //if mirrored change
                    if (!$mirrored_to_old_id) { //if mirrored old is null
                        if ($mirrored_to_id) { //if mirrored submit not null
                            $this->_addCateForPro($mirrored_to_id, $cate->getData('entity_id'));
                            $this->_copyCateSub($this->_getModelCategory()->load($mirrored_to_id), $cate);
                            $this->_mirroredSetting($mirrored_to_id,$cate->getData('entity_id'));
                        }
                    }
                    else { //mirrored old is not null
                        $this->_deleteCateSub($cate->getData('entity_id'));
                        $this->_deleteSelfProduct($cate->getData('entity_id'));

                        if ($mirrored_to_id) { //if mirrored submit not null
                            $this->_addCateForPro($mirrored_to_id, $cate->getData('entity_id'));
                            $this->_copyCateSub($this->_getModelCategory()->load($mirrored_to_id), $cate);
                            $this->_mirroredSetting($mirrored_to_id,$cate->getData('entity_id'));
                        }
                    }
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
        $this->_copyCateSub($cate_parent, $cate_child);

        //$this->_mirroredSetting($cate_parent, $cate_child);
    }

    /**
     * copy setting of parent on child when parent change
     *
     * @param $categoryParent
     */
    private function _copySetting($categoryParent)
    {
        $array_not_copy_mirrored_sub = array(
            'entity_id',
            'parent_id',
            'created_at',
            'updated_at',
            'level',
            'path',
            'url_path',
            'mirrored_to',
            'mirrored_copy_from_cate',
        );

        $array_not_copy_mirrored_parent = array(
            'entity_id',
            'parent_id',
            'created_at',
            'updated_at',
            'name',
            'level',
            'path',
            'url_key',
            'url_path',
            'mirrored_to',
            'mirrored_copy_from_cate'
        );

        //category  mirrored change
        $categories = $this->_getSingletonCategory()->getCollection()
            ->addAttributeToFilter('mirrored_to',array('eq'=> $categoryParent->getData('entity_id')));
        if($categories){
            foreach( $categories as $category ) {
                foreach($categoryParent->getData() as $key => $value) {
                    if (in_array($key, $array_not_copy_mirrored_parent))
                        continue;
                    $category->setData($key, $value);
                }
                $category->save();
            }
        }

        //category sub mirrored change
        $categories = $this->_getSingletonCategory()->getCollection()
            ->addAttributeToFilter('mirrored_copy_from_cate',array('eq'=> $categoryParent->getData('entity_id')));
        if($categories){
            foreach( $categories as $category ) {
                foreach($categoryParent->getData() as $key => $value) {
                    if (in_array($key, $array_not_copy_mirrored_sub))
                        continue;
                    $category->setData($key, $value);
                }
                $category->save();
            }
        }
    }

    /**
     * copy setting mirrored parent to mirrored child
     *
     * @param $id_mirrored_parent: id category mirrored parent
     * @param $id_mirrored_child: id category mirrored child
     */
    private function _mirroredSetting($id_mirrored_parent,$id_mirrored_child)
    {
        $array_not_copy_mirrored_sub = array(
            'entity_id',
            'parent_id',
            'created_at',
            'updated_at',
            'level',
            'path',
            'url_path',
            'mirrored_to',
            'mirrored_copy_from_cate',
            'name',
        );

        $categoryParent = $this->_getSingletonCategory()->load($id_mirrored_parent);
        $categoryChild = $this->_getSingletonCategory()->load($id_mirrored_child);
        foreach($categoryParent->getData() as $key => $value) {
            if (in_array($key, $array_not_copy_mirrored_sub))
                continue;
            $categoryChild->setData($key, $value);
        }
        $categoryChild->save();
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
            'path',
            'url_path',
            'mirrored_to',
            'mirrored_copy_from_cate',
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
    private function _addNewAllCateSub($id_mirrored_parent, $id_mirrored_child,
                                       $level_mirrored_child, $array_not_copy = array())
    {
        $collection = Mage::getResourceModel('catalog/category_collection')
            ->addAttributeToFilter('is_active', array('in' => array(0,1)))
            ->addAttributeToFilter('parent_id', $id_mirrored_parent)
            ->getColumnValues('entity_id');
        if($collection){
            define('pathToMirroredChild', $this->_getSingletonCategory()->load($id_mirrored_child)->getPath());
            foreach ($collection as $id_sub) {
                $cateSub = $this->_getSingletonCategory()->load($id_sub);
                $cateNew = $this->_getModelCategory();
                foreach ($cateSub->getData() as $key => $value) {
                    if (in_array($key, $array_not_copy)) //not copy data
                        continue;
                    $cateNew->setData($key, $value);
                }
                $cateNew->setData('level', ($level_mirrored_child + 1));

                $cateNew->setData('mirrored_copy_from_cate', $id_sub);

                $cateNew->setPath(pathToMirroredChild);
                $cateNew->save();
                //add category for product of sub category mirrored parent
                $this->_addCateForPro($id_sub, $cateNew->getData('entity_id'));

                $this->_addNewAllCateSub(
                    $id_sub,
                    $cateNew->getData('entity_id'),
                    ($level_mirrored_child + 1),
                    $array_not_copy
                ); //call recursive add new all category
            }
        }
    }

    /**
     * Add category for product
     *
     * @param $id_cate_parent: id category parent
     * @param $id_cate_child: id category child
     */
    private function _addCateForPro($id_cate_parent, $id_cate_child)
    {
        //get all product of category $cate_parent
        $collection = $this->_getModelProduct()
            ->getCollection()
            ->joinField('category_id', 'catalog/category_product', 'category_id',
                'product_id = entity_id')
            ->addAttributeToFilter('category_id', array('eq' => $id_cate_parent));

        if($collection)
            foreach ($collection as $product) {
                //$product = $this->_getModelProduct()->load($p->getEntityId());

                $arrayCategory = $product->getCategoryIds();

                $arrayCategory[] = $id_cate_child; //add category for product

                $product->setCategoryIds($arrayCategory);

                $product->save();
            }

    }

    private function _deleteCateSub($id_cate_mirrored_child)
    {
        //find all id sub category
        $categoryResource = Mage::getResourceModel('catalog/category_collection')
            ->addAttributeToFilter('is_active', array('in' => array(0,1)))
            ->addAttributeToFilter('parent_id', $id_cate_mirrored_child)
            ->getColumnValues('entity_id');
        if($categoryResource)
            //delete category => sub and product auto delete
            foreach($categoryResource as $idSub) {
                $this->_getModelCategory()->load($idSub)->delete();
            }
    }

    private function _deleteSelfProduct($id_cate_mirrored_child)
    {
        $collection = $this->_getModelProduct()
            ->getCollection()
            ->joinField('category_id', 'catalog/category_product', 'category_id',
                'product_id = entity_id')
            ->addAttributeToFilter('category_id',
                array('eq' => $id_cate_mirrored_child)
            );
        if($collection){
            foreach ($collection as $product) {
                $arrayCategory = $product->getCategoryIds();

                $key = array_search($id_cate_mirrored_child,$arrayCategory);
                unset($arrayCategory[$key]);
                $product->setCategoryIds($arrayCategory);
                $product->save();
            }
        }
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
    private function _getSingletonCategory()
    {
        return Mage::getSingleton('catalog/category');
    }
    private function _getSingletonProduct()
    {
        return Mage::getSingleton('catalog/product');
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
