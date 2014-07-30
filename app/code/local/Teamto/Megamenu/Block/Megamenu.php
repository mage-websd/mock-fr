<?php

/**
 * Class Teamto_Megamenu_Block_Megamenu
 *
 * override class Block: Mage_Page_Block_Html_Topmenu
 */
class Teamto_Megamenu_Block_Megamenu extends Mage_Page_Block_Html_Topmenu
{
    /**
     * override _getHtml() of class Mage_Page_Block_Html_Topmenu
     *
     * @param Varien_Data_Tree_Node $menuTree
     * @param string $childrenWrapClass
     * @return string
     */
    protected function _getHtml(Varien_Data_Tree_Node $menuTree, $childrenWrapClass)
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
            $option_tagli = ' class="mega-level'.($level-2).' no-'.$no.'"'; // add class for li

            $html .= '<li' . $option_tagli . '>
                <a href="' . $this->getUrl($cate->getData('url_path')). '">'
                . $cate->getData('name')
                . '</a>';
            if($level==3) {
                $html .= '<span class="mega-menu-status hot">Hot</span>';
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
