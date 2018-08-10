<?php
namespace Camph\Faq\Controller\Adminhtml\FaqGroup;

use Magento\Framework\Exception\LocalizedException;

class Save extends \Magento\Backend\App\Action
{
    
    /**
     * @var \Magento\Framework\App\Request\DataPersistorInterface
     */
    private $dataPersistor;
    
    /**
     * @var \Camph\Faq\Model\FaqGroup
     */
    private $faqModel;
    
    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Camph\Faq\Model\FaqGroup
     * @param \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Camph\Faq\Model\FaqGroup $faqModel,
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
        ) {
            $this->dataPersistor = $dataPersistor;
            $this->faqModel = $faqModel;
            parent::__construct($context);
    }
    
    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        
        if ($data) {
            $id = $this->getRequest()->getParam('faqgroup_id');
            
            $model = $this->faqModel->load($id);
            if (!$model->getId() && $id) {
                $this->messageManager->addErrorMessage(__('This FAQgroup no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            }
            
            
            $model->setData($data);
            
            try {
                $model->save();
                $this->messageManager->addSuccessMessage(__('You saved the FAQgroup.'));
                $this->dataPersistor->clear('camph_faq_faqgroup');
                
                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['faqgroup_id' => $model->getId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager
                ->addExceptionMessage($e, __('Something went wrong while saving the Faqgroup.'));
            }
            
            $this->dataPersistor->set('camph_faq_faqgroup', $data);
            return $resultRedirect->setPath('*/*/edit',
                [
                    'faqgroup_id' => $this->getRequest()->getParam('faqgroup_id')
                ]
                );
        }
        return $resultRedirect->setPath('*/*/');
    }
   
}
