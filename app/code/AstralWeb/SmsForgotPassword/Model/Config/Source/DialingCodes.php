<?php
namespace AstralWeb\SmsForgotPassword\Model\Config\Source;

class DialingCodes implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * International Country Calling Codes
     * and International Dialing Prefixes Country Dialing Code List
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => 886, 'label' => __('Taiwan')],
            ['value' => 81, 'label' => __('Japan')],
            ['value' => 44, 'label' => __('United Kingdom')],
            ['value' => 1, 'label' => __('United States of America')],
            ['value' => 84, 'label' => __('Viet Nam')],
            ['value' => 65, 'label' => __('Singapore')],
            ['value' => 86, 'label' => __('China')]
        ];
    }
}
