<?php
/**
 * Created by PhpStorm.
 * User: camph
 * Date: 31/07/2018
 * Time: 17:33
 */
namespace Camph\Faq\Ui\Component\Listing\Column;

class FaqGroupActions extends \Magento\Ui\Component\Listing\Columns\Column
{

    const URL_PATH_EDIT = 'camph_faq/faqgroup/edit';
    private $urlBuilder;
    const URL_PATH_DELETE = 'camph_faq/faqgroup/delete';
    const URL_PATH_DETAILS = 'camph_faq/faqgroup/details';

    /**
     * @param \Magento\Framework\View\Element\UiComponent\ContextInterface $context
     * @param \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory
     * @param \Magento\Framework\UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\UiComponent\ContextInterface $context,
        \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory,
        \Magento\Framework\UrlInterface $urlBuilder,
        array $components = [],
        array $data = []
    ) {
        $this->urlBuilder = $urlBuilder;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as & $item) {
                if (isset($item['faqgroup_id'])) {
                    $item[$this->getData('name')] = [
                        'edit' => [
                            'href' => $this->urlBuilder->getUrl(
                                static::URL_PATH_EDIT,
                                [
                                    'faqgroup_id' => $item['faqgroup_id']
                                ]
                            ),
                            'label' => __('Edit')
                        ],
                        'delete' => [
                            'href' => $this->urlBuilder->getUrl(
                                static::URL_PATH_DELETE,
                                [
                                    'faqgroup_id' => $item['faqgroup_id']
                                ]
                            ),
                            'label' => __('Delete'),
                            'confirm' => [
                                'title' => __('Delete "${ $.$data.groupname }"'),
                                'message' => __('Are you sure you wan\'t to delete a "${ $.$data.groupname }" record?')
                            ]
                        ]
                    ];
                }
            }
        }

        return $dataSource;
    }
}
