<?php
namespace Camph\Faq\Model\ResourceModel\Faq;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    
    public $_idFieldName = 'faq_id';

    public function __construct(
        \Magento\Framework\Data\Collection\EntityFactoryInterface $entityFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\Data\Collection\Db\FetchStrategyInterface $fetchStrategy,
        \Magento\Framework\Event\ManagerInterface $eventManager,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\DB\Adapter\AdapterInterface $connection = null,
        \Magento\Framework\Model\ResourceModel\Db\AbstractDb $resource = null
    ) {
        $this->_init(
            'Camph\Faq\Model\Faq',
            'Camph\Faq\Model\ResourceModel\Faq'
        );
        parent::__construct(
            $entityFactory, $logger, $fetchStrategy, $eventManager, $connection,
            $resource
        );
        $this->storeManager = $storeManager;
    }





    /**
     * @param array|string $field
     * @param null $condition
     * @return AbstractCollection|mixed
     */
    public function addFieldToFilter($field, $condition = null)
    {
        if ($field === 'group') {
            return $this->addGroupFilter($field, $condition, true);
        }

        return parent::addFieldToFilter($field, $condition);
    }

    /**
     * @param $field
     * @param $condition
     * @param bool $withAdmin
     */
    public function addGroupFilter($field, $condition, $withAdmin = true){
        if ($withAdmin){
            foreach ($condition as $key => $values){
                if(is_array($values)){
                    foreach ($values as $value){
                        $condition = array('finset' => $value);
                        return parent::addFieldToFilter($field, $condition);
                    }
                }else{
                    $condition = array('finset' => $values);
                    return parent::addFieldToFilter($field, $condition);
                }

            }
        }else{
            return parent::addFieldToFilter($field, $condition);
        }

    }

}
