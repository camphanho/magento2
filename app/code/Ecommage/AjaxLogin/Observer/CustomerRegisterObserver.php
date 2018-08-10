<?php
/**
 * Created by PhpStorm.
 * User: camph
 * Date: 07/08/2018
 * Time: 09:45
 */
namespace Ecommage\AjaxLogin\Observer;

use Magento\Framework\Event\ObserverInterface;
use Ecommage\AjaxLogin\Helper\Email;


class CustomerRegisterObserver implements ObserverInterface
{
    private $helperEmail;

    public function __construct(
        Email $helperEmail
    ) {
        $this->helperEmail = $helperEmail;
    }
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $data = $observer->getData('data');
        return $this->helperEmail->sendEmail($data);
    }
}