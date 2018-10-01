<?php

namespace Ecommage\MerchantAccount\Controller\Adminhtml\Merchant;

use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Backend\App\Action;
use Ecommage\MerchantAccount\Helper\Data;
use Ecommage\MerchantAccount\Model\Source\Status;
use Vnecoms\Vendors\Model\Vendor;
use Vnecoms\Vendors\Helper\Email;

/**
 * Class Index
 * @package Ecommage\MerchantAccount\Controller\Adminhtml\Merchant\Index
 */
class Confirmation extends Action
{

    const PATH_LIMIT_TIME = 'vendors/create_account/set_time_link';
    const EMAIL_TEMPLATE_ACTIVE = 'vendors/create_account/email_template_active';
    /**
     * Index resultPageFactory
     * @var PageFactory
     */
    protected $resultPageFactory;
    protected $vendorFactory;
    protected $messageManager;
    protected $scopeConfig;
    protected $time;
    protected $merchantHelper;
    protected $vendorHelper;

    /**
     * Confirmation constructor.
     * @param Context $context
     * @param PageFactory $resultPageFactory
     * @param Data $helper
     * @param \Vnecoms\Vendors\Model\VendorFactory $vendorFactory
     * @param \Magento\Framework\Message\ManagerInterface $messageManager
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $time
     * @param Email $vendorHelper
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        Data $helper,
        \Vnecoms\Vendors\Model\VendorFactory $vendorFactory,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Stdlib\DateTime\DateTime $time,
        Email $vendorHelper
    )
    {
        $this->vendorHelper = $vendorHelper;
        $this->time = $time;
        $this->scopeConfig = $scopeConfig;
        $this->messageManager = $messageManager;
        $this->vendorFactory = $vendorFactory;
        $this->merchantHelper = $helper;
        $this->resultPageFactory = $resultPageFactory;
        return parent::__construct($context);
    }

    /**
     * Function execute
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $vendorId = $this->getRequest()->getParam('id');
        $isExistingVendor = (bool)$vendorId;
        $vendor = $this->vendorFactory->create();
        if ($isExistingVendor) {
            $vendor->load($vendorId);
        }

        if ($vendor->getStatus() == Vendor::STATUS_APPROVED){
            $this->messageManager->addNoticeMessage(__('Vendor was active.'));
            $this->_redirect('vendors/index/edit', ['id' => $this->getRequest()->getParam('id')]);
        }
        if ($vendor->getStatus() == Status::STATUS_WAIT_ACTIVE || $vendor->getStatus() == Vendor::STATUS_PENDING){
            $vendorData['ves_key_account'] = $this->merchantHelper->createKey();
            $vendorData['status'] = Status::STATUS_WAIT_ACTIVE;
            $vendor->addData($vendorData);
            $vendor->save();
            $store = $vendor->getStore();
            $dataVar = [
                "vendor"=> $vendor,
                "store"=>$store
            ];
            $this->vendorHelper->sendTransactionEmail(
                self::EMAIL_TEMPLATE_ACTIVE,
                \Magento\Framework\App\Area::AREA_FRONTEND,
                Vendor::XML_PATH_REGISTER_EMAIL_IDENTITY,
                $vendor->getEmail(),
                $dataVar
            );
            $this->messageManager->addSuccessMessage(__('Create new key and send new confirm email success.'));
            $this->_redirect('vendors/index/edit', ['id' => $this->getRequest()->getParam('id')]);
        }
    }
    /*public function checkKey()
    {
        $id = $this->getRequest()->getParam('id');
        $limit_time = $this->scopeConfig->getValue(self::PATH_LIMIT_TIME, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $model = $this->_objectManager->create('Vnecoms\Vendors\Model\Vendor');
        if ($id) {
            $model->load($id);
            if ($model->getStatus() == Status::STATUS_WAIT_ACTIVE){
                $create_at = $model->getVesKeyAccount();
                $create_at = base64_decode($create_at);
                $resetTime = $create_at + $limit_time*60*60;
                $now_time = $this->time->timestamp();
                if ($now_time - $resetTime < 0){
                    //reset key
                }
            }
        }
    }*/
}
