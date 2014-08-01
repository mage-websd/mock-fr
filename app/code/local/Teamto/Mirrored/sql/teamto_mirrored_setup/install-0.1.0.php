<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 7/31/14
 * Time: 2:23 PM
 */ 
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();
$entityTypeId     = $installer->getEntityTypeId('catalog_category');
$attributeSetId   = $installer->getDefaultAttributeSetId($entityTypeId);
$attributeGroupId = $installer->getDefaultAttributeGroupId($entityTypeId, $attributeSetId);

// add attribute mirrored to category parrent
$installer->addAttribute('catalog_category', 'mirrored_to',  array(
    'type'     => 'int',
    'label'    => 'Mirrored to',
    'input'    => 'select',
    'visible'           => true,
    'required'          => false,
    'user_defined'      => false,
    'group'             => 'General',
    'source'            => 'mirrored/resource_category_mirrored'
));

/*$installer->addAttributeToGroup(
    $entityTypeId,
    $attributeSetId,
    $attributeGroupId,
    'mirrored_to',
    '11'
);*/

// add attribute mirrored category children
$installer->addAttribute('catalog_category', 'mirrored_child',  array(
    'type'     => 'text',
    'label'    => 'Mirrored Child',
    'input'    => 'hidden',
    'visible'           => true,
    'required'          => false,
    'user_defined'      => false,
));

//this will set data of your custom attribute for root category
Mage::getModel('catalog/category')
    ->load(1)
    ->setImportedCatId(0)
    ->setInitialSetupFlag(true)
    ->save();

$installer->endSetup();