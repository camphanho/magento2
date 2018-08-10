<?php
namespace Camph\Faq\Model;

use Camph\Faq\Model\ResourceModel\FaqGroup as ResourceFaqGroup;
use Magento\Framework\Exception\CouldNotSaveException;
use Camph\Faq\Api\Data\FaqGroupSearchResultsInterfaceFactory;
use Magento\Framework\Api\DataObjectHelper;
use Camph\Faq\Api\Data\FaqGroupInterfaceFactory;
use Camph\Faq\Model\ResourceModel\FaqGroup\CollectionFactory as FaqGroupCollectionFactory;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Api\SortOrder;
use Camph\Faq\Api\FaqGroupRepositoryInterface;

class FaqGroupRepository implements FaqGroupRepositoryInterface
{
    
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;
    
    /**
     * @var \Camph\Faq\Model\ResourceModel\FaqGroup
     */
    private $resource;
    
    /**
     * @var \Camph\Faq\Api\Data\FaqGroupFactory
     */
    private $faqGroupFactory;
    
    /**
     * @var \Camph\Faq\Api\Data\FaqGroupInterfaceFactory
     */
    private $dataFaqGroupFactory;
    
    /**
     * @var \Camph\Faq\Model\ResourceModel\FaqGroup\CollectionFactory
     */
    private $faqGroupCollectionFactory;
    
    /**
     * @var \Magento\Framework\Reflection\DataObjectProcessor
     */
    private $dataObjectProcessor;
    
    /**
     * @var \Magento\Framework\Api\DataObjectHelper
     */
    private $dataObjectHelper;
    
    /**
     * @var \Camph\Faq\Api\Data\FaqGroupSearchResultsInterfaceFactory
     */
    private $searchResultsFactory;
    
    /**
     * @param ResourceFaqGroup $resource
     * @param FaqGroupFactory $faqGroupFactory
     * @param FaqGroupInterfaceFactory $dataFaqGroupFactory
     * @param FaqGroupCollectionFactory $faqGroupCollectionFactory
     * @param FaqGroupSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ResourceFaqGroup $resource,
        FaqGroupFactory $faqGroupFactory,
        FaqGroupInterfaceFactory $dataFaqGroupFactory,
        FaqGroupCollectionFactory $faqGroupCollectionFactory,
        FaqGroupSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager
        ) {
            $this->resource = $resource;
            $this->faqGroupFactory = $faqGroupFactory;
            $this->faqGroupCollectionFactory = $faqGroupCollectionFactory;
            $this->searchResultsFactory = $searchResultsFactory;
            $this->dataObjectHelper = $dataObjectHelper;
            $this->dataFaqGroupFactory = $dataFaqGroupFactory;
            $this->dataObjectProcessor = $dataObjectProcessor;
            $this->storeManager = $storeManager;
    }
    
    /**
     * {@inheritdoc}
     */
    public function save(
        \Camph\Faq\Api\Data\FaqGroupInterface $faqGroup
        ) {
            try {
                $faqGroup->getResource()->save($faqGroup);
            } catch (\Exception $exception) {
                throw new CouldNotSaveException(__(
                    'Could not save the faqGroup: %1',
                    $exception->getMessage()
                    ));
            }
            return $faqGroup;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getById($faqGroupId)
    {
        $faqGroup = $this->faqGroupFactory->create();
        $faqGroup->getResource()->load($faqGroup, $faqGroupId);
        if (!$faqGroup->getId()) {
            throw new NoSuchEntityException(__('FaqGroup with id "%1" does not exist.', $faqGroupId));
        }
        return $faqGroup;
    }
    
    /**
     * {@inheritdoc}
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $criteria
        ) {
            $collection = $this->faqGroupCollectionFactory->create();
            
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
    public function delete(
        \Camph\Faq\Api\Data\FaqGroupInterface $faqGroup
        ) {
            try {
                $faqGroup->getResource()->delete($faqGroup);
            } catch (\Exception $exception) {
                throw new CouldNotDeleteException(__(
                    'Could not delete the FaqGroup: %1',
                    $exception->getMessage()
                    ));
            }
            return true;
    }
    
    
    public function deleteById($faqGroupId)
    {
        return $this->delete($this->getById($faqGroupId));
    }
}
