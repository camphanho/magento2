<?php 
namespace Camph\Faq\Api\Data;

interface FaqInterface
{
    
    const QUESTION = 'question';
    const SORTORDER = 'sortorder';
    const FAQ_ID = 'faq_id';
    const ANSWER = 'answer';
    
    
    public function getFaqId();
    
    
    
    public function setFaqId($faqId);
    
    
    
    public function getQuestion();
    
    
    public function setQuestion($title);
    
    
    public function getAnswer();
    
    
    public function setAnswer($content);
    
    
    public function getSortorder();
    
    
    
    public function setSortorder($sortorder);
    
}

?>