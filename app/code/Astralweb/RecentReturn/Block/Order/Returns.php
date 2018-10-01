<?php
/**
 * Created by PhpStorm.
 * User: camph
 * Date: 27/08/2018
 * Time: 18:32
 */
namespace Astralweb\RecentReturn\Block\Order;
class Returns extends \Magento\Framework\View\Element\Template
{
    protected $registry;
    public function __construct(\Magento\Framework\View\Element\Template\Context $context, \Magento\Framework\Registry $registry)
    {
        $this->registry = $registry;
        parent::__construct($context);
    }
    public function getDatas(){
        return $this->registry->registry('data_return');
    }
}