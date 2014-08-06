<?php
/**
 * install script Mage_Catalog_Model_Resource_Setup
 *
 * add attibute status for category: new, hot,...
 */
$installer = $this;
$installer->startSetup();
/*$entityTypeId     = $installer->getEntityTypeId('catalog_category');
$attributeSetId   = $installer->getDefaultAttributeSetId($entityTypeId);
$attributeGroupId = $installer->getDefaultAttributeGroupId($entityTypeId, $attributeSetId);*/
$installer->addAttribute('catalog_category', 'status',  array(
    'type'     => 'varchar',
    'label'    => 'Status',
    'input'    => 'text',
    'global'   => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    'visible'           => true,
    'required'          => false,
    'user_defined'      => false,
    'group'             => 'General',
));
/*$installer->addAttributeToGroup(
    $entityTypeId,
    $attributeSetId,
    $attributeGroupId,
    'status',
    '11'                    //last Magento's attribute position in General tab is 10
);*/
//add status for all category
/*$attributeId = $installer->getAttributeId($entityTypeId, 'status');
$installer->run("INSERT INTO `{$installer->getTable('catalog_category_entity_varchar')}`
                  (`entity_type_id`, `attribute_id`, `entity_id`, `value`)
    SELECT '{$entityTypeId}', '{$attributeId}', `entity_id`, 'normal'
        FROM `{$installer->getTable('catalog_category_entity')}`;");*/

//this will set data of your custom attribute for root category
Mage::getModel('catalog/category')
    ->load(1)
    ->setImportedCatId(0)
    ->setInitialSetupFlag(true)
    ->save();
//this will set data of your custom attribute for default category
Mage::getModel('catalog/category')
    ->load(2)
    ->setImportedCatId(0)
    ->setInitialSetupFlag(true)
    ->save();

$installer->endSetup();