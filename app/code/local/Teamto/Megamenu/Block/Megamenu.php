<?php

/**
 * Class Teamto_Megamenu_Block_Megamenu
 *
 */
class Teamto_Megamenu_Block_Megamenu extends Mage_Core_Block_Template
{
    private $_cate_current_id = 0; //category current id
    private $_singleton; //singleton catalog_category
    /**
     * get current category id
     */
    public function __construct()
    {
        $this->_singleton = Mage::getSingleton('catalog/category');
        $cate_current = Mage::registry('current_category');
        if($cate_current){
            $this->_cate_current_id = $this->_getIdCurrentCateLevel2($cate_current);//$cate_current->getData('entity_id');
        }
        parent::__construct();
    }

    public function getHtml()
    {
        $rootcatID = Mage::app()->getStore()->getRootCategoryId();
        return $this->_getSubCategory($rootcatID,0);
    }
    /**
     * get category and all sub category
     *
     * 
     * @param $id_category: id category
     * @param $no: #no level
     * @return string
     */
    protected function _getSubCategory($id_category, $no)
    {
        $html = '';
        $cate = $this->_singleton->load($id_category);
        $level = $cate->getData('level');
        //not display category have level > 5
        if($level>5)
            return '';
        if ($level > 1) {
            $option_tagli = ' class="mega-level'.($level-2).' no-'.$no; // add class for li
            if($id_category == $this->_cate_current_id)
                $option_tagli .= ' active';

            $option_tagli .= '"';
            $html .= '<li' . $option_tagli . '>
                <a href="' . $this->getUrl($cate->getData('url_path')). '">'
                . $cate->getData('name')
                . '</a>';
            //check status category
            if($level==3) {
                $status = $cate->getData('status');
                if(strtolower($status) != 'normal' && $status != ''){
                    $option_status = strtolower($status);
                    $html .= "<span class='mega-menu-status {$option_status}'>{$status}</span>";
                }
            }
        }
        if ($cate->getData('children_count') > 0) {
            if($level > 1) {
                $option_tagul = ' class="mega-level'.($level-2).' no-'.$no.'"'; //add class for ul
                if($level== 2) {
                    $count_child = $cate->getData('children_count');
                    if($count_child>4)
                        $count_child = 4;
                    $option_tagul .= ' style="width: '.($count_child*200).'px"';
                }
                $html .= "<ul {$option_tagul}>";
            }
            $no_sub = 0;
            foreach (explode(',', $cate->getChildren()) as $idChild) {
                $html .= $this->_getSubCategory($idChild,$no_sub);
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
     * get id catagory current level 2
     *
     * @param $cate_current
     * @return id category current level 2
     */
    private function _getIdCurrentCateLevel2($cate_current)
    {
        if($cate_current->getData('level') == 2) {
            return $cate_current->getData('entity_id');
        }
        $cate_parrent = $this->_singleton->load($cate_current->getData('parent_id'));
        while($cate_parrent->getData('level') > 2) {
            $cate_parrent = $this->_singleton->load($cate_parrent->getData('parent_id'));
        }
        return $cate_parrent->getData('entity_id');
    }

}
