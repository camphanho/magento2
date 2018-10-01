<?php
/**
 * Created by PhpStorm.
 * User: camph
 * Date: 27/09/2018
 * Time: 11:03
 */
namespace  Ecommage\MerchantAccount\Block\Adminhtml\Vendor;
class Edit extends \Vnecoms\Vendors\Block\Adminhtml\Vendor\Edit
{
    protected function _construct()
    {
        $this->_objectId = 'id';
        $this->_blockGroup = 'Vnecoms_Vendors';
        $this->_controller = 'adminhtml_vendor';

        parent::_construct();
        $this->updateButton('save', 'label', __('Save Seller'));

        $vendor = $this->_coreRegistry->registry('current_vendor');
        $this->buttonList->add(
            'create_key',
            [
                'label' => __('Create Key'),
                'onclick' => 'setLocation(\'' . $this->getUrl(
                        'vendors/merchant/confirmation',
                        ['id' => $vendor->getId()]
                    ) . '\')',
            ],
            8
        );
        $this->buttonList->add(
            'customer_account',
            [
                'label' => __('View Customer Account'),
                'onclick' => 'setLocation(\'' . $this->getUrl(
                        'customer/index/edit',
                        ['id' => $vendor->getCustomer()->getId()]
                    ) . '\')',
            ],
            9
        );

        $this->buttonList->add(
            'save_and_continue_edit',
            [
                'class' => 'save',
                'label' => __('Save and Continue Edit'),
                'data_attribute' => [
                    'mage-init' => ['button' => ['event' => 'saveAndContinueEdit', 'target' => '#edit_form']],
                ]
            ],
            10
        );


        return $this;
    }
}