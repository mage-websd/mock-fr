<?php

/**
 * Class Teamto_Megamenu_Block_Megamenu
 *
 */
class Teamto_Megamenu_Block_Megamenu extends Mage_Core_Block_Template
{
    private $_cate_current_id; //category current id
    public function __construct()
    {
        $this->_cate_current_id = Mage::registry('current_category')->getData('entity_id');
        parent::__construct();
    }

    public function getHtml()
    {
        $singleton = Mage::getSingleton('catalog/category');
        $rootcatID = Mage::app()->getStore()->getRootCategoryId();
        return $this->_getSubCategory($singleton, $rootcatID,0);
    }
    /**
     * get category and all sub category
     *
     * @param $singleton: singletion catalog/category
     * @param $id_category: id category
     * @param $no: #no level
     * @return string
     */
    protected function _getSubCategory($singleton, $id_category, $no)
    {
        $html = '';
        $cate = $singleton->load($id_category);
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
                $status = Mage::getSingleton('catalog/category')->load($id_category)->getData('status');
                if(strtolower($status) != 'normal'){
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
                $html .= $this->_getSubCategory($singleton, $idChild,$no_sub);
                $no_sub++;
            }

            $html .= '</ul>';

        }
        if ($level > 1) {
            $html .= '</li>';
        }
        return $html;
    }

}
