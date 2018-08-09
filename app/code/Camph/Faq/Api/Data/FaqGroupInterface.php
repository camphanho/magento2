<?php 
namespace Camph\Faq\Api\Data;

interface FaqGroupInterface
{
    
    const GROUPNAME = 'groupname';
    const FAQIDS = 'faqids';
    const FAQGROUP_ID = 'faqgroup_id';
    const GROUPCODE = 'groupcode';
    const WIDTH = 'width';
    
    
    
    public function getFaqgroupId();
    
    
    
    public function setFaqgroupId($faqgroupId);
    
    
    
    public function getGroupname();
    
   
    
    public function setGroupname($groupname);
    
    
    
    public function getGroupcode();
    
    
    
    public function setGroupcode($groupcode);
    
    
   
    /**
     * Get width
     * @return string|null
     */
    
    public function getWidth();
    
    
    
    public function setWidth($width);
    
    /**
     * Get faqids
     * @return string|null
     */
    
    public function getFaqids();
    
    
    
    public function setFaqids($faqids);
    
    
    
}

?>