<?php

/**
 * Class Teamto_Megamenu_Block_Megamenu
 *
 */
class Teamto_Megamenu_Block_Megamenu extends Mage_Core_Block_Template
{
    private $_cate_current_id = 0; //category current id

    public function __construct()
    {
        $cate_current = Mage::registry('current_category'); //current category
        if($cate_current){
            $this->_cate_current_id = $this->_getIdCurrentCateLevel2($cate_current); //get category level 2
        }
        parent::__construct();
    }

    /**
     * return string menu category format html
     *
     * @return string
     */
    public function getHtml()
    {
        $rootcatID = Mage::app()->getStore()->getRootCategoryId();
        return $this->_getSubCategory($rootcatID,0);
    }
    /**
     * get category and all sub category
     *   format html
     * 
     * @param $id_category: id category
     * @param $no: #no category same level
     * @return string
     */
    protected function _getSubCategory($id_category, $no)
    {
        $html = '';
        $cate = $this->_getModelCategory()->load($id_category);
        if(!$cate->getData('include_in_menu') || !$cate->getData('is_active'))
            return '';
        $level = $cate->getData('level');
        //not display category have level > 4
        if($level>4)
            return '';
        if ($level > 1) {
            $option_tagli = ' class="mega-level'.($level-2).' no-'.$no; // add class for li
            if($id_category == $this->_cate_current_id)
                $option_tagli .= ' active';

            $option_tagli .= '"';
            $html .= '<li' . $option_tagli . '>
                <a href="' . Mage::getBaseUrl().$cate->getData('url_path'). '">'
                . $cate->getData('name')
                . '</a>';
            //check status category, display if level = 3
            if($level==3) {
                $status = $cate->getData('status');
                if(strtolower($status) != 'normal' && $status != ''){
                    $option_status = strtolower($status);
                    $html .= "<span class='mega-menu-status {$option_status}'>{$status}</span>";
                }
            }
        }

        $str_subCategories = $cate->getChildren();
        if ($str_subCategories) {
            if($level > 1) {
                $option_tagul = ' class="mega-level'.($level-2).' no-'.$no.'"'; //add class for ul
                if($level== 2) {
                    $option_tagul .= ' style="width: '.(800).'px"';
                }
                $html .= "<ul {$option_tagul}>";
            }
            $no_sub = 0;
            foreach (explode(',', $str_subCategories) as $idChild) {
                $html .= $this->_getSubCategory($idChild,$no_sub); // call recursive with sub category
                $no_sub++;
            }

            $html .= '</ul>';

        }
        if ($level > 1) {
            $html .= '</li>';
        }
        return $html;
    }

    /**
     * get id catagory current level 2, menu level 1
     *
     * @param $cate_current: category current
     * @return id category current level 2
     */
    private function _getIdCurrentCateLevel2($cate_current)
    {
        if($cate_current->getData('level') == 2) {
            return $cate_current->getData('entity_id');
        }
        $cate_parrent = $this->_getModelCategory()->load($cate_current->getData('parent_id'));
        while($cate_parrent->getData('level') > 2) {
            $cate_parrent = $this->_getModelCategory()->load($cate_parrent->getData('parent_id'));
        }
        return $cate_parrent->getData('entity_id');
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
}
