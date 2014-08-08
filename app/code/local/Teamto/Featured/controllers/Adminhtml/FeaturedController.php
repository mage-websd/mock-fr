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
     * Save product action
     */
    public function saveAction()
    {
        $data = $this->getRequest()->getPost();

        $aryProductId = explode(',', $data['aryUncheckedBoxes']);   // get and build array of all product id
        $aryCheckedBoxes = explode(',', $data['aryCheckedBoxes']);  // get and build array of all product id which were checked

        $attributes = Mage::getSingleton('catalog/config')->getProductAttributes();
        $aryProduct = Mage::getModel('catalog/product')->getCollection()
            ->addAttributeToFilter('entity_id', array('in' => $aryProductId))
            ->addAttributeToSelect($attributes);

        if ($aryCheckedBoxes) {
            try {
                // disable indexing
                $pCollection = Mage::getSingleton('index/indexer')->getProcessesCollection();
                foreach ($pCollection as $process) {
                    $process->setMode(Mage_Index_Model_Process::MODE_MANUAL)->save();
                    //$process->setMode(Mage_Index_Model_Process::MODE_REAL_TIME)->save();
                }

                $indexingProcesses = Mage::getSingleton('index/indexer')->getProcessesCollection();
                foreach ($indexingProcesses as $process) {
                    $process->reindexEverything();
                }

                // update featured attribute
                foreach ($aryProduct as $product) {
                    if( in_array($product->getData('entity_id'), $aryCheckedBoxes) ){
                        $product->load()->setFeatured(1)->save();
                    } else {
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