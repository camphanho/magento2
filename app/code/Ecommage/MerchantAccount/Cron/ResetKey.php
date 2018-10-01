<?php
/**
 * Created by PhpStorm.
 * User: camph
 * Date: 27/09/2018
 * Time: 18:36
 */
namespace Ecommage\MerchantAccount\Cron;
use Ecommage\MerchantAccount\Helper\Data;
use Ecommage\MerchantAccount\Helper\Email;

class ResetKey
{
    protected $merchantHelper;
    protected $emailHelper;

    /**
     * ResetKey constructor.
     * @param Data $merchantHelper
     * @param Email $emailHelper
     */
    public function __construct(
        Data $merchantHelper,
        Email $emailHelper
    )
    {
        $this->merchantHelper = $merchantHelper;
        $this->emailHelper = $emailHelper;
    }
    public function resetKey()
    {

    }

}