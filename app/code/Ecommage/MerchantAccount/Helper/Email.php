<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ecommage\MerchantAccount\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;

class Email
{
    /**
     * @var \Magento\Framework\Mail\Template\TransportBuilder
     */
    protected $_transportBuilder;

    /**
     * @var \Magento\Framework\Translate\Inline\StateInterface
     */
    protected $inlineTranslation;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $_logger;

    /**
     * Email constructor.
     * @param \Psr\Log\LoggerInterface $logger
     * @param \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder
     * @param \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation
     * @param ScopeConfigInterface $scopeConfig
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Magento\Framework\Translate\Inline\StateInterface $inlineTranslation,
        ScopeConfigInterface $scopeConfig,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->_logger = $logger;
        $this->_transportBuilder = $transportBuilder;
        $this->inlineTranslation = $inlineTranslation;
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
    }

    /**
     * @param $templateIdentifier
     * @param $area
     * @param $fromEmailIdentifier
     * @param $toEmail
     * @param array $templateVars
     * @param string $replyTo
     * @param int $storeId
     * @param string $scope
     * @return bool
     */
    public function sendTransactionEmail(
        $templateIdentifier,
        $area,
        $fromEmailIdentifier,
        $toEmail,
        $templateVars = [],
        $replyTo = '',
        $storeId = \Magento\Store\Model\Store::DEFAULT_STORE_ID,
        $scope = ScopeConfigInterface::SCOPE_TYPE_DEFAULT
    ) {
        $templateId = $this->scopeConfig->getValue($templateIdentifier, $scope);
        if(!$templateId) return true;

        $this->inlineTranslation->suspend();
        $transportBuilder = $this->_transportBuilder
            ->setTemplateIdentifier($templateId)
            ->setTemplateOptions(
                [
                    'area' => $area,
                    'store' => $storeId,
                ]
            )
            ->setTemplateVars($templateVars)
            ->setFrom($this->scopeConfig->getValue($fromEmailIdentifier, $scope))
            ->setReplyTo($replyTo?replyTo:$this->scopeConfig->getValue($fromEmailIdentifier, $scope));

        if (is_array($toEmail)) {
            foreach ($toEmail as $em) {
                $transportBuilder->addTo($em);
            }
        } else {
            $transportBuilder->addTo($toEmail);
        }

        $transport = $transportBuilder->getTransport();
        try {
            $transport->sendMessage();
        } catch (\Exception $e) {
            $this->_logger->error($e->getMessage());
        }

        $this->inlineTranslation->resume();
    }
}
