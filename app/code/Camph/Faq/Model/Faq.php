<?php 
namespace Camph\Faq\Model;

use Camph\Faq\Api\Data\FaqInterface;

class Faq extends \Magento\Framework\Model\AbstractModel implements FaqInterface
{
    
   
    public function _construct()
    {
        $this->_init('Camph\Faq\Model\ResourceModel\Faq');
    }
    
    /**
     * Get faq_id
     * @return string
     */
    public function getFaqId()
    {
        return $this->getData(self::FAQ_ID);
    }
    
    
    public function setFaqId($faqId)
    {
        return $this->setData(self::FAQ_ID, $faqId);
    }
    
    
    public function getQuestion()
    {
        return $this->getData(self::QUESTION);
    }
    
    
    public function setQuestion($question)
    {
        return $this->setData(self::QUESTION, $question);
    }
    
    /**
     * Get content
     * @return string
     */
    public function getAnswer()
    {
        return $this->getData(self::ANSWER);
    }
    
    
    public function setAnswer($answer)
    {
        return $this->setData(self::ANSWER, $answer);
    }
    
    
    public function getSortorder()
    {
        return $this->getData(self::SORTORDER);
    }
    
    
    public function setSortorder($sortorder)
    {
        return $this->setData(self::SORTORDER, $sortorder);
    }
}
?>