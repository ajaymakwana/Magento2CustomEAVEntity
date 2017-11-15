<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ajay\Brand\Ui\DataProvider\Brand\Form\Modifier;

use Ajay\Brand\Api\Data\BrandAttributeInterface;
use Ajay\Brand\Api\BrandAttributeGroupRepositoryInterface;
use Ajay\Brand\Api\BrandAttributeRepositoryInterface;
use Ajay\Brand\Model\Locator\LocatorInterface;
use Ajay\Brand\Model\Brand;
use Ajay\Brand\ResourceModel\Eav\Attribute as EavAttribute;
use Ajay\Brand\Model\ResourceModel\Eav\AttributeFactory as EavAttributeFactory;
use Magento\Eav\Api\Data\AttributeGroupInterface;
use Magento\Eav\Api\Data\AttributeInterface;
use Magento\Eav\Model\Config;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Group\CollectionFactory as GroupCollectionFactory;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SortOrderBuilder;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Filter\Translit;
use Magento\Framework\Stdlib\ArrayManager;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Ui\Component\Form\Field;
use Magento\Ui\Component\Form\Fieldset;
use Magento\Catalog\Ui\DataProvider\CatalogEavValidationRules;
use Magento\Ui\DataProvider\Mapper\FormElement as FormElementMapper;
use Magento\Ui\DataProvider\Mapper\MetaProperties as MetaPropertiesMapper;
use Magento\Ui\Component\Form\Element\Wysiwyg as WysiwygElement;
use Magento\Catalog\Model\Attribute\ScopeOverriddenValue;
use Magento\Framework\Locale\CurrencyInterface;
use Magento\Framework\UrlInterface;
use Magento\Framework\App\ObjectManager;
/**
 * Class Eav
 *
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 * @SuppressWarnings(PHPMD.TooManyFields)
 */
class Eav extends AbstractModifier
{
    /**
     * Maximum file size allowed for file_uploader UI component
     */
    const MAX_FILE_SIZE = 2097152;

    const SORT_ORDER_MULTIPLIER = 10;

    /**
     * @var LocatorInterface
     */
    protected $locator;

    /**
     * @var Config
     */
    protected $eavConfig;

    /**
     * @var CatalogEavValidationRules
     */
    protected $catalogEavValidationRules;

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var GroupCollectionFactory
     */
    protected $groupCollectionFactory;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var FormElementMapper
     */
    protected $formElementMapper;

    /**
     * @var MetaPropertiesMapper
     */
    protected $metaPropertiesMapper;

    /**
     * @var BrandAttributeGroupRepositoryInterface
     */
    protected $attributeGroupRepository;

    /**
     * @var SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    /**
     * @var BrandAttributeRepositoryInterface
     */
    protected $attributeRepository;

    /**
     * @var SortOrderBuilder
     */
    protected $sortOrderBuilder;

    /**
     * @var EavAttributeFactory
     */
    protected $eavAttributeFactory;

    /**
     * @var Translit
     */
    protected $translitFilter;

    /**
     * @var ArrayManager
     */
    protected $arrayManager;

    /**
     * @var ScopeOverriddenValue
     */
    private $scopeOverriddenValue;

    /**
     * @var array
     */
    private $attributesToDisable;

    /**
     * @var array
     */
    protected $attributesToEliminate;

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var EavAttribute[]
     */
    private $attributes = [];

    /**
     * @var AttributeGroupInterface[]
     */
    private $attributeGroups = [];

    /**
     * @var array
     */
    private $canDisplayUseDefault = [];

    /**
     * @var array
     */
    private $bannedInputTypes = [];

    /**
     * @var array
     */
    private $prevSetAttributes;

    /**
     * @var CurrencyInterface
     */
    private $localeCurrency;
    /**
     * @var UrlInterface
     */
    protected $urlBuilder;
    /**
     * File types allowed for file_uploader UI component
     *
     * @var array
     */
    private $fileUploaderTypes = [
        'image',
        'file',
    ];

    /**
     * @var FileProcessorFactory
     */
    private $fileProcessorFactory;
    /**
     * @param LocatorInterface $locator
     * @param CatalogEavValidationRules $catalogEavValidationRules
     * @param Config $eavConfig
     * @param RequestInterface $request
     * @param GroupCollectionFactory $groupCollectionFactory
     * @param StoreManagerInterface $storeManager
     * @param FormElementMapper $formElementMapper
     * @param MetaPropertiesMapper $metaPropertiesMapper
     * @param BrandAttributeGroupRepositoryInterface $attributeGroupRepository
     * @param BrandAttributeRepositoryInterface $attributeRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     * @param SortOrderBuilder $sortOrderBuilder
     * @param EavAttributeFactory $eavAttributeFactory
     * @param Translit $translitFilter
     * @param ArrayManager $arrayManager
     * @param ScopeOverriddenValue $scopeOverriddenValue
     * @param DataPersistorInterface $dataPersistor
     * @param array $attributesToDisable
     * @param array $attributesToEliminate
     * @param UrlInterface $urlBuilder
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        LocatorInterface $locator,
        CatalogEavValidationRules $catalogEavValidationRules,
        Config $eavConfig,
        RequestInterface $request,
        GroupCollectionFactory $groupCollectionFactory,
        StoreManagerInterface $storeManager,
        FormElementMapper $formElementMapper,
        MetaPropertiesMapper $metaPropertiesMapper,
        BrandAttributeGroupRepositoryInterface $attributeGroupRepository,
        BrandAttributeRepositoryInterface $attributeRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        SortOrderBuilder $sortOrderBuilder,
        EavAttributeFactory $eavAttributeFactory,
        Translit $translitFilter,
        ArrayManager $arrayManager,
        ScopeOverriddenValue $scopeOverriddenValue,
        DataPersistorInterface $dataPersistor,
        UrlInterface $urlBuilder,
        $attributesToDisable = [],
        $attributesToEliminate = []
    ) {
        $this->locator = $locator;
        $this->catalogEavValidationRules = $catalogEavValidationRules;
        $this->eavConfig = $eavConfig;
        $this->request = $request;
        $this->groupCollectionFactory = $groupCollectionFactory;
        $this->storeManager = $storeManager;
        $this->formElementMapper = $formElementMapper;
        $this->metaPropertiesMapper = $metaPropertiesMapper;
        $this->attributeGroupRepository = $attributeGroupRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->attributeRepository = $attributeRepository;
        $this->sortOrderBuilder = $sortOrderBuilder;
        $this->eavAttributeFactory = $eavAttributeFactory;
        $this->translitFilter = $translitFilter;
        $this->arrayManager = $arrayManager;
        $this->scopeOverriddenValue = $scopeOverriddenValue;
        $this->dataPersistor = $dataPersistor;
        $this->attributesToDisable = $attributesToDisable;
        $this->attributesToEliminate = $attributesToEliminate;
        $this->urlBuilder = $urlBuilder;
    }

    /**
     * {@inheritdoc}
     */
    public function modifyMeta(array $meta)
    {
        $sortOrder = 0;

        foreach ($this->getGroups() as $groupCode => $group) {
            $attributes = !empty($this->getAttributes()[$groupCode]) ? $this->getAttributes()[$groupCode] : [];

            if ($attributes) {
                $meta[$groupCode]['children'] = $this->getAttributesMeta($attributes, $groupCode);
                $meta[$groupCode]['arguments']['data']['config']['componentType'] = Fieldset::NAME;
                $meta[$groupCode]['arguments']['data']['config']['label'] = __('%1', $group->getAttributeGroupName());
                $meta[$groupCode]['arguments']['data']['config']['collapsible'] = true;
                $meta[$groupCode]['arguments']['data']['config']['dataScope'] = self::DATA_SCOPE_BRAND;
                $meta[$groupCode]['arguments']['data']['config']['sortOrder'] = $sortOrder * self::SORT_ORDER_MULTIPLIER;
            }

            $sortOrder++;
        }

        return $meta;
    }

    /**
     * Get attributes meta
     *
     * @param BrandAttributeInterface[] $attributes
     * @param string $groupCode
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    private function getAttributesMeta(array $attributes, $groupCode)
    {
        $meta = [];

        foreach ($attributes as $sortOrder => $attribute) {
            if (in_array($attribute->getFrontendInput(), $this->bannedInputTypes)) {
                //continue;
            }

            if (in_array($attribute->getAttributeCode(), $this->attributesToEliminate)) {
                //continue;
            }

            if (!($attributeContainer = $this->setupAttributeContainerMeta($attribute))) {
                continue;
            }

            $attributeContainer = $this->addContainerChildren($attributeContainer, $attribute, $groupCode, $sortOrder);

            $meta[static::CONTAINER_PREFIX . $attribute->getAttributeCode()] = $attributeContainer;
        }

        return $meta;
    }

    /**
     * Add container children
     *
     * @param array $attributeContainer
     * @param BrandAttributeInterface $attribute
     * @param string $groupCode
     * @param int $sortOrder
     * @return array
     * @api
     */
    public function addContainerChildren(
        array $attributeContainer,
        BrandAttributeInterface $attribute,
        $groupCode,
        $sortOrder
    ) {
        foreach ($this->getContainerChildren($attribute, $groupCode, $sortOrder) as $childCode => $child) {
            $attributeContainer['children'][$childCode] = $child;
        }

        $attributeContainer = $this->arrayManager->merge(
            ltrim(static::META_CONFIG_PATH, ArrayManager::DEFAULT_PATH_DELIMITER),
            $attributeContainer,
            [
                'sortOrder' => $sortOrder * self::SORT_ORDER_MULTIPLIER,
                // TODO: Eliminate this in scope of MAGETWO-51364
                'scopeLabel' => $this->getScopeLabel($attribute),
            ]
        );

        return $attributeContainer;
    }

    /**
     * Retrieve container child fields
     *
     * @param BrandAttributeInterface $attribute
     * @param string $groupCode
     * @param int $sortOrder
     * @return array
     * @api
     */
    public function getContainerChildren(BrandAttributeInterface $attribute, $groupCode, $sortOrder)
    {
        if (!($child = $this->setupAttributeMeta($attribute, $groupCode, $sortOrder))) {
            return [];
        }

        return [$attribute->getAttributeCode() => $child];
    }

    /**
     * {@inheritdoc}
     */
    public function modifyData(array $data)
    {
        if (!$this->locator->getBrand()->getId() && $this->dataPersistor->get('ajay_brand_eav_attribute')) {
            return $this->resolvePersistentData($data);
        }

        $brandId = $this->locator->getBrand()->getId();


        /** @var BrandAttributeInterface[] $attributes */
        $attributes = $this->getAttributes();
        /** @var string $groupCode */
        foreach (array_keys($this->getGroups()) as $groupCode) {
            /** @var BrandAttributeInterface[] $attributes */
            $attributes = !empty($this->getAttributes()[$groupCode]) ? $this->getAttributes()[$groupCode] : [];

            foreach ($attributes as $attribute) {

                if (null !== ($attributeValue = $this->setupAttributeData($attribute))) {
                    $data[$brandId][self::DATA_SOURCE_DEFAULT][$attribute->getAttributeCode()] = $attributeValue;
                }

            }
        }
        return $data;
    }    
    /**
     * Resolve data persistence
     *
     * @param array $data
     * @return array
     */
    private function resolvePersistentData(array $data)
    {

        $persistentData = (array)$this->dataPersistor->get('ajay_brand_eav_attribute');
        $this->dataPersistor->clear('ajay_brand_eav_attribute');
        $brandId = $this->locator->getBrand()->getId();

        if (empty($data[$brandId][self::DATA_SOURCE_DEFAULT])) {
            $data[$brandId][self::DATA_SOURCE_DEFAULT] = [];
        }

        $data[$brandId] = array_replace_recursive(
            $data[$brandId][self::DATA_SOURCE_DEFAULT],
            $persistentData
        );
        return $data;
    }

    /**
     * Get product type
     *
     * @return null|string
     */
    private function getProductType()
    {
        return (string)$this->request->getParam('type', $this->locator->getBrand()->getTypeId());
    }

    /**
     * Return prev set id
     *
     * @return int
     */
    private function getPreviousSetId()
    {
        return (int)$this->request->getParam('prev_set_id', 0);
    }

    /**
     * Retrieve groups
     *
     * @return AttributeGroupInterface[]
     */
    private function getGroups()
    {
        if (!$this->attributeGroups) {
            $searchCriteria = $this->prepareGroupSearchCriteria()->create();
            $attributeGroupSearchResult = $this->attributeGroupRepository->getList($searchCriteria);
            foreach ($attributeGroupSearchResult->getItems() as $group) {
                $this->attributeGroups[$this->calculateGroupCode($group)] = $group;
            }
        }

        return $this->attributeGroups;
    }

    /**
     * Initialize attribute group search criteria with filters.
     *
     * @return SearchCriteriaBuilder
     */
    private function prepareGroupSearchCriteria()
    {
        return $this->searchCriteriaBuilder->addFilter(
            AttributeGroupInterface::ATTRIBUTE_SET_ID,
            $this->getAttributeSetId()
        );
    }

    /**
     * Return current attribute set id
     *
     * @return int|null
     */
    private function getAttributeSetId()
    {
        return $this->locator->getBrand()->getAttributeSetId();
    }

    /**
     * Retrieve attributes
     *
     * @return BrandAttributeInterface[]
     */
    private function getAttributes()
    {

        if (!$this->attributes) {
            foreach ($this->getGroups() as $group) {

                $this->attributes[$this->calculateGroupCode($group)] = $this->loadAttributes($group);
            }
        }

        return $this->attributes;

    }
    /**
     * Retrieve EAV Config Singleton
     *
     * @return \Magento\Eav\Model\Config
     */
    private function getEavConfig()
    {
        return $this->eavConfig;
    }
    /**
     * Loading brand attributes from group
     *
     * @param AttributeGroupInterface $group
     * @return BrandAttributeInterface[]
     */
    private function loadAttributes(AttributeGroupInterface $group)
    {

        $attributes = [];
        $sortOrder = $this->sortOrderBuilder
            ->setField('sort_order')
            ->setAscendingDirection()
            ->create();
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter(AttributeGroupInterface::GROUP_ID, $group->getAttributeGroupId())
            //->addFilter(BrandAttributeInterface::IS_VISIBLE, 1)
            ->addSortOrder($sortOrder)
            ->create();

        $groupAttributes = $this->attributeRepository->getList($searchCriteria)->getItems();



        foreach ($groupAttributes as $attribute) {
                $attributes[] = $attribute;
        }

        return $attributes;
    }

    /**
     * Get attribute codes of prev set
     *
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    private function getPreviousSetAttributes()
    {
        if ($this->prevSetAttributes === null) {
            $searchCriteria = $this->searchCriteriaBuilder
                ->addFilter('attribute_set_id', $this->getPreviousSetId())
                ->create();
            $attributes = $this->attributeRepository->getList($searchCriteria)->getItems();
            $this->prevSetAttributes = [];
            foreach ($attributes as $attribute) {
                $this->prevSetAttributes[] = $attribute->getAttributeCode();
            }
        }

        return $this->prevSetAttributes;
    }

    /**
     * Initial meta setup
     *
     * @param BrandAttributeInterface $attribute
     * @param string $groupCode
     * @param int $sortOrder
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @api
     */
    public function setupAttributeMeta(BrandAttributeInterface $attribute, $groupCode, $sortOrder)
    {
        $configPath = ltrim(static::META_CONFIG_PATH, ArrayManager::DEFAULT_PATH_DELIMITER);

        $meta = $this->arrayManager->set($configPath, [], [
            'dataType' => $attribute->getFrontendInput(),
            'formElement' => $this->getFormElementsMapValue($attribute->getFrontendInput()),
            'visible' => $attribute->getIsVisible(),
            'required' => $attribute->getIsRequired(),
            'notice' => $attribute->getNote(),
            'default' => $attribute->getDefaultValue(),
            'label' => $attribute->getDefaultFrontendLabel(),
            'code' => $attribute->getAttributeCode(),
            'source' => $groupCode,
            'scopeLabel' => $this->getScopeLabel($attribute),
            'globalScope' => $this->isScopeGlobal($attribute),
            'sortOrder' => $sortOrder * self::SORT_ORDER_MULTIPLIER,
            'validation' => ['required-entry' => (bool)$attribute->getIsRequired()],
        ]);

        // TODO: Refactor to $attribute->getOptions() when MAGETWO-48289 is done
        $attributeModel = $this->getAttributeModel($attribute);
        if ($attributeModel->usesSource()) {
            $meta = $this->arrayManager->merge($configPath, $meta, [
                'options' => $attributeModel->getSource()->getAllOptions(),
            ]);
        }

        if ($this->canDisplayUseDefault($attribute)) {
            $meta = $this->arrayManager->merge($configPath, $meta, [
                'service' => [
                    'template' => 'ui/form/element/helper/service',
                ]
            ]);
        }

        if (!$this->arrayManager->exists($configPath . '/componentType', $meta)) {
            $meta = $this->arrayManager->merge($configPath, $meta, [
                'componentType' => Field::NAME,
            ]);
        }

        if (in_array($attribute->getAttributeCode(), $this->attributesToDisable)) {
            $meta = $this->arrayManager->merge($configPath, $meta, [
                'disabled' => true,
            ]);
        }

        // TODO: getAttributeModel() should not be used when MAGETWO-48284 is complete
        /*$childData = $this->arrayManager->get($configPath, $meta, []);
        if (($rules = $this->catalogEavValidationRules->build($this->getAttributeModel($attribute), $childData))) {
            $meta = $this->arrayManager->merge($configPath, $meta, [
                'validation' => $rules,
            ]);
        }*/

        $meta = $this->addUseDefaultValueCheckbox($attribute, $meta);

        switch ($attribute->getFrontendInput()) {
            case 'boolean':
                $meta = $this->customizeCheckbox($attribute, $meta);
                break;
            case 'textarea':
                $meta = $this->customizeWysiwyg($attribute, $meta);
                break;
            case 'price':
                $meta = $this->customizePriceAttribute($attribute, $meta);
                break;
            case 'image':
                $meta = $this->customizeImageAttribute($attribute, $meta);
                break;
            case 'file':
                $meta = $this->customizeImageAttribute($attribute, $meta);
                break;
            case 'gallery':
                // Gallery attribute is being handled by "Images And Videos" section
                $meta = [];
                break;
        }

        return $meta;
    }

    /**
     * @param BrandAttributeInterface $attribute
     * @param array $meta
     * @return array
     */
    private function addUseDefaultValueCheckbox(BrandAttributeInterface $attribute, array $meta)
    {
        $canDisplayService = $this->canDisplayUseDefault($attribute);
        if ($canDisplayService) {
            $meta['arguments']['data']['config']['service'] = [
                'template' => 'ui/form/element/helper/service',
            ];

            $meta['arguments']['data']['config']['disabled'] = !$this->scopeOverriddenValue->containsValue(
                \Ajay\Brand\Api\Data\BrandInterface::class,
                $this->locator->getBrand(),
                $attribute->getAttributeCode(),
                $this->locator->getStore()->getId()
            );
        }
        return $meta;
    }

    /**
     * Setup attribute container meta
     *
     * @param BrandAttributeInterface $attribute
     * @return array
     * @api
     */
    public function setupAttributeContainerMeta(BrandAttributeInterface $attribute)
    {
        $containerMeta = $this->arrayManager->set(
            'arguments/data/config',
            [],
            [
                'formElement' => 'container',
                'componentType' => 'container',
                'breakLine' => false,
                'label' => $attribute->getDefaultFrontendLabel(),
                'required' => $attribute->getIsRequired(),
            ]
        );

        if ($attribute->getIsWysiwygEnabled()) {
            $containerMeta = $this->arrayManager->merge(
                'arguments/data/config',
                $containerMeta,
                [
                    'component' => 'Magento_Ui/js/form/components/group'
                ]
            );
        }

        return $containerMeta;
    }

    /**
     * Setup attribute data
     *
     * @param BrandAttributeInterface $attribute
     * @return mixed|null
     * @api
     */
    public function setupAttributeData(BrandAttributeInterface $attribute)
    {
        $brand = $this->locator->getBrand();
        $brandId = $brand->getId();
        $prevSetId = $this->getPreviousSetId();
        $notUsed = !$prevSetId
            || ($prevSetId && !in_array($attribute->getAttributeCode(), $this->getPreviousSetAttributes()));

        if ($brandId && $notUsed) {
            return $this->getValue($attribute);
        }

        return null;
    }    

    
    /**
     * Customize checkboxes
     *
     * @param BrandAttributeInterface $attribute
     * @param array $meta
     * @return array
     */
    private function customizeCheckbox(BrandAttributeInterface $attribute, array $meta)
    {
        if ($attribute->getFrontendInput() === 'boolean') {
            $meta['arguments']['data']['config']['prefer'] = 'toggle';
            $meta['arguments']['data']['config']['valueMap'] = [
                'true' => '1',
                'false' => '0',
            ];
        }

        return $meta;
    }

    /**
     * Customize image
     *
     * @param BrandAttributeInterface $attribute
     * @param array $meta
     * @return array
     */
    private function customizeImageAttribute(BrandAttributeInterface $attribute, array $meta)
    {
        $brand = $this->locator->getBrand();
        $brandData = array();
        /*if ($attribute->getFrontendInput() === 'image' || $attribute->getFrontendInput() === 'file') {
            $fileData = $brand->getData($attribute->getAttributeCode());
            if($fileData != ''){
                $meta['arguments']['data']['config']['elementTmpl'] = 'Ajay_Brand/image';
            }
        }*/
        $meta = $this->overrideFileUploaderMetadata($attribute, $meta);

        return $meta;
    }

    /**
     * Override file uploader UI component metadata
     *
     * Overrides metadata for attributes with frontend_input equal to 'image' or 'file'.
     *
     * @param Type $entityType
     * @param AbstractAttribute $attribute
     * @param array $config
     * @return void
     */
    private function overrideFileUploaderMetadata(
        BrandAttributeInterface $attribute,
        array $meta
    ) {
        if (in_array($attribute->getFrontendInput(), $this->fileUploaderTypes)) {
            $maxFileSize = self::MAX_FILE_SIZE;

            $allowedExtensions = ['jpg', 'jpeg', 'png'];
            $allowedExtensions = implode(' ', $allowedExtensions);

            $url = $this->getFileUploadUrl();

            $meta['arguments']['data']['config']['formElement'] = 'fileUploader';
            $meta['arguments']['data']['config']['componentType'] = 'fileUploader';
            $meta['arguments']['data']['config']['component'] = 'Ajay_Brand/js/components/file-uploader';
            $meta['arguments']['data']['config']['elementTmpl'] = 'Ajay_Brand/components/file-uploader';
            $meta['arguments']['data']['config']['maxFileSize'] = $maxFileSize;
            $meta['arguments']['data']['config']['dataType'] = 'string';
            $meta['arguments']['data']['config']['allowedExtensions'] = $allowedExtensions;
            $meta['arguments']['data']['config']['inputName'] = $attribute->getAttributeCode();
            $meta['arguments']['data']['config']['dataScope'] = $attribute->getAttributeCode();
            $meta['arguments']['data']['config']['uploaderConfig'] = [
                'url' => $this->urlBuilder->addSessionParam()->getUrl(
                    $url,
                    ['type' => $attribute->getAttributeCode(), '_secure' => true]
                ),
            ];
            
            /*$meta['arguments']['data']['config'] = [
                'componentType' => 'container',
                'formElement' => 'container',
                'component' => 'Magento_Ui/js/form/components/group',
                'label' => __('Color Image'),
                'dataScope' => '',
                //'sortOrder' => 100,
            ];

            $meta['children'][$attribute->getAttributeCode()]['arguments']['data']['config'] = [
                'dataType' => $attribute->getFrontendInput(),
                'formElement' => 'fileUploader',
                'componentType' => 'fileUploader',
                'component' => 'Ajay_Merchandise/js/components/file-uploader',
                'elementTmpl' => 'Ajay_Merchandise/components/file-uploader',
                'maxFileSize' => $maxFileSize,
                'allowedExtensions' => $allowedExtensions,
                'uploaderConfig' => [
                    'url' => $url,
                ],
                'label' => $this->getMetadataValue($meta, 'label'),
                'sortOrder' => $this->getMetadataValue($meta, 'sortOrder'),
                'required' => $this->getMetadataValue($meta, 'required'),
                'visible' => $this->getMetadataValue($meta, 'visible'),
                'validation' => $this->getMetadataValue($meta, 'validation'),
                'source' => 'general',
                'scopeLabel' => $this->getScopeLabel($attribute),
                'globalScope' => $this->isScopeGlobal($attribute),
                'code' => $attribute->getAttributeCode(),
            ];*/

            return $meta;
        }
    }
    /**
     * Retrieve URL to file upload
     *
     * @param string $entityTypeCode
     * @return string
     */
    private function getFileUploadUrl()
    {
        $url = 'brand/brand_image/upload';
        return $url;
    }

    /**
     * Retrieve metadata value
     *
     * @param array $config
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    private function getMetadataValue($config, $name, $default = null)
    {
        $value = isset($config[$name]) ? $config[$name] : $default;
        return $value;
    }
    /**
     * Customize attribute that has price type
     *
     * @param BrandAttributeInterface $attribute
     * @param array $meta
     * @return array
     */
    private function customizePriceAttribute(BrandAttributeInterface $attribute, array $meta)
    {
        if ($attribute->getFrontendInput() === 'price') {
            $meta['arguments']['data']['config']['addbefore'] = $this->locator->getStore()
                ->getBaseCurrency()
                ->getCurrencySymbol();
        }

        return $meta;
    }

    /**
     * Add wysiwyg properties
     *
     * @param BrandAttributeInterface $attribute
     * @param array $meta
     * @return array
     */
    private function customizeWysiwyg(BrandAttributeInterface $attribute, array $meta)
    {
        if (!$attribute->getIsWysiwygEnabled()) {
            return $meta;
        }

        $meta['arguments']['data']['config']['formElement'] = WysiwygElement::NAME;
        $meta['arguments']['data']['config']['wysiwyg'] = true;
        $meta['arguments']['data']['config']['wysiwygConfigData'] = [
            'add_variables' => false,
            'add_widgets' => false
        ];

        return $meta;
    }

    /**
     * Retrieve form element
     *
     * @param string $value
     * @return mixed
     */
    private function getFormElementsMapValue($value)
    {
        $valueMap = $this->formElementMapper->getMappings();

        return isset($valueMap[$value]) ? $valueMap[$value] : $value;
    }

    /**
     * Retrieve attribute value
     *
     * @param BrandAttributeInterface $attribute
     * @return mixed
     */
    private function getValue(BrandAttributeInterface $attribute)
    {
        $attributeData = array();
        /** @var Brand $brand */
        $brand = $this->locator->getBrand();
        if (in_array($attribute->getFrontendInput(), $this->fileUploaderTypes)) {
            return $this->getFileUploaderData(
                $attribute,
                $brand->getData()
            );
        }
        return $brand->getData($attribute->getAttributeCode());
    }
    /**
     * Retrieve array of values required by file uploader UI component
     *
     * @param Type $entityType
     * @param Attribute $attribute
     * @param array $customerData
     * @return array
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    private function getFileUploaderData(
        BrandAttributeInterface $attribute,
        array $brandData
    ) {
        $attributeCode = $attribute->getAttributeCode();

        $file = isset($brandData[$attributeCode])
            ? $brandData[$attributeCode]
            : '';

        /** @var FileProcessor $fileProcessor */
        $fileProcessor = $this->getFileProcessorFactory()->create();
        $fileProcessor->isExist($file);

        if (!empty($file)
            && $fileProcessor->isExist($file)
        ) {
            $stat = $fileProcessor->getStat($file);
            $viewUrl = $fileProcessor->getViewUrl($file, $attribute->getFrontendInput());
        }

        $fileName = $file;
        if (strrpos($fileName, '/') !== false) {
            $fileName = substr($fileName, strrpos($fileName, '/') + 1);
        }

        if (!empty($file)) {
            return [
                [
                    'file' => $file,
                    'size' => isset($stat) ? $stat['size'] : 0,
                    'url' => isset($viewUrl) ? $viewUrl : '',
                    'name' => $fileName,
                ],
            ];
        }
        return [];
    }

    
    /**
     * Retrieve scope label
     *
     * @param BrandAttributeInterface $attribute
     * @return \Magento\Framework\Phrase|string
     */
    private function getScopeLabel(BrandAttributeInterface $attribute)
    {
        if (
            $this->storeManager->isSingleStoreMode()
            || $attribute->getFrontendInput() === AttributeInterface::FRONTEND_INPUT
        ) {
            return '';
        }


        switch ($attribute->getScope()) {
            case BrandAttributeInterface::SCOPE_GLOBAL_TEXT:
                return __('[GLOBAL]');
            case BrandAttributeInterface::SCOPE_WEBSITE_TEXT:
                return __('[WEBSITE]');
            case BrandAttributeInterface::SCOPE_STORE_TEXT:
                return __('[STORE VIEW]');
        }

        return '';
    }

    /**
     * Whether attribute can have default value
     *
     * @param BrandAttributeInterface $attribute
     * @return bool
     */
    private function canDisplayUseDefault(BrandAttributeInterface $attribute)
    {
        $attributeCode = $attribute->getAttributeCode();
        /** @var Brand $brand */
        $brand = $this->locator->getBrand();

        if (isset($this->canDisplayUseDefault[$attributeCode])) {
            //return $this->canDisplayUseDefault[$attributeCode];
        }

        return $this->canDisplayUseDefault[$attributeCode] = (
            ($attribute->getScope() != BrandAttributeInterface::SCOPE_GLOBAL_TEXT)
            && $brand
            && $brand->getId()
            && $brand->getStoreId()
        );
    }

    /**
     * Check if attribute scope is global.
     *
     * @param BrandAttributeInterface $attribute
     * @return bool
     */
    private function isScopeGlobal($attribute)
    {
        return $attribute->getScope() === BrandAttributeInterface::SCOPE_GLOBAL_TEXT;
    }

    /**
     * Load attribute model by attribute data object.
     *
     * TODO: This method should be eliminated when all missing service methods are implemented
     *
     * @param BrandAttributeInterface $attribute
     * @return EavAttribute
     */
    private function getAttributeModel($attribute)
    {
        return $this->eavAttributeFactory->create()->load($attribute->getAttributeId());
    }

    /**
     * Calculate group code based on group name.
     *
     * TODO: This logic is copy-pasted from \Magento\Eav\Model\Entity\Attribute\Group::beforeSave
     * TODO: and should be moved to a separate service, which will allow two-way conversion groupName <=> groupCode
     * TODO: Remove after MAGETWO-48290 is complete
     *
     * @param AttributeGroupInterface $group
     * @return string
     */
    private function calculateGroupCode(AttributeGroupInterface $group)
    {
        $attributeGroupCode = $group->getAttributeGroupCode();

        if ($attributeGroupCode === 'images') {
            $attributeGroupCode = 'image-management';
        }

        return $attributeGroupCode;
    }

    /**
     * The getter function to get the locale currency for real application code
     *
     * @return \Magento\Framework\Locale\CurrencyInterface
     *
     * @deprecated
     */
    private function getLocaleCurrency()
    {
        if ($this->localeCurrency === null) {
            $this->localeCurrency = \Magento\Framework\App\ObjectManager::getInstance()->get(CurrencyInterface::class);
        }
        return $this->localeCurrency;
    }

    /**
     * Format price according to the locale of the currency
     *
     * @param mixed $value
     * @return string
     */
    protected function formatPrice($value)
    {
        if (!is_numeric($value)) {
            return null;
        }

        $store = $this->storeManager->getStore();
        $currency = $this->getLocaleCurrency()->getCurrency($store->getBaseCurrencyCode());
        $value = $currency->toCurrency($value, ['display' => \Magento\Framework\Currency::NO_SYMBOL]);

        return $value;
    }

    /**
     * Get FileProcessorFactory instance
     *
     * @return FileProcessorFactory
     *
     * @deprecated
     */
    private function getFileProcessorFactory()
    {
        if ($this->fileProcessorFactory === null) {
            $this->fileProcessorFactory = ObjectManager::getInstance()
                ->get('Ajay\Brand\Model\FileProcessorFactory');
        }
        return $this->fileProcessorFactory;
    }
}
