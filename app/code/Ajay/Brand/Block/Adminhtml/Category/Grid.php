<?php

namespace Ajay\Brand\Block\Adminhtml\Category;

/**
 * Brand grid.
 * @category Ajay
 * @package  Ajay_Brand
 * @module   Brand
 * @author   Ajay Developer
 */
class Grid extends \Magento\Backend\Block\Widget\Grid\Extended
{
    /**
     * Brand collection factory.
     *
     * @var \Ajay\Brand\Model\ResourceModel\Category\CollectionFactory
     */
    protected $_categoryCollectionFactory;
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * Available status.
     *
     * @var \Ajay\Brand\Model\Status
     */
   // private $_status;

    /**
     * [__construct description].
     *
     * @param \Magento\Backend\Block\Brand\Context                                   $context
     * @param \Magento\Backend\Helper\Data                                              $backendHelper
     * @param \Ajay\Brand\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory
     * @param \Ajay\Brand\Helper\Data                                  $bannerbrandHelper
     * @param \Ajay\Brand\Model\Status                                 $status
     * @param array                                                                     $data
     */
    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Backend\Helper\Data $backendHelper,
        \Ajay\Brand\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        //\Ajay\Brand\Helper\Data $bannerbrandHelper,
       // \Ajay\Brand\Model\Status $status,
        array $data = []
    ) {
        $this->_storeManager = $storeManager;
        parent::__construct($context, $backendHelper, $data);
        $this->_categoryCollectionFactory = $categoryCollectionFactory;
    }

    /**
     * Internal constructor, that is called from real constructor
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();

        $this->setId('categoryGrid');
        $this->setDefaultSort('id');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * Prepare collection.
     *
     * @return [type] [description]
     */
    protected function _prepareCollection()
    {
        $adminStoreId = $this->_storeManager->getStore(\Magento\Store\Model\Store::ADMIN_CODE)->getId();

        $collection = $this->_categoryCollectionFactory->create();
        $collection->addAttributeToSelect('*');
        $collection->joinAttribute('title','ajay_brand_category/title','entity_id',null,'left',$adminStoreId);

        $this->setCollection($collection);

        return parent::_prepareCollection();
    }

    /**
     * @return $this
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'entity_id',
            [
                'header' => __('ID'),
                'type'   => 'number',
                'index'  => 'entity_id',
                'header_css_class' => 'col-id',
                'column_css_class' => 'col-id',
            ]
        );

        $this->addColumn(
            'title',
            [
                'header' => __('Title'),
                'index'  => 'title',
                'class'  => 'xxx',
                'width'  => '50px',
            ]
        );

        $this->addColumn(
            'status',
            [
                'header'  => __('Status'),
                'index'   => 'status',
                'type'    => 'options',
                'options' => [
                    1  => __('Enabled'),
                    0 => __('Disabled'),
                ],
            ]
        );

        $this->addColumn(
            'edit',
            [
                'header'  => __('Edit'),
                'type'    => 'action',
                'getter'  => 'getId',
                'actions' => [
                    [
                        'caption' => __('Edit'),
                        'url' => [
                            'base' => '*/*/edit',
                        ],
                        'field' => 'id',
                    ],
                ],
                'filter'   => false,
                'sortable' => false,
                'index'    => 'stores',
                'header_css_class' => 'col-action',
                'column_css_class' => 'col-action',
            ]
        );
        $this->addExportType('*/*/exportCsv', __('CSV'));
        $this->addExportType('*/*/exportXml', __('XML'));
        $this->addExportType('*/*/exportExcel', __('Excel'));
        return parent::_prepareColumns();
    }

    /**
     * @return $this
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('category');

        $this->getMassactionBlock()->addItem(
            'delete',
            [
                'label' => __('Delete'),
                'url' => $this->getUrl('brand/*/massDelete'),
                'confirm' => __('Are you sure?'),
            ]
        );

        $status = array();
        $status = [
            ['value' => '', 'label' => ''],
            ['value' => 1, 'label' => 'Enabled'],
            ['value' => 0, 'label' => 'Disabled'],
        ];
        //array_unshift($status, ['label' => '', 'value' => '']);
        $this->getMassactionBlock()->addItem(
            'status',
            [
                'label' => __('Change status'),
                'url' => $this->getUrl('brand/*/massStatus', ['_current' => true]),
                'additional' => [
                    'visibility' => [
                        'name'   => 'status',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => __('Status'),
                        'values' => $status,
                    ],
                ],
            ]
        );

        return $this;
    }

    /**
     * @return string
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/grid', ['_current' => true]);
    }

    /**
     * get row url
     * @param  object $row
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl(
            '*/*/edit',
            ['id' => $row->getId()]
        );
    }
}
