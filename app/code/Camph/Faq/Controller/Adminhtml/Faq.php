<?php
namespace Camph\Faq\Controller\Adminhtml;

abstract class Faq extends \Magento\Backend\App\Action
{
    
    const ADMIN_RESOURCE = 'Camph_Faq::top_level';
    private $coreRegistry;
    

    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry
        ) {
            $this->coreRegistry = $coreRegistry;
            parent::__construct($context);
    }

    public function initPage($resultPage)
    {
        $resultPage->setActiveMenu(self::ADMIN_RESOURCE)
        ->addBreadcrumb(__('Camph'), __('Camph'))
        ->addBreadcrumb(__('FAQ'), __('FAQ'));
        return $resultPage;
    }
}
