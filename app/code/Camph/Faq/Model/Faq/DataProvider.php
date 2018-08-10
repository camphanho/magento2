<?php
namespace Camph\Faq\Model\Faq;

use Magento\Framework\App\Request\DataPersistorInterface;
use Camph\Faq\Model\ResourceModel\Faq\CollectionFactory;

class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    
    private $loadedData;
    
    /**
     * @var \Magento\Framework\App\Request\DataPersistorInterface
     */
    private $dataPersistor;
    
    /**
     * @var \Camph\Faq\Model\ResourceModel\Faq\CollectionFactory
     */
    
    public $collection;
    
    /**
     * Constructor
     *
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $blockCollectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        DataPersistorInterface $dataPersistor,
        array $meta = [],
        array $data = []
        ) {
            $this->collection = $collectionFactory->create();
            $this->dataPersistor = $dataPersistor;
            parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }
    
    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();
        foreach ($items as $model) {
            $this->loadedData[$model->getId()] = $model->getData();
        }
        $data = $this->dataPersistor->get('camph_faq_faq');
        
        if (!empty($data)) {
            $model = $this->collection->getNewEmptyItem();
            $model->setData($data);
            $this->loadedData[$model->getId()] = $model->getData();
            $this->dataPersistor->clear('camph_faq_faq');
        }
        
        return $this->loadedData;
    }
}
