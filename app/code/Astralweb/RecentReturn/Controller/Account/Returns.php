<?php

namespace Astralweb\RecentReturn\Controller\Account;

use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\Action\Action;

/**
 * Class Index
 * @package Astralweb\RecentReturn\Controller\Account\Index
 */
class Returns extends Action
{
    /**
     * @var \Magento\Sales\Model\Order
     */
    protected $order;
    /**
     * @var \Magento\Framework\Registry
     */
    protected $coreRegistry;
    /**
     * @var PageFactory
     */
    protected $resultPageFactory;

    /**
     * Returns constructor.
     * @param Context $context
     * @param \Magento\Sales\Model\Order $order
     * @param \Magento\Framework\Registry $registry
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        \Magento\Sales\Model\Order $order,
        \Magento\Framework\Registry $registry,
        PageFactory $resultPageFactory
    ) {
        $this->order = $order;
        $this->coreRegistry = $registry;
        $this->resultPageFactory = $resultPageFactory;
        return parent::__construct($context);
    }

    /**
     * Function execute
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $orderId = $this->getRequest()->getParam('order_id');
        $order = $this->order->load($orderId);
        $this->coreRegistry->register('current_order', $order);
        return $this->resultPageFactory->create();
    }
}
