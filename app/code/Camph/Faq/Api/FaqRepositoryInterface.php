<?php 
namespace Camph\Faq\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface FaqRepositoryInterface
{
    
    
    
    public function save(\Camph\Faq\Api\Data\FaqInterface $faq);
    
    
    
    public function getById($faqId);
    
   
    
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
        );
    
    
    public function delete(\Camph\Faq\Api\Data\FaqInterface $faq);
    
    /**
     * Delete Faq by ID
     * @param string $faqId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    
    public function deleteById($faqId);
}

?>