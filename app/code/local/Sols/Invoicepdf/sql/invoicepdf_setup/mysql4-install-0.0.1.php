<?php

/** @var Mage_Core_Model_Resource_Setup $installer */
$installer = $this;

$installer->startSetup();

$setup = new Mage_Eav_Model_Entity_Setup('core_setup');


$entityTypeId     = $setup->getEntityTypeId('customer');
$attributeSetId   = $setup->getDefaultAttributeSetId($entityTypeId);
$attributeGroupId = $setup->getDefaultAttributeGroupId($entityTypeId, $attributeSetId);

$installer->addAttribute(
    'customer',
    'invoicepdf',
    array(
        'group'                => 'Default',
        'type'                 => 'varchar',
        'label'                => 'PDF invoice email',
        'input'                => 'text',
        'source'               => '',
        'global'               => Mage_Catalog_Model_Resource_Eav_Attribute::SCOPE_GLOBAL,
        'required'             => 0,
        'visible'              => 1,
        'default'              => 0,
        'visible_on_front'     => 1,
        'used_for_price_rules' => 0,
        'adminhtml_only'       => 0,
    )
);

$setup->addAttributeToGroup(
    $entityTypeId,
    $attributeSetId,
    $attributeGroupId,
    'invoicepdf',
    '999'  //sort_order
);

$attribute   = Mage::getSingleton("eav/config")->getAttribute("customer", "invoicepdf");

$used_in_forms=array();

$used_in_forms[]="adminhtml_customer";
$used_in_forms[]="checkout_register";
$used_in_forms[]="customer_account_create";
$used_in_forms[]="customer_account_edit";
$used_in_forms[]="adminhtml_checkout";
$attribute->setData("used_in_forms", $used_in_forms)
        ->setData("is_used_for_customer_segment", true)
        ->setData("is_system", 0)
        ->setData("is_user_defined", 1)
        ->setData("is_visible", 1)
        ->setData("sort_order", 100);
$attribute->save();

$installer->endSetup();
