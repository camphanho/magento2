<?php
namespace Camph\Faq\Model\ResourceModel;

class Faq extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    
    /**
     * Define resource model
     *
     * @return void
     */
    public function _construct()
    {
        $this->_init('camph_faq', 'faq_id');
    }
}
