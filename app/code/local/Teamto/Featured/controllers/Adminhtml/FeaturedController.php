<?php

/*
 *  @author datdb
 */
class Teamto_Featured_Adminhtml_FeaturedController extends Mage_Adminhtml_Controller_Action
{

    /**
     * The greatest value which could be stored in CatalogInventory Qty field
     */
    const MAX_QTY_VALUE = 99999999.9999;

    /**
     * Array of actions which can be processed without secret key validation
     *
     * @var array
     */
    protected function _construct()
    {
        // Define module dependent translate
        //$this->setUsedModuleName('Mage_Catalog');
    }

    /**
     * Product list page
     */
    public function indexAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * Product grid for AJAX request
     */
    public function gridAction()
    {
        $this->loadLayout();
        $this->renderLayout();
    }

    /**
     * @author datdb
    *   prepare data before saving
    */
    public function prepareSave($data, &$aryProductId, &$aryCheckedBoxes, &$aryUncheckedBoxes)
    {
        $aryProductId = explode(',', $data['aryUncheckedBoxes']); // build array of all product id
        $aryCheckedBoxes = explode(',', $data['aryCheckedBoxes']); // build array of all product id which were checked
        $aryUncheckedBoxes = array_diff($aryProductId, $aryCheckedBoxes);   // build array of product id which were uncheced
        $aryInitialCheckedBoxes = explode(',', $data['aryInitialCheckedBoxes']); // build array of initial product id which were checked
        $aryInitialUnCheckedBoxes = explode(',', $data['aryInitialUncheckedBoxes']); // build array of initial product id which were unchecked

        // get product id which need to be updated to featured product
        if (!empty($aryCheckedBoxes)) {
            foreach ($aryCheckedBoxes as $key => $val) {
                if (in_array($val, $aryInitialCheckedBoxes)) {
                    unset($aryCheckedBoxes[$key]);
                }
            }
        }

        // get product id which need to be updated to unfeatured product
        if (!empty($aryUncheckedBoxes)) {
            foreach ($aryUncheckedBoxes as $key => $val) {
                if (in_array($val, $aryInitialUnCheckedBoxes)) {
                    unset($aryUncheckedBoxes[$key]);
                }
            }
        }
        $aryProductId = $aryCheckedBoxes + $aryUncheckedBoxes;  // array product id which need to be updated
    }


    /**
     * @author: datdb
     * Save product action
     */
    public function saveAction()
    {
        $data = $this->getRequest()->getPost();
        $this->prepareSave($data, $aryProductId, $aryCheckedBoxes, $aryUncheckedBoxes);

        if (!empty($aryProductId)) {
            $attributes = Mage::getSingleton('catalog/config')->getProductAttributes();
            $aryProduct = Mage::getModel('catalog/product')->getCollection()
                ->addAttributeToFilter('entity_id', array('in' => $aryProductId))
                ->addAttributeToSelect($attributes);
            try {
                // update featured attribute
                foreach ($aryProduct as $product) {
                    if (in_array($product->getData('entity_id'), $aryCheckedBoxes)) {
                        $product->load()->setFeatured(1)->save();
                    } else if (in_array($product->getData('entity_id'), $aryUncheckedBoxes)){
                        $product->load()->setFeatured(0)->save();
                    }
                }
            } catch (Mage_Core_Exception $e) {
                $this->_getSession()->addError($e->getMessage())->setProductData($data);
                $this->_redirect('*/*/');
            } catch (Exception $e) {
                Mage::logException($e);
                $this->_getSession()->addError($e->getMessage());
                $this->_redirect('*/*/');
            }
        }
        $this->_getSession()->addSuccess($this->__('The product has been saved.'));
        $this->_redirect('*/*/');
    }

}