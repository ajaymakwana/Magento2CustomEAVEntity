<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ajay\Brand\Ui\DataProvider\Brand\Product\Form\Modifier;

use Magento\Catalog\Model\Locator\LocatorInterface;
use Magento\Directory\Helper\Data;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Module\Manager as ModuleManager;
use Magento\Ui\Component\Container;
use Magento\Ui\Component\Form\Element\DataType\Text;
use Magento\Ui\Component\Form\Element\Input;
use Magento\Ui\Component\Form\Element\Hidden;
use Magento\Ui\Component\Form\Field;
use Magento\Ui\Component\Modal;
use Magento\Framework\Stdlib\ArrayManager;
use Magento\Framework\UrlInterface;
/**
 * Class AdvancedPricing
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class PersonaliseOptions extends \Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier
{
    const CODE_PERSONALISE_OPTION = 'merchandise_personalize_option';
    const CODE_SIDES_CONFIGURATION = 'sides_configuration';
    /**
     * @var LocatorInterface
     */
    protected $locator;

    /**
     * @var ModuleManager
     */
    protected $moduleManager;


    /**
     * @var SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;


    /**
     * @var Data
     */
    protected $directoryHelper;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var ArrayManager
     */
    protected $arrayManager;

    /**
     * @var string
     */
    protected $scopeName;

    /**
     * @var array
     */
    protected $meta = [];

    /**
     * @var UrlInterface
     */
    protected $urlBuilder;
    /**
     * Store manager
     *
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;
    /**
     * @param LocatorInterface $locator
     * @param StoreManagerInterface $storeManager
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param ModuleManager $moduleManager
     * @param Data $directoryHelper
     * @param ArrayManager $arrayManager
     * @param string $scopeName
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        LocatorInterface $locator,
        StoreManagerInterface $storeManager,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        ModuleManager $moduleManager,
        Data $directoryHelper,
        ArrayManager $arrayManager,
        UrlInterface $urlBuilder,
        $scopeName = ''
    ) {
        $this->locator = $locator;
        $this->storeManager = $storeManager;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->moduleManager = $moduleManager;
        $this->directoryHelper = $directoryHelper;
        $this->arrayManager = $arrayManager;
        $this->urlBuilder = $urlBuilder;
        $this->scopeName = $scopeName;
    }

    /**
     * {@inheritdoc}
     */
    public function modifyMeta(array $meta)
    {
        $this->meta = $meta;


        if (isset($this->meta['sides-configuration'])) {
            //$this->customizeSides();
            $this->addAdvancedDesignLink();
            //$this->customizeAdvancedSides();
            //$this->customizeSideLayouts();
        }

        return $this->meta;
    }

    /**
     * {@inheritdoc}
     */
    public function modifyData(array $data)
    {
        return $data;
    }


    /**
     * Customize side field
     *
     * @return $this
     */
    protected function customizeSides()
    {
        $sideConfigurationPath = $this->arrayManager->findPath(
            self::CODE_SIDES_CONFIGURATION,
            $this->meta,
            null,
            'children'
        );

        if ($sideConfigurationPath) {

            $fields = $this->arrayManager->merge('children/record/children',$this->getSideConfigurationStructure($sideConfigurationPath),$this->_createStoreSpecificField());



            /*$this->meta = $this->arrayManager->merge(
                $sideConfigurationPath,
                $this->meta,
                $this->getSideConfigurationStructure($sideConfigurationPath)
            );*/
            $this->meta = $this->arrayManager->merge(
                $sideConfigurationPath,
                $this->meta,
                $fields
            );
            $this->meta = $this->arrayManager->set(
                $this->arrayManager->slicePath($sideConfigurationPath, 0, -3)
                . '/' . self::CODE_SIDES_CONFIGURATION,
                $this->meta,
                $this->arrayManager->get($sideConfigurationPath, $this->meta)
            );
            $this->meta = $this->arrayManager->remove(
                $this->arrayManager->slicePath($sideConfigurationPath, 0, -2),
                $this->meta
            );
        }
        
        return $this;
    }


    /**
     * Add link to open Advanced Pricing Panel
     *
     * @return $this
     */
    protected function addAdvancedDesignLink()
    {
        $sidePath = $this->arrayManager->findPath(
            self::CODE_PERSONALISE_OPTION,
            $this->meta,
            null,
            'children'
        );

        if ($sidePath) {
            $this->meta = $this->arrayManager->merge(
                $sidePath . '/arguments/data/config',
                $this->meta,
                ['additionalClasses' => 'admin__field-small']
            );

            $advancedPricingButton['arguments']['data']['config'] = [
                'displayAsLink' => true,
                'formElement' => Container::NAME,
                'componentType' => Container::NAME,
                'component' => 'Magento_Ui/js/form/components/button',
                'template' => 'ui/form/components/button/container',
                'actions' => [
                    [
                        'targetName' => $this->scopeName . '.advanced_pages_modal',
                        'actionName' => 'toggleModal',
                    ]
                ],
                'title' => __('Select Design'),
                'additionalForGroup' => true,
                'provider' => false,
                'source' => 'product_details',
                'sortOrder' =>
                    $this->arrayManager->get($sidePath . '/arguments/data/config/sortOrder', $this->meta) + 1,
            ];

            $this->meta = $this->arrayManager->set(
                $this->arrayManager->slicePath($sidePath, 0, -1) . '/advanced_pages_button',
                $this->meta,
                $advancedPricingButton
            );
        }

        return $this;
    }

    /**
     * Get side dynamic rows structure
     *
     * @param string $sideConfigurationPath
     * @return array
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function getSideConfigurationStructure($sideConfigurationPath)
    {

        return [
            'arguments' => [
                'data' => [
                    'config' => [
                        'componentType' => 'dynamicRows',
                        'label' => __('Side Configuration'),
                        'renderDefaultRecord' => false,
                        'recordTemplate' => 'record',
                        'dataScope' => '',
                        'addButton' => false,
                        'dndConfig' => [
                            'enabled' => false,
                        ],
                        'additionalClasses' => 'admin__field-wide',
                        'disabled' => false,
                        'sortOrder' =>
                            $this->arrayManager->get($sideConfigurationPath . '/arguments/data/config/sortOrder', $this->meta),
                    ],
                ],
            ],
            'children' => [
                'record' => [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'componentType' => Container::NAME,
                                'isTemplate' => true,
                                'is_collection' => true,
                                'component' => 'Magento_Ui/js/dynamic-rows/record',
                                'dataScope' => '',
                            ],
                        ],
                    ],
                    'children' => [
                        'label' => [
                            'arguments' => [
                                'data' => [
                                    'config' => [
                                        'formElement' => Input::NAME,
                                        'componentType' => Field::NAME,
                                        'dataType' => Text::NAME,
                                        'label' => __('Side Label'),
                                        'dataScope' => 'label',
                                        'validation' => [
                                            'required-entry' => true
                                        ],
                                        'scopeLabel' => null,
                                        'additionalClasses' => 'admin__field-small',
                                        'sortOrder' => 0,
                                    ],
                                ],
                            ],
                        ],
                        'value_id' => [
                            'arguments' => [
                                'data' => [
                                    'config' => [
                                        'formElement' => Hidden::NAME,
                                        'componentType' => Field::NAME,
                                        'dataType' => Text::NAME,
                                        'dataScope' => 'value_id',
                                    ],
                                ],
                            ],
                        ],
                        'color_image' => [
                            'arguments' => [
                                'data' => [
                                    'config' => [
                                        'componentType' => Container::NAME,
                                        'formElement' => Container::NAME,
                                        'component' => 'Magento_Ui/js/form/components/group',
                                        'label' => __('Color Image'),
                                        'dataScope' => '',
                                        //'sortOrder' => 100,
                                    ],
                                ],
                            ],
                            'children' => [
                                'image' => [
                                    'arguments' => [
                                        'data' => [
                                            'config' => [
                                                'formElement' => 'fileUploader',
                                                'componentType' => 'fileUploader',
                                                'component' => 'Ajay_Merchandise/js/components/file-uploader',
                                                'elementTmpl' => 'Ajay_Merchandise/components/file-uploader',
                                                'fileInputName' => 'color',
                                                'uploaderConfig' => [
                                                    'url' => $this->urlBuilder->addSessionParam()->getUrl(
                                                        'merchandise/side_image/upload',
                                                        ['type' => 'color', '_secure' => true]
                                                    ),
                                                ],
                                                'dataScope' => 'color.file',
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        'side_image' => [
                            'arguments' => [
                                'data' => [
                                    'config' => [
                                        'componentType' => Container::NAME,
                                        'formElement' => Container::NAME,
                                        'component' => 'Magento_Ui/js/form/components/group',
                                        'label' => __('Side Image'),
                                        'dataScope' => '',
                                        //'sortOrder' => 100,
                                    ],
                                ],
                            ],
                            'children' => [
                                'image' => [
                                    'arguments' => [
                                        'data' => [
                                            'config' => [
                                                'formElement' => 'fileUploader',
                                                'componentType' => 'fileUploader',
                                                'component' => 'Ajay_Merchandise/js/components/file-uploader',
                                                'elementTmpl' => 'Ajay_Merchandise/components/file-uploader',
                                                'fileInputName' => 'image',
                                                'uploaderConfig' => [
                                                    'url' => $this->urlBuilder->addSessionParam()->getUrl(
                                                        'merchandise/side_image/upload',
                                                        ['type' => 'image', '_secure' => true]
                                                    ),
                                                ],
                                                'dataScope' => 'image.file',
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        'mask_image' => [
                            'arguments' => [
                                'data' => [
                                    'config' => [
                                        'componentType' => Container::NAME,
                                        'formElement' => Container::NAME,
                                        'component' => 'Magento_Ui/js/form/components/group',
                                        'label' => __('Mask Image'),
                                        'dataScope' => '',
                                        //'sortOrder' => 100,
                                    ],
                                ],
                            ],
                            'children' => [
                                'image' => [
                                    'arguments' => [
                                        'data' => [
                                            'config' => [
                                                'formElement' => 'fileUploader',
                                                'componentType' => 'fileUploader',
                                                'component' => 'Ajay_Merchandise/js/components/file-uploader',
                                                'elementTmpl' => 'Ajay_Merchandise/components/file-uploader',
                                                'fileInputName' => 'mask',
                                                'uploaderConfig' => [
                                                    'url' => $this->urlBuilder->addSessionParam()->getUrl(
                                                        'merchandise/side_image/upload',
                                                        ['type' => 'mask', '_secure' => true]
                                                    ),
                                                ],
                                                'dataScope' => 'mask.file',
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        'overlay_image' => [
                            'arguments' => [
                                'data' => [
                                    'config' => [
                                        'componentType' => Container::NAME,
                                        'formElement' => Container::NAME,
                                        'component' => 'Magento_Ui/js/form/components/group',
                                        'label' => __('Overlay Image'),
                                        'dataScope' => '',
                                        //'sortOrder' => 100,
                                    ],
                                ],
                            ],
                            'children' => [
                                'image' => [
                                    'arguments' => [
                                        'data' => [
                                            'config' => [
                                                'formElement' => 'fileUploader',
                                                'componentType' => 'fileUploader',
                                                'component' => 'Ajay_Merchandise/js/components/file-uploader',
                                                'elementTmpl' => 'Ajay_Merchandise/components/file-uploader',
                                                'fileInputName' => 'overlay',
                                                'uploaderConfig' => [
                                                    'url' => $this->urlBuilder->addSessionParam()->getUrl(
                                                        'merchandise/side_image/upload',
                                                        ['type' => 'overlay', '_secure' => true]
                                                    ),
                                                ],
                                                'dataScope' => 'overlay.file',
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        'category' => [
                            'arguments' => [
                                'data' => [
                                    'config' => [
                                        'displayAsLink' => true,
                                        'formElement' => Container::NAME,
                                        'componentType' => Container::NAME,
                                        'component' => 'Magento_Ui/js/form/components/button',
                                        'template' => 'ui/form/components/button/container',
                                        'actions' => [
                                            [
                                                'targetName' => $this->scopeName . '.advanced_sides_layout_modal',
                                                'actionName' => 'toggleModal',
                                            ]
                                        ],
                                        'title' => __('Advanced Sides'),
                                        'additionalForGroup' => true,
                                        'provider' => false,
                                        'source' => 'product_details',
                                        'sortOrder' => 100,
                                    ],
                                ],
                            ],
                        ],
                        /*'actionDelete' => [
                            'arguments' => [
                                'data' => [
                                    'config' => [
                                        'componentType' => 'actionDelete',
                                        'dataType' => Text::NAME,
                                        'label' => '',
                                    ],
                                ],
                            ],
                        ],*/
                    ],
                ],
            ],
        ];
    }
    /**
     * Customize Advanced Sides Panel
     *
     * @return $this
     */
    protected function customizeSideLayouts()
    {

        $this->meta['advanced_sides_layout_modal']['arguments']['data']['config'] = [
            'isTemplate' => false,
            'componentType' => Modal::NAME,
            'dataScope' => '',
            'provider' => 'product_form.product_form_data_source',
            'onCancel' => 'actionDone',
            'options' => [
                'title' => __('Advanced Sides'),
                'buttons' => [
                    [
                        'text' => __('Done'),
                        'class' => 'action-primary',
                        'actions' => [
                            [
                                'targetName' => '${ $.name }',
                                'actionName' => 'actionDone'
                            ]
                        ]
                    ],
                ],
            ],
        ];


        return $this;
    }

    /**
     * Customize Advanced Sides Panel
     *
     * @return $this
     */
    protected function customizeAdvancedSides()
    {
        $this->meta['sides-configuration']['arguments']['data']['config']['opened'] = true;
        $this->meta['sides-configuration']['arguments']['data']['config']['collapsible'] = false;
        $this->meta['sides-configuration']['arguments']['data']['config']['label'] = '';

        $this->meta['advanced_design_modal']['arguments']['data']['config'] = [
            'isTemplate' => false,
            'componentType' => Modal::NAME,
            'dataScope' => '',
            'provider' => 'product_form.product_form_data_source',
            'onCancel' => 'actionDone',
            'options' => [
                'title' => __('Advanced Sides'),
                'buttons' => [
                    [
                        'text' => __('Done'),
                        'class' => 'action-primary',
                        'actions' => [
                            [
                                'targetName' => '${ $.name }',
                                'actionName' => 'actionDone'
                            ]
                        ]
                    ],
                ],
            ],
        ];

        $this->meta = $this->arrayManager->merge(
            $this->arrayManager->findPath(
                static::CONTAINER_PREFIX . self::CODE_NO_OF_SIDES,
                $this->meta,
                null,
                'children'
            ),
            $this->meta,
            [
                'arguments' => [
                    'data' => [
                        'config' => [
                            'component' => 'Magento_Ui/js/form/components/group',
                        ],
                    ],
                ],
            ]
        );

        $this->meta['advanced_design_modal']['children']['design-configuration'] = $this->meta['sides-configuration'];
        unset($this->meta['sides-configuration']);
        /*$this->meta['advanced_pages_modal']['children']['advanced-pages'] = $this->meta['advanced-pricing'];
        unset($this->meta['advanced-pricing']);*/

        return $this;
    }

    /**
     * Retrieve store
     *
     * @return \Magento\Store\Model\Store
     */
    protected function getStore()
    {
        return $this->locator->getStore();
    }

    protected function _createStoreSpecificField()
    {
        $storeFields = [];
        $sortOrder = 0;
        foreach ($this->storeManager->getWebsites() as $website) {
            foreach ($website->getGroups() as $group) {
                $stores = $group->getStores();
                if (count($stores) == 0) {
                    continue;
                }
                /*$storeMeta['stores'] = [
                    'arguments' => [
                        'data' => [
                            'config' => [
                                'componentType' => Container::NAME,
                                'formElement' => Container::NAME,
                                'component' => 'Magento_Ui/js/form/components/group',
                                'label' => __('Stores'),
                                'dataScope' => 'stores',

                                //'sortOrder' => 100,
                            ],
                        ],
                    ],
                    'children' => [ ],
                ];*/
                foreach ($stores as $store) {
                    $storeFields[$store->getCode()] = [
                        'arguments' => [
                            'data' => [
                                'config' => [
                                    'formElement' => Input::NAME,
                                    'componentType' => Field::NAME,
                                    'dataType' => Text::NAME,
                                    'label' => $store->getName(),
                                    'dataScope' => $store->getCode(),
                                    'validation' => [
                                        // 'required-entry' => true
                                    ],
                                    'additionalClasses' => 'admin__field-small'
                                    //'sortOrder' => $sortOrder,
                                ],
                            ],
                        ],
                    ];
                    $sortOrder++;
                }
            }
        }
        //$storeMeta['stores']['children'] = $storeFields;
        //return $storeMeta;
        return $storeFields;
    }
}
