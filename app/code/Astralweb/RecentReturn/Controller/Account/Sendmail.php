<?php
/**
 * Created by PhpStorm.
 * User: camph
 * Date: 29/08/2018
 * Time: 11:46
 */
namespace Astralweb\RecentReturn\Controller\Account;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Framework\Translate\Inline\StateInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
class Sendmail extends \Magento\Framework\App\Action\Action
{
    protected $coreRegistry;
    protected $resultPageFactory;
    protected $_transportBuilder;
    protected $inlineTranslation;
    protected $resultJsonFactory;
    protected $_helper;
    protected $scopeConfig;
    protected $a;
    private   $dataPersistor;
    public function __construct(
        Context $context,
        JsonFactory $resultJsonFactory,
        \Astralweb\RecentReturn\Helper\Returns $helper,
        TransportBuilder $transportBuilder,
        ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Registry $registry,
        StateInterface $inlineTranslation)
    {
        $this->coreRegistry = $registry;
        $this->scopeConfig = $scopeConfig;
        $this->_transportBuilder = $transportBuilder;
        $this->inlineTranslation = $inlineTranslation;
        $this->_helper = $helper;
        $this->resultJsonFactory = $resultJsonFactory;
        parent::__construct($context);
    }
    public function execute()
    {
        $customerSession = $this->_helper->getCustomerData();
        if($customerSession->isLoggedIn()) {
            $data = $this->getRequest()->getParams();
            $message = $this->getRequest()->getParam('message');
            $order_id = $this->getRequest()->getParam('orderId');
            if(empty($data['cid'])){
                $this->_redirect('customer/account/returns/order_id/'.$order_id);
            }
            else {
                $this->coreRegistry->register('data_return', $data);
                $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
                $emailTemplate = $this->scopeConfig->getValue('order/email/email_template', $storeScope);
                $emailRecipient = $this->scopeConfig->getValue('order/email/recipient_email', $storeScope);
                $sender = [
                    'name' => $customerSession->getCustomer()->getName(),
                    'email' => $customerSession->getCustomer()->getEmail()
                ];
                $transport = $this->_transportBuilder->setTemplateIdentifier($emailTemplate)
                    ->setTemplateOptions(
                        [
                            'area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                            'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID,
                        ]
                    )
                    ->setTemplateVars([
                        'name' => $customerSession->getCustomer()->getName(),
                        'email' => $customerSession->getCustomer()->getEmail(),
                        'order_id' => $order_id,
                        'message' => $message
                    ])
                    ->setFrom($sender)
                    ->addTo($emailRecipient)
                    ->getTransport();
                $transport->sendMessage();
                $this->inlineTranslation->resume();
                $this->messageManager->addSuccess(
                    __('Thanks for contacting us with your comments and request. We\'ll respond to you very soon.')
                );
                $this->_redirect('customer/account/index');
            }
        }
        else{
            $this->_redirect('customer/account/login');
        }


    }

}