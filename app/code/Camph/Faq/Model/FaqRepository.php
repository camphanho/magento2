<?php
namespace Camph\Faq\Model;

use Camph\Faq\Api\Data\FaqSearchResultsInterfaceFactory;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Api\DataObjectHelper;
use Camph\Faq\Api\Data\FaqInterfaceFactory;
use Camph\Faq\Api\FaqRepositoryInterface;
use Camph\Faq\Model\ResourceModel\Faq\CollectionFactory as FaqCollectionFactory;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;
use Camph\Faq\Model\ResourceModel\Faq as ResourceFaq;
use Magento\Framework\Api\SortOrder;
use Magento\Store\Model\StoreManagerInterface;

class FaqRepository implements FaqRepositoryInterface
{
    
    private $storeManager;
    
    
    private $resource;
    
    
    private $faqFactory;
    
   
    private $faqCollectionFactory;
    
    /**
     * @var \Magento\Framework\Reflection\DataObjectProcessor
     */
    private $dataObjectProcessor;
    
    /**
     * @var \Magento\Framework\Api\DataObjectHelper
     */
    private $dataObjectHelper;
    
   
    private $dataFaqFactory;
    
    
    private $searchResultsFactory;
    
    
    public function __construct(
        ResourceFaq $resource,
        FaqFactory $faqFactory,
        FaqInterfaceFactory $dataFaqFactory,
        FaqCollectionFactory $faqCollectionFactory,
        FaqSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager
        ) {
            $this->resource = $resource;
            $this->faqFactory = $faqFactory;
            $this->faqCollectionFactory = $faqCollectionFactory;
            $this->searchResultsFactory = $searchResultsFactory;
            $this->dataObjectHelper = $dataObjectHelper;
            $this->dataFaqFactory = $dataFaqFactory;
            $this->dataObjectProcessor = $dataObjectProcessor;
            $this->storeManager = $storeManager;
    }
    
    /**
     * {@inheritdoc}
     */
    public function save(\Camph\Faq\Api\Data\FaqInterface $faq)
    {
        try {
            $faq->getResource()->save($faq);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the faq: %1',
                $exception->getMessage()
                ));
        }
        return $faq;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getById($faqId)
    {
        $faq = $this->faqFactory->create();
        $faq->getResource()->load($faq, $faqId);
        if (!$faq->getId()) {
            throw new NoSuchEntityException(__('Faq with id "%1" does not exist.', $faqId));
        }
        return $faq;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
        ) {
            $collection = $this->faqCollectionFactory->create();
            
            $sortOrders = $criteria->getSortOrders();
            if ($sortOrders) {
                /** @var SortOrder $sortOrder */
                foreach ($sortOrders as $sortOrder) {
                    $collection->addOrder(
                        $sortOrder->getField(),
                        ($sortOrder->getDirection() == SortOrder::SORT_ASC) ? 'ASC' : 'DESC'
                        );
                }
            }
            $collection->setCurPage($criteria->getCurrentPage());
            $collection->setPageSize($criteria->getPageSize());
            
            $searchResults = $this->searchResultsFactory->create();
            $searchResults->setSearchCriteria($criteria);
            $searchResults->setTotalCount($collection->getSize());
            $searchResults->setItems($collection->getItems());
            return $searchResults;
    }
    
    /**
     * {@inheritdoc}
     */
    public function delete(\Camph\Faq\Api\Data\FaqInterface $faq)
    {
        try {
            $faq->getResource()->delete($faq);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the Faq: %1',
                $exception->getMessage()
                ));
        }
        return true;
    }
    
    /**
     * {@inheritdoc}
     */
    public function deleteById($faqId)
    {
        return $this->delete($this->getById($faqId));
    }
}
