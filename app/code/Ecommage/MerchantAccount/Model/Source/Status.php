<?php
/**
 * Created by PhpStorm.
 * User: camph
 * Date: 26/09/2018
 * Time: 16:41
 */
namespace Ecommage\MerchantAccount\Model\Source;
use Vnecoms\Vendors\Model\Vendor;
class Status extends \Vnecoms\Vendors\Model\Source\Status
{
    const STATUS_WAIT_ACTIVE = 5;

    public function getAllOptions()
    {
        if ($this->_options === null) {
            $this->_options = [
                ['label' => __('Pending'), 'value' => Vendor::STATUS_PENDING],
                ['label' => __('Active'), 'value' => Vendor::STATUS_APPROVED],
                ['label' => __('Approved'), 'value' => self::STATUS_WAIT_ACTIVE],
                ['label' => __('Disabled'), 'value' => Vendor::STATUS_DISABLED],
                ['label' => __('Expired'), 'value' => Vendor::STATUS_EXPIRED],
            ];
        }
        return $this->_options;
    }
}