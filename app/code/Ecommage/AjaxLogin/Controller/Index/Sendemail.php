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
        $resource = $this->_objectManager->create('\Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);
        $wtf = $connection->fetchAll('select core_config_data.value from `core_config_data` where core_config_data.path = "email/demo/template"');
        foreach ($wtf as $value){
            foreach ($value as $v){
                $r = $v;
            }
        }
        $data['template'] = $r;
        $this->_eventManager->dispatch('ecommage_login_success',['data' => $data]);
        exit;
    }
}