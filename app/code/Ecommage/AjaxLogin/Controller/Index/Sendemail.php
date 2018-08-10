<?php
/**
 * Created by PhpStorm.
 * User: camph
 * Date: 07/08/2018
 * Time: 10:22
 */
namespace Ecommage\AjaxLogin\Controller\Index;

class Sendemail extends \Magento\Framework\App\Action\Action
{

    public function execute()
    {
        $data = $this->getRequest()->getParams();
        $this->_eventManager->dispatch('ecommage_login_success',['data' => $data]);
        exit;
    }
}