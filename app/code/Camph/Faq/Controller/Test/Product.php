<?php
/**
 * Created by PhpStorm.
 * User: camph
 * Date: 01/08/2018
 * Time: 13:32
 */
namespace Camph\Faq\Controller\Test;

use Magento\Catalog\Model\ProductFactory;
use Magento\Catalog\Helper\Image;
use Magento\Store\Model\StoreManager;
use Camph\Faq\Model\FaqGroupFactory;
class Product extends \Magento\Framework\App\Action\Action
{
    protected $productFactory;
    protected $imageHelper;
    protected $listProduct;
    protected $_storeManager;
    protected $faqgroup;
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Data\Form\FormKey $formKey,
        ProductFactory $productFactory,
        FaqGroupFactory $faqGroup,
        StoreManager $storeManager,
        Image $imageHelper
    )
    {
        $this->productFactory = $productFactory;
        $this->imageHelper = $imageHelper;
        $this->_storeManager = $storeManager;
        $this->faqgroup = $faqGroup;
        parent::__construct($context);
    }

    public function getCollection()
    {
        /*return $this->productFactory->create()
            ->getCollection()
            ->addAttributeToSelect('*')
            ->setPageSize(5)
            ->setCurPage(1);*/
        return $this->faqgroup->create()->getCollection()->addAttributeToSelect('*');
    }

    public function execute()
    {
        /*if ($id = $this->getRequest()->getParam('id')) {
            $product = $this->productFactory->create()->load($id);

            $productData = [
                'entity_id' => $product->getId(),
                'name' => $product->getName(),
                'price' => '$' . $product->getPrice(),
                'src' => $this->imageHelper->init($product, 'product_base_image')->getUrl(),
            ];

            echo json_encode($productData);
            return;
        }*/
        if ($id = $this->getRequest()->getParam('id')){
            $product = $this->faqgroup->create()->load($id);

            $productData = [
                'entity_id' => $product->getFaqGroupId(),
                'name' => $product->getGroupName(),
                'price' => $product->getSortorder()
            ];
        }
        echo json_encode($productData);
        return;
    }
}