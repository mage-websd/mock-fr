<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Subcriber
 *
 * @author baoanh
 */
require_once Mage::getModuleDir('controllers','Mage_Newsletter').DS.'SubscriberController.php';
class Teamto_Customhome_SubscriberController extends Mage_Newsletter_SubscriberController {
    public function newAction(){
         if ($this->getRequest()->isPost() && $this->getRequest()->getPost('email')) {
            $session = Mage::getSingleton('core/session');
            $customerSession = Mage::getSingleton('customer/session');
            $email = (string) $this->getRequest()->getPost('email');

            try {
                $subscriber = Mage::getModel('newsletter/subscriber')->loadByEmail($email);
                if ($subscriber->getId()) {
                    echo 'This email address is already exist';
                    return;
                }
                if (!Zend_Validate::is($email, 'EmailAddress')) {
                    echo 'Please enter a valid email address.';
                    return;
                }

                if (Mage::getStoreConfig(Mage_Newsletter_Model_Subscriber::XML_PATH_ALLOW_GUEST_SUBSCRIBE_FLAG) != 1 &&
                        !$customerSession->isLoggedIn()) {
                    echo 'Sorry, but administrator denied subscription for guests. Please <a href="%s">register</a>.'. Mage::helper('customer')->getRegisterUrl();
                    return;
                }

                $ownerId = Mage::getModel('customer/customer')
                        ->setWebsiteId(Mage::app()->getStore()->getWebsiteId())
                        ->loadByEmail($email)
                        ->getId();
                if ($ownerId !== null && $ownerId != $customerSession->getId()) {
                    echo 'This email address is already assigned to another user.';
                    return;
                }

                $status = Mage::getModel('newsletter/subscriber')->subscribe($email);
                if ($status == Mage_Newsletter_Model_Subscriber::STATUS_NOT_ACTIVE) {
                    echo 'Confirmation request has been sent.';
                    return;
                } else {
                    echo 'Thank you for your subscription.';
                    return;
                }
                
            } catch (Mage_Core_Exception $e) {
                $session->addException($e, $this->__('There was a problem with the subscription: %s', $e->getMessage()));
            } catch (Exception $e) {
                $session->addException($e, $this->__('There was a problem with the subscription.'));
            }
        }
    }
}

?>
