<?php 
namespace Camph\Faq\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface FaqGroupRepositoryInterface
{
    
    
    public function save(
        \Camph\Faq\Api\Data\FaqGroupInterface $faqGroup
        );
    
    
    public function getById($faqgroupId);

    
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
        );

    
    public function delete(
        \Camph\Faq\Api\Data\FaqGroupInterface $faqGroup
        );
    
  
    
    public function deleteById($faqgroupId);
}

?>