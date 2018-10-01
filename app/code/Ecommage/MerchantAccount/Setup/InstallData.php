<?php

namespace Ecommage\MerchantAccount\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Vnecoms\Vendors\Model\Vendor;
use Vnecoms\Vendors\Setup\VendorSetupFactory;
use Magento\Eav\Model\Entity\Attribute\Set as AttributeSet;
use Magento\Eav\Model\Entity\Attribute\SetFactory as AttributeSetFactory;


class InstallData implements InstallDataInterface
{
    private $vendorSetupFactory;
    private $attributeSetFactory;
    private $customerCollection;

    public function __construct(
        VendorSetupFactory $vendorSetupFactory,
        AttributeSetFactory $attributeSetFactory,
        \Magento\Customer\Model\ResourceModel\Customer\Collection $customerCollection
    )
    {
        $this->vendorSetupFactory = $vendorSetupFactory;
        $this->attributeSetFactory = $attributeSetFactory;

        $this->customerCollection = $customerCollection;
    }

    /**
     * Function install
     * @param ModuleDataSetupInterface $setup
     * @param ModuleContextInterface $context
     * @return void
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $vendorSetup = $this->vendorSetupFactory->create(['setup' => $setup]);
        $setup->startSetup();
        $vendorSetup->addAttribute(
            Vendor::ENTITY,
            'ves_key_account',
            [
                'label' => 'Account Key',
                'type' => 'varchar',
                'input' => 'text',
                'position' => 145,
                'visible' => false,
                'required' => false,
                'default' => '',
                'user_defined'=>1,
                'system' => 0,
                'used_in_profile_form' => 1,
                'used_in_registration_form' => 1,
                'visible_in_customer_form' => 0,
            ]
        );

        $setup->endSetup();
    }
}