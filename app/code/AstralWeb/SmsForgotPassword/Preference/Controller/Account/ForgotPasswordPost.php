<?php
/**
 *
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace AstralWeb\SmsForgotPassword\Preference\Controller\Account;

use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Model\AccountManagement;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Escaper;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Exception\SecurityViolationException;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\CustomerFactory;
use Magento\Customer\Model\Customer;
use AstralWeb\SmsForgotPassword\Helper\Data as HelperData;
use Magento\Customer\Model\ResourceModel\CustomerFactory as CustomerResource;
use Magento\Framework\Stdlib\DateTime\DateTime;

/**
 * ForgotPasswordPost controller
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class ForgotPasswordPost extends \Magento\Customer\Controller\AbstractAccount
{
    /**
     * @var \Magento\Customer\Api\AccountManagementInterface
     */
    protected $customerAccountManagement;

    /**
     * @var \Magento\Framework\Escaper
     */
    protected $escaper;

    /**
     * @var Session
     */
    protected $session;
    /**
     * @var Data
     */
    protected $helperData;
    /**
     * @var CustomerResource
     */
    protected $customerResourceFactory;
    /**
     * @var DateTime
     */
    protected $date;

    /**
     * ForgotPasswordPost constructor.
     * @param Context $context
     * @param Session $customerSession
     * @param AccountManagementInterface $customerAccountManagement
     * @param Escaper $escaper
     * @param CustomerRepositoryInterface $customerRepository
     * @param CustomerFactory $customerFactory
     * @param HelperData $helperData
     * @param CustomerResource $customerResourceFactory
     * @param DateTime $date
     */
    public function __construct(
        Context $context,
        Session $customerSession,
        AccountManagementInterface $customerAccountManagement,
        Escaper $escaper,
        CustomerRepositoryInterface $customerRepository,
        CustomerFactory $customerFactory,
        HelperData $helperData,
        CustomerResource $customerResourceFactory,
        DateTime $date
    ) {
        $this->session = $customerSession;
        $this->customerAccountManagement = $customerAccountManagement;
        $this->escaper = $escaper;
        $this->customerRepository = $customerRepository;
        $this->customerFactory = $customerFactory;
        $this->helperData = $helperData;
        $this->customerResourceFactory = $customerResourceFactory;
        $this->date = $date;
        parent::__construct($context);
    }

    /**
     * Forgot customer password action
     *
     * @return \Magento\Framework\Controller\Result\Redirect
     * @SuppressWarnings(PHPMD)
     */
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $email = (string)$this->getRequest()->getPost('email');
        if ($email) {
            if (!\Zend_Validate::is($email, \Magento\Framework\Validator\EmailAddress::class)) {
                $this->session->setForgottenEmail($email);
                $this->messageManager->addErrorMessage(__('Please correct the email address.'));
                return $resultRedirect->setPath('*/*/forgotpassword');
            }
            if ($this->helperData->isEnabled()) {
                $customer = $this->getCustomerByEmail($email);
                if ($customer) {
                    $maxReset = $this->helperData->getMaxForgotPassword();
                    $customerCountReset = $this->getLimitResetRequestPerDay($customer);
                    if ($customerCountReset >= 0 && $customerCountReset > $maxReset) {
                        $this->messageManager->addErrorMessage(__("密碼重設要求過多次數，請稍後再試，或是與我們進行聯繫。\n"));
                        return $resultRedirect->setPath('*/*/forgotpassword');
                    }
                } elseif (!$customer) {
                    $this->messageManager->addErrorMessage(__("No account associated with this email."));
                    return $resultRedirect->setPath('*/*/forgotpassword');
                }

                try {
                    $this->customerAccountManagement->initiatePasswordReset(
                        $email,
                        AccountManagement::EMAIL_RESET
                    );
                    $this->messageManager->addSuccessMessage($this->getSuccessMessage($email));
                    return $resultRedirect->setPath('*/*/');
                } //catch (NoSuchEntityException $exception) {
                    // Do nothing, we don't want anyone to use this action to determine which email accounts are registered.
                    //}
                catch (SecurityViolationException $exception) {
                    var_dump($exception->getMessage());die;
                    $this->messageManager->addErrorMessage($exception->getMessage());
                    return $resultRedirect->setPath('*/*/forgotpassword');
                } catch (\Exception $exception) {
                    $this->messageManager->addExceptionMessage(
                        $exception,
                        __('We\'re unable to send the password reset email.')
                    );
                    return $resultRedirect->setPath('*/*/forgotpassword');
                }
            }



        }

        $this->messageManager->addErrorMessage(__('Please enter your email.'));
        return $resultRedirect->setPath('*/*/forgotpassword');
    }

    /**
     * @param $email
     * @return bool
     */
    private function getCustomerByEmail($email)
    {
        try{
            $account = $this->customerRepository->get($email);
            $customer = $this->customerFactory->create()->load($account->getId());
        } catch (NoSuchEntityException $e) {
            $customer = false;
        }

        return $customer;
    }
    private function getLimitResetRequestPerDay(Customer $customer)
    {
        $currentDate = $this->date->timestamp();
        $customerCountReset = (int)$customer->getCountReset();
        $resetDate = $this->date->timestamp($customer->getDateReset());
        //Limit the number of password reset request per day. Use 0 to disable.
        $customerResetDate = $resetDate + 86400;
        if ($currentDate > $customerResetDate) {
            $customerCountReset = 0;
            $insertDate = $this->date->gmtDate();
            $customerData = $customer->getDataModel();
            $customerData->setCustomAttribute('count_reset', $customerCountReset);
            $customerData->setCustomAttribute('date_reset', $insertDate);
            $customer->updateData($customerData);
            $customerResource = $this->customerResourceFactory->create();
            $customerResource->saveAttribute($customer, 'count_reset');
            $customerResource->saveAttribute($customer, 'date_reset');
        }
        
        return $customerCountReset;
    }
    /**
     * @param $phoneNumber
     * @param $message
     * @return bool|string
     */
    private function sendSMS($phoneNumber, $message)
    {
        if ($phoneNumber && $message ) {
            $countryCallingCode = $this->helperData->getCountryCallingCode();
            $phoneNumber = $countryCallingCode . (int) $phoneNumber;
            return $this->helperData->sendSMS($phoneNumber, $message);
        }
        return false;
    }
    /**
     * Retrieve success message
     *
     * @param string $email
     * @return \Magento\Framework\Phrase
     */
    protected function getSuccessMessage($email)
    {
        if ($this->helperData->isEnabled()) {
            $customer = $this->getCustomerByEmail($email);
            if ($customer) {
                $customerCountReset = (int)$customer->getCountReset();
                $address = $customer->getPrimaryBillingAddress();
                $phoneNumber = ($address) ? $address->getTelephone() : '';
                $message = __("如果有與%1關聯的帳戶，您將收到一封包含重置密碼連結的電子郵件  \n", $email);
                if ($customerCountReset <= 3) {
                    $message = __("如果有與%1關聯的帳戶，您將收到一封包含重置密碼連結的電子郵件與手機簡訊  \n", $email);
                }

                $insertDate = $this->date->gmtDate();
                $customerData = $customer->getDataModel();
                $customerData->setCustomAttribute('count_reset', $customerCountReset + 1);
                $customerData->setCustomAttribute('date_reset', $insertDate);
                $customer->updateData($customerData);
                $customerResource = $this->customerResourceFactory->create();
                $customerResource->saveAttribute($customer, 'count_reset');
                $customerResource->saveAttribute($customer, 'date_reset');
                $this->sendSMS($phoneNumber, $message);
                return $message;
            }
        }
        return __(
            'If there is an account associated with %1 you will receive an email with a link to reset your password.',
            $this->escaper->escapeHtml($email)
        );
    }
}
