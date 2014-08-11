<?php
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();

// add attribute 'mirrored_to'
$installer->addAttribute('catalog_category', 'mirrored_to',  array(
    'type'     => 'int',
    'label'    => 'Mirrored to',
    'input'    => 'select',
    'visible'           => true,
    'required'          => false,
    'user_defined'      => false,
    'group'             => 'General',
    'source'            => 'mirrored/resource_category_mirrored',

));


// add attribute mirrored_copy_from_cate: data of sub category mirrored children
$installer->addAttribute('catalog_category', 'mirrored_copy_from_cate',  array(
    'type'     => 'int',
    'label'    => 'Mirrored Copy From Category',
    'input'    => 'hidden',
    'visible'           => true,
    'required'          => false,
    'user_defined'      => false,
));

//this will set data of attribute for root category
Mage::getModel('catalog/category')
    ->load(1)
    ->setImportedCatId(0)
    ->setInitialSetupFlag(true)
    ->save();

$installer->endSetup();