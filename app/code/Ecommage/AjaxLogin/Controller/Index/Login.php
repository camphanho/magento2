<?php
/**
 * Created by PhpStorm.
 * User: camph
 * Date: 30/07/2018
 * Time: 18:15
 */
namespace Ecommage\AjaxLogin\Controller\Index;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
class Login extends \Magento\Framework\App\Action\Action
{
    protected $resultPageFactory;

    protected $resultJsonFactory;

    public function __construct(Context $context, JsonFactory $resultJsonFactory)
    {
        $this->resultJsonFactory = $resultJsonFactory;
        parent::__construct($context);
    }
    public function execute()
    {
        $data = $this->getRequest()->getParams();
        $result = $this->resultJsonFactory->create();
        $result->setData($data);
        return $result;
    }
}