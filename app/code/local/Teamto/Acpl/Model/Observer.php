<?php
class Teamto_Acpl_Model_Observer 
{
    public function show_field_news($observer)
    {
        $form = $observer->getEvent()->getForm();
        $fieldset = $form->addFieldset(
            'news_fieldset',
            array(
                'legend' => 'News',
                'class' => 'fieldset-wide'
            )
        );
        $fieldset->addField('is_news', 'checkbox', array(
            'name'  => 'is_news',
            'label' => 'Is Page News :',
            'title' => 'Page News',
            'onclick' => 'this.value = this.checked ? 1 : null',
        ));
//        $fieldset->addField('img', 'file', array(
//            'name'  => 'img',
//            'label' => 'Image :',
//        ));
    }
    
    public function save_field_news($observer)
    {
        $model = $observer->getEvent()->getPage();
        $params = $observer->getRequest()->getParams();
        if(isset($params['is_news']) && $params['is_news'] != "")
        {
//             if ($_FILES['img']['name'] != '') {
//                $uploader = new Varien_File_Uploader('img');
//                $uploader->setAllowedExtensions(array('jpg', 'jpeg', 'gif', 'png'));
//                $path = Mage::getBaseDir('media') . DS . 'new_img';
//                $uploader->setAllowCreateFolders(true);
//                $uploader->save($path, $_FILES['img']['name']);
//            }
//            $model->setImg($params['img']);
            $model->setIs_news($params['is_news']);
        }  
    }
}
