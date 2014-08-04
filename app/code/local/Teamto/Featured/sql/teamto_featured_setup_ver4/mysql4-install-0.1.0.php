<?php

//var_dump('dat');die;

/**
 * Created by PhpStorm.
 * User: root
 * Date: 7/29/14
 * Time: 2:28 PM
 */ 
/* @var $installer Mage_Core_Model_Resource_Setup */
$installer = $this;

$installer->startSetup();

$installer->addAttribute('catalog_product', 'featured', array(
    'label'         => 'Featured Product',
    'group'         => 'General',
    'type'          => 'int',
    'input'         => 'checkbox',
    'source'        => 'teamto_featured/resource_featured_attribute_unit',
    'global'        => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_STORE,
    'visible'       => true,
    'required'      => false,
    'user_defined'  => false
));

$installer->endSetup();