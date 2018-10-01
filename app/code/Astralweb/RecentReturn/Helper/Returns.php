<?php
/**
 * Created by PhpStorm.
 * User: camph
 * Date: 27/08/2018
 * Time: 16:39
 */
namespace Astralweb\RecentReturn\Helper;

use Magento\Framework\App\Helper\Context;
use Magento\Sales\Model\ResourceModel\Order\CollectionFactory;

class Returns extends \Magento\Framework\App\Helper\AbstractHelper
{
    protected $_storeManager;
    protected $_orderCollectionFactory;
    protected $_currentOrderId;

    public function __construct(
        Context $context,
        CollectionFactory $orderCollectionFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    )
    {
        $this->_storeManager = $storeManager;
        $this->_orderCollectionFactory = $orderCollectionFactory;
        parent::__construct($context);
    }
    public function getViewUrl($order)
    {
        return $this->_getUrl('customer/account/returns', ['order_id' => $order->getId()]);
    }
    public function getBaseUrl(){
        return $this->_storeManager->getStore()->getBaseUrl();
    }
    public function getOrderId(){
        $orderId = $this->_request->getParam('order_id');
        return $orderId;
    }

    public function getCustomerData(){
        $om = \Magento\Framework\App\ObjectManager::getInstance();
        $customerSession = $om->get('Magento\Customer\Model\Session');
        return $customerSession;
    }
}
