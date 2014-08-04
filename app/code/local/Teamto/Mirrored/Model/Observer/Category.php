<?php
class Teamto_Mirrored_Model_Observer_Category
{
    public function category_before_save($observer)
    {
        $singleton = Mage::getSingleton('catalog/category');
        //get cate before save
        $cate = $observer->getEvent();
        $cate = $cate['category'];

        $mirrored_to_id = $cate->getData('mirrored_to'); //mirrored id parent submit

        /*echo '<script>console.log("'.$cate->getData('level').
            '");</script>';
        exit;*/

        // if category add new
        if(!$cate->getData('entity_id')) {
            /*if($mirrored_to_id) { //if have mirrored
                $this->_copySetting($singleton->load($mirrored_to_id), $cate);

                $this->_copyCateSub($singleton->load($mirrored_to_id), $cate);

                $this->_copyAllProduct($singleton->load($mirrored_to_id), $cate);

                $this->_setMirroredParent($cate);
            }*/

            $this->_copyData($mirrored_to_id,$singleton->load($mirrored_to_id),$cate);
        }

        //else category edit
        else {
            $cate_old = $singleton->load($cate->getData('entity_id'));  // category old before save
            $mirrored_to_old_id = $cate_old->getData('mirrored_to'); //mirrored old before save

            if(!$mirrored_to_old_id) { //if mirrored old is null
                /*if($mirrored_to_id) { //if mirrored submit not null
                    $this->_copySetting($singleton->load($mirrored_to_id), $cate);

                    $this->_copyCateSub($singleton->load($mirrored_to_id), $cate);

                    $this->_copyAllProduct($singleton->load($mirrored_to_id), $cate);

                    $this->_setMirroredParent($cate);
                }*/
                $this->_copyData($mirrored_to_id,$singleton->load($mirrored_to_id),$cate);
            }

            else { //mirrored old is not null
                if($mirrored_to_id != $mirrored_to_old_id) { //if mirrored submit != mirrored old
                    $this->_deleteAllProduct($cate);
                    $this->_deleteCateSub($cate);

                    /*if($mirrored_to_id) { //if $mirrored submit is not null
                        $this->_copySetting($singleton->load($mirrored_to_id), $cate);

                        $this->_copyCateSub($singleton->load($mirrored_to_id), $cate);

                        $this->_copyAllProduct($singleton->load($mirrored_to_id), $cate);

                        $this->_setMirroredParent($cate);
                    }*/
                    $this->_copyData($mirrored_to_id,$singleton->load($mirrored_to_id),$cate);
                }
            }
        }

        //var_dump($cate);
        //echo "<script>alert(".$cate->getData().");</script>";
        echo '<script>console.log("'.$cate->getData('entity_id')
            . ' - ' . $cate->getData('mirrored_to') . ' - ' . $cate->getData('status') .
            '");</script>';
    }

    private function _copyData($mirrored_to_id, $cate_parent, &$cate_child)
    {
        if($mirrored_to_id) { //if $mirrored submit is not null
            $this->_copySetting($cate_parent, $cate_child);

            $this->_copyCateSub($cate_parent, $cate_child);

            $this->_copyAllProduct($cate_parent, $cate_child);

            $this->_setMirroredParent($cate_child);
        }
    }

    private function _copySetting($copy,&$paste)
    {
        //setting of category parent not copy
        $array_attr_not = array(
            'entity_id',
            'parent_id',
            'created_at',
            'updated_at',
            'path',
            'level',
            'children_count',
            'name',
            'url_key',
            'url_path',
            'mirrored_to',
            'mirrored_child',
        );
        foreach($copy->getData() as $key => $value) {
            if(in_array($key,$array_attr_not))
                continue;
            $paste->setData($key,$value);
        }
    }

    private function _copyCateSub($copy,&$paste)
    {
        $singleton = Mage::getSingleton('catalog/category');
        $subCateStr = $copy->getChildren();
        foreach(explode(',',$subCateStr) as $value) {
            $cateSub = $singleton->load($value);
            $cateNew = $singleton;
            foreach($cateSub as $key => $value) {
                $cateNew->setData($key,$value);
            }
            $cateNew->save();

        }
    }

    private function _addNewAllCateSub($id_copy,$array_attr_not = array()) {
        $singleton = Mage::getSingleton('catalog/category');
        $category = $singleton->load($id_copy);
        $str_cateSub = $category->getChildren();

        foreach(explode(',',$str_cateSub) as $value) {
            $cateSub = Mage::getSingleton('catalog/category')->load($value);
            $id_cateSub = $cateSub->getData('entity_id');
            $cateNew = Mage::getSingleton('catalog/category');
            foreach($cateSub->getData() as $key => $value) {
                $cateNew->setData($key,$value);
            }
            $cateNew->save();

        }
    }

    private function _copyAllProduct($copy,&$paste)
    {

    }

    private function _deleteCateSub(&$cate)
    {

    }

    private function _deleteAllProduct(&$cate)
    {

    }

    private function _setMirroredParent($cate_child)
    {

    }
}