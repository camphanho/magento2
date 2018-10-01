<?php
namespace AstralWeb\SmsForgotPassword\Helper;

use Magento\Store\Model\ScopeInterface;
use AstralWeb\Infobip\Helper\Data as HelperData;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    const SMSFORGOTPASSWORD_ENABLED = 'smsforgotpassword/general/enabled';
    const SMSFORGOTPASSWORD_API_URL = 'smsforgotpassword/general/api_url';
    const SMSFORGOTPASSWORD_AUTHORIZATION_KEY = 'smsforgotpassword/general/auth_key';
    const SMSFORGOTPASSWORD_COUNTRY_CALLING_CODE  = 'smsforgotpassword/general/country_calling_code';
    const SMSFORGOTPASSWORD_MAX_RESET = 'smsforgotpassword/general/max_reset';
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * Data constructor.
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        parent::__construct($context);
        $this->storeManager     = $storeManager;
    }

    /**
     * @param $path
     * @return mixed
     */
    public function getConfig($path)
    {
        return $this->scopeConfig->getValue($path, ScopeInterface::SCOPE_STORE);
    }

    /**
     * @return boolean
     */
    public function isEnabled()
    {
        return $this->getConfig(self::SMSFORGOTPASSWORD_ENABLED);
    }

    /**
     * @return string
     */
    public function getCountryCallingCode()
    {
        return '+'. $this->getConfig(self::SMSFORGOTPASSWORD_COUNTRY_CALLING_CODE);
    }

    /**
     * @return boolean
     */
    public function getMaxForgotPassword()
    {
        return (int)$this->getConfig(self::SMSFORGOTPASSWORD_MAX_RESET);
    }

    /**
     * @return \Magento\Store\Api\Data\StoreInterface
     */
    public function getStore()
    {
        return $this->storeManager->getStore();
    }
    /**
     * @param $phoneNumber
     * @param $message
     * @return string
     */
    public function sendSMS($phoneNumber, $message)
    {

        $apiUrl = $this->getConfig(self::SMSFORGOTPASSWORD_API_URL);
        $authKey = $this->getConfig(HelperData::CONFIG_AUTHORIZATION_KEY);
        // @codingStandardsIgnoreStart
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL            => $apiUrl,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => '',
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => 'POST',
            CURLOPT_POSTFIELDS     => '{"from":"VERIFY", "to":"'. $phoneNumber .'", "text":"'.$message.'" }',
            CURLOPT_HTTPHEADER     => [
                'accept: application/json',
                'authorization: Basic ' . $authKey,
                'content-type: application/json'
            ],
        ]);
        $response = curl_exec($curl);
        $error = curl_error($curl);
        curl_close($curl);
        // @codingStandardsIgnoreEnd
        $result = ['status' => 'OK', 'data' => $response];

        if ($error) {
            $result = ['status' => 'FAIL', 'msg' => $error];
        }

        return $result;
    }
    
}