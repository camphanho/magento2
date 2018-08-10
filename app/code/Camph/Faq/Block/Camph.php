<?php
/**
 * Created by PhpStorm.
 * User: camph
 * Date: 02/08/2018
 * Time: 11:20
 */
namespace Camph\Faq\Block;


class Camph extends \Magento\Framework\View\Element\Template
{
    private $faqCollectionFactory;

    private $faqGroupCollectionFactory;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Camph\Faq\Model\ResourceModel\Faq\CollectionFactory $faqCollectionFactory,
        \Camph\Faq\Model\ResourceModel\FaqGroup\CollectionFactory $faqGroupCollectionFactory
    ) {
        $this->faqCollectionFactory = $faqCollectionFactory;
        $this->faqGroupCollectionFactory = $faqGroupCollectionFactory;
        parent::__construct($context);
    }

    public function getFaqCollection($group)
    {

        $faqCollection = $this->faqCollectionFactory->create();
        $faqCollection->addFieldToFilter('group', ['finset' => $group]);
        return $faqCollection;
    }

    public function getFaqGroupCollection()
    {
        $faqGroupCollection = $this->faqGroupCollectionFactory->create();
        $faqGroupCollection->setOrder('sortorder', 'ASC');
        return $faqGroupCollection;
    }
}