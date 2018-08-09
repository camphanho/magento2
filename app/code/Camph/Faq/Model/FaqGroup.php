<?php
namespace Camph\Faq\Model;

use Camph\Faq\Api\Data\FaqGroupInterface;

class FaqGroup extends \Magento\Framework\Model\AbstractModel implements FaqGroupInterface
{
    
   
    public function _construct()
    {
        $this->_init('Camph\Faq\Model\ResourceModel\FaqGroup');
    }
    
    /**
     * Get faqgroup_id
     * @return string
     */
    public function getFaqgroupId()
    {
        return $this->getData(self::FAQGROUP_ID);
    }
    
   
    public function setFaqgroupId($faqgroupId)
    {
        return $this->setData(self::FAQGROUP_ID, $faqgroupId);
    }
    
    /**
     * Get groupname
     * @return string
     */
    public function getGroupname()
    {
        return $this->getData(self::GROUPNAME);
    }
    
    
    public function setGroupname($groupname)
    {
        return $this->setData(self::GROUPNAME, $groupname);
    }
    
    /**
     * Get groupcode
     * @return string
     */
    public function getGroupcode()
    {
        return $this->getData(self::GROUPCODE);
    }
    
    
    public function setGroupcode($groupcode)
    {
        return $this->setData(self::GROUPCODE, $groupcode);
    }
    
   
    /**
     * Get width
     * @return string
     */
    public function getWidth()
    {
        return $this->getData(self::WIDTH);
    }
    
    
    public function setWidth($width)
    {
        return $this->setData(self::WIDTH, $width);
    }
    
    
    public function getFaqids()
    {
        return $this->getData(self::FAQIDS);
    }
    
   
    public function setFaqids($faqids)
    {
        return $this->setData(self::FAQIDS, $faqids);
    }
    
}
