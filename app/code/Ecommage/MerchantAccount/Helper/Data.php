<?php
/**
 * Created by PhpStorm.
 * User: camph
 * Date: 26/09/2018
 * Time: 18:18
 */
namespace Ecommage\MerchantAccount\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\ObjectManagerInterface;
class Data
{
    const PATH_LIMIT_TIME = 'vendors/create_account/set_time_link';
    protected $scopeConfig;
    protected $_logger;
    protected $time;
    protected $objectManager;

    /**
     * Key constructor.
     * @param \Psr\Log\LoggerInterface $logger
     * @param ObjectManagerInterface $objectManager
     * @param ScopeConfigInterface $scopeConfig
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $time
     */
    public function __construct(
        \Psr\Log\LoggerInterface $logger,
        ObjectManagerInterface $objectManager,
        ScopeConfigInterface $scopeConfig,
        \Magento\Framework\Stdlib\DateTime\DateTime $time
    )
    {
        $this->objectManager = $objectManager;
        $this->_logger = $logger;
        $this->scopeConfig = $scopeConfig;
        $this->time = $time;
    }
    public function createKey()
    {
        $current_time = $this->time->timestamp();
        $key = base64_encode($current_time);
//        $datetimeFormat = 'Y-m-d H:i:s';
//        $date = new \DateTime();
//        $date->setTimestamp($current_time);
//        $date = date($datetimeFormat, time());
        return $key;
    }
//    public function checkKey($id)
//    {
//        //$id = $this->getRequest()->getParam('id');
//        $limit_time = $this->scopeConfig->getValue(self::PATH_LIMIT_TIME, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
//        $model = $this->_objectManager->create('Vnecoms\Vendors\Model\Vendor');
//        if ($id) {
//            $model->load($id);
//            if ($model->getStatus() == Status::STATUS_WAIT_ACTIVE){
//                $create_at = $model->getVesKeyAccount();
//                $create_at = base64_decode($create_at);
//                $resetTime = $create_at + $limit_time*60*60;
//                $now_time = $this->time->timestamp();
//                if ($now_time - $resetTime < 0){
//                    //reset key
//                }
//            }
//        }
//    }
}