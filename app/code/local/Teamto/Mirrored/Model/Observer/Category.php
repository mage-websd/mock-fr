<?php

/**
 * Class Teamto_Mirrored_Model_Observer_Category
 */
class Teamto_Mirrored_Model_Observer_Category
{
    /**
     * Function main observer of event catalog_category_prepare_save
     *
     * @param $observer
     */
    public function category_before_save($observer)
    {
        //get cate before save
        $category = $observer->getEvent()['category'];
        $idCategory = $category->getData('entity_id');
        $this->_idCategory = $idCategory;

        if (!$idCategory) { //if add new category, do not nothing
            $category->setData('mirrored_to','');
        }
        //onle mirrored category exists
        else{//eles edit category

            $idMirroredParent = $category->getData('mirrored_to'); //mirrored id parent submit

            if($idMirroredParent == $idCategory) {//if mirrored to self
            }
            else{

                $idMirroredParentOld = $category->getOrigData('mirrored_to'); //mirrored old before submit
                if ($idMirroredParent != $idMirroredParentOld) { //if mirrored change
                    if (!$idMirroredParentOld) { //if mirrored old is null
                        if ($idMirroredParent) { //if mirrored submit not null

                            $this->_mirroredSetting($idMirroredParent, $category);

                            $this->_addPostedProductsMirrored($idMirroredParent,$category);

                            $this->_copyCateSub($idMirroredParent, $category);

                        }
                    }
                    else { //mirrored old is not null
                        $this->_deleteCateSub($idCategory);
                        $this->_deleteProductMirroredChild($idCategory);

                        if ($idMirroredParent) { //if mirrored submit not null
                            $this->_mirroredSetting($idMirroredParent, $category);
                            $this->_addPostedProductsMirrored($idMirroredParent,$category);
                            $this->_copyCateSub($idMirroredParent, $category);
                        }
                    }
                }
            }
            $this->_copySetting($category);//copy setting category $category to mirrored child when $category change
        }
    }

    /**
     * copy data setting of mirrored parent to mirrored child when exec mirrored
     *
     * @param $idMirroredParent
     * @param $mirroredChild
     */
    private function _mirroredSetting($idMirroredParent, &$mirroredChild)
    {
        $categoryParent = $this->_getModelCategory()->load($idMirroredParent);
        foreach ($this->_arrayColumnMirroredParent as $column) {
            $mirroredChild->setData($column,$categoryParent->getData($column));
        }
    }

    private function _addPostedProductsMirrored($idMirroredParent, &$categoryNew)
    {
        $arrayPostedProducts = $this->_getPostProducts($idMirroredParent);
        if($arrayPostedProducts)
            $categoryNew->setData('posted_products',$arrayPostedProducts);
    }

    /**
     * copy all sub category of mirrored parent to mirrored child
     *
     * @param $idMirroredParent
     * @param $mirroredChild
     */
    private function _copyCateSub($idMirroredParent, $mirroredChild)
    {
        //find id catedub of mirrored parent
        $collection = Mage::getModel('catalog/category')->getCollection()
            ->addAttributeToFilter('is_active', array('in' => array(0,1)))
            ->addAttributeToFilter('parent_id', "{$idMirroredParent}")
            ->getColumnValues('entity_id');
        if(count($collection)){
            foreach ($collection as $idSub) {
                //dont copy sub of mirrored child
                if($idSub == $this->_idCategory)
                    continue;

                $cateNew = $this->_getModelCategory();
                $cateSub = $this->_getModelCategory()->load($idSub);
                foreach($this->_arrayColumnMirroredChild as $column) {
                    $cateNew->setData($column,$cateSub->getData($column));
                }
                $cateNew->setData('mirrored_copy_from_cate', $idSub);
                $cateNew->setLevel($mirroredChild->getLevel()+1);
                $cateNew->setPath($mirroredChild->getPath());
                $this->_addPostedProductsMirrored($idSub, $cateNew);
                $cateNew->save();

                $this->_copyCateSub($idSub, $cateNew); //call recursive add new all category
            }
        }
    }

    /**
     * get array id product posted to add category mirrored child and sub
     *
     * @param $idMirroredParent
     * @return array|null
     */
    private function _getPostProducts($idMirroredParent)
    {
        //get all product of category $cate_parent
        $collection = $this->_getModelProduct()
            ->getCollection()
            ->joinField('category_id', 'catalog/category_product', 'category_id',
                'product_id = entity_id')
            ->addAttributeToFilter('category_id', array('eq' => "{$idMirroredParent}"))
            ->getColumnValues('entity_id');

        if(count($collection)) {
            $arrayPostedProducts = array();
            foreach($collection as $idProduct) {
                $arrayPostedProducts["{$idProduct}"] = 1;
            }
            return $arrayPostedProducts;
        }
        return null;
    }

    /**
     * copy setting of parent on child when parent change
     *
     * @param $categoryParent
     */
    private function _copySetting($categoryParent)
    {
        //category  mirrored change
        $categories = $this->_getModelCategory()->getCollection()
            ->addAttributeToFilter('mirrored_to',array('eq'=> $categoryParent->getData('entity_id')));
        if(count($categories)) {
            foreach ($categories as $cate) {
                foreach( $this->_arrayColumnMirroredParent as $column) {
                    $cate->setData($column, $categoryParent->getData($column));
                }
                $cate->save();
            }
        }

        //sub category mirrored change
        $categories = $this->_getModelCategory()->getCollection()
            ->addAttributeToFilter('mirrored_copy_from_cate',array('eq'=> $categoryParent->getData('entity_id')));
        if(count($categories)) {
            foreach ($categories as $cate) {
                foreach( $this->_arrayColumnMirroredChild as $column) {
                    $cate->setData($column, $categoryParent->getData($column));
                }
                $cate->save();
            }
        }
    }

    /**
     * delete sub category of mirrored child
     *
     * @param $idCateSub
     */
    private function _deleteCateSub($idCateSub)
    {
        //find all id sub category
        $categoryResource = Mage::getResourceModel('catalog/category_collection')
            ->addAttributeToFilter('is_active', array('in' => array(0,1)))
            ->addAttributeToFilter('parent_id', $idCateSub)
            ->getColumnValues('entity_id');
        if(count($categoryResource))
            //delete category => sub and product auto delete
            foreach($categoryResource as $idSub) {
                $this->_getModelCategory()->load($idSub)->delete();
            }
    }

    /**
     * delete product of mirrored child
     *
     * @param $idCateChild: id category mirrored child
     */
    private function _deleteProductMirroredChild($idCateChild)
    {
        $category = $this->_getModelCategory()->load($idCateChild);
        $category->setData('posted_products',array());
        $category->save();
    }

    /**
     * get model catalog/category
     *
     * @return false|Mage_Core_Model_Abstract
     */
    private function _getModelCategory()
    {
        return Mage::getModel('catalog/category');
    }

    /**
     * get model catalog product
     *
     * @return false|Mage_Core_Model_Abstract
     */
    private function _getModelProduct()
    {
        return Mage::getModel('catalog/product');
    }

    private $_idCategory = null; //id category submit

    /**
     * @var array: setting columns for copy data from mirrored parent to mirrored child
     */
    private $_arrayColumnMirroredParent = array(
            'position',
            'meta_title',
            'display_mode',
            'custom_design',
            'page_layout',
            'image',
            'is_active',
            'include_in_menu',
            'landing_page',
            'is_anchor',
            'custom_use_parent_settings',
            'custom_apply_to_products',
            'description',
            'meta_keywords',
            'meta_description',
            'custom_layout_update',
            'available_sort_by',
            'custom_design_from',
            'custom_design_to',
            'filter_price_range',
        );

    /**
     * @var array: setting columns for copy data from mirrored parent sub category to mirrored child sub category
     */
    private $_arrayColumnMirroredChild = array(
        'position',
        'meta_title',
        'display_mode',
        'custom_design',
        'page_layout',
        'image',
        'is_active',
        'include_in_menu',
        'landing_page',
        'is_anchor',
        'custom_use_parent_settings',
        'custom_apply_to_products',
        'description',
        'meta_keywords',
        'meta_description',
        'custom_layout_update',
        'available_sort_by',
        'custom_design_from',
        'custom_design_to',
        'filter_price_range',
        'name',
        'url_key',
    );
}
