<?php

namespace Ajay\Brand\Model\Brand;

use Magento\Framework\Api\AttributeValueFactory;
use Magento\UrlRewrite\Model\UrlFinderInterface;
use Magento\Framework\Stdlib\DateTime\DateTimeFormatterInterface;
/**
 * Ajay Brand
 *
 * @method string getUrlKey()
 * @method Brand setUrlKey(string $urlKey)
 *
 * @SuppressWarnings(PHPMD.LongVariable)
 * @SuppressWarnings(PHPMD.ExcessivePublicCount)
 * @SuppressWarnings(PHPMD.TooManyFields)
 * @SuppressWarnings(PHPMD.ExcessiveClassComplexity)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Attribute extends \Magento\Eav\Model\Entity\Attribute
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const SCOPE_STORE       = 0;
    const SCOPE_GLOBAL      = 1;
    const SCOPE_WEBSITE     = 2;

    const MODULE_NAME       = 'Ajay_Brand';
    const ENTITY            = 'ajay_brand';

    /**
     * Event prefix
     *
     * @var string
     */
    protected $_eventPrefix = 'ajay_brand_entity';

    /**
     * Event object name
     *
     * @var string
     */
    protected $_eventObject = 'attribute';

    /**
     * Array with labels
     *
     * @var array
     */
    static protected $_labels = null;




    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory
     * @param AttributeValueFactory $customAttributeFactory
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\UrlInterface $url
     * @param \Magento\Framework\Filter\FilterManager $filter,
     * @param UrlFinderInterface $urlFinder,
     * @param \Magento\Framework\Model\ResourceModel\AbstractResource $resource
     * @param \Magento\Framework\Data\Collection\AbstractDb $resourceCollection
     * @param array $data
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */



    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Api\ExtensionAttributesFactory $extensionFactory,
        AttributeValueFactory $customAttributeFactory,
        \Magento\Eav\Model\Config $eavConfig,
        \Magento\Eav\Model\Entity\TypeFactory $eavTypeFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Eav\Model\ResourceModel\Helper $resourceHelper,
        \Magento\Framework\Validator\UniversalFactory $universalFactory,
        \Magento\Eav\Api\Data\AttributeOptionInterfaceFactory $optionDataFactory,
        \Magento\Framework\Reflection\DataObjectProcessor $dataObjectProcessor,
        \Magento\Framework\Api\DataObjectHelper $dataObjectHelper,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $localeDate,
        \Magento\Catalog\Model\Product\ReservedAttributeList $reservedAttributeList,
        \Magento\Framework\Locale\ResolverInterface $localeResolver,
        DateTimeFormatterInterface $dateTimeFormatter,
        \Magento\Framework\Model\ResourceModel\AbstractResource $resource = null,
        \Magento\Framework\Data\Collection\AbstractDb $resourceCollection = null,
        array $data = []
    ) {
        parent::__construct(
            $context,
            $registry,
            $extensionFactory,
            $customAttributeFactory,
            $eavConfig,
            $eavTypeFactory,
            $storeManager,
            $resourceHelper,
            $universalFactory,
            $optionDataFactory,
            $dataObjectProcessor,
            $dataObjectHelper,
            $localeDate,
            $reservedAttributeList,
            $localeResolver,
            $dateTimeFormatter,
            $resource,
            $resourceCollection,
            $data
        );
    }
    /**
     * Initialize resource mode
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init('Ajay\Brand\Model\Brand\Attribute');
    }

    /**
     * Processing object before save data
     *
     * @access protected
     * @throws Mage_Core_Exception
     * @return Mage_Core_Model_Abstract
     * @author Ultimate Module Creator
     */
    protected function _beforeSave()
    {
        $this->setData('modulePrefix', self::MODULE_NAME);
        if (isset($this->_origData['is_global'])) {
            if (!isset($this->_data['is_global'])) {
                $this->_data['is_global'] = self::SCOPE_GLOBAL;
            }
        }
        if ($this->getFrontendInput() == 'textarea') {
            if ($this->getIsWysiwygEnabled()) {
                $this->setIsHtmlAllowedOnFront(1);
            }
        }
        return parent::_beforeSave();
    }

    /**
     * Processing object after save data
     *
     * @access protected
     * @return Mage_Core_Model_Abstract
     * @author Ultimate Module Creator
     */
    protected function _afterSave()
    {
        /**
         * Fix saving attribute in admin
         */
        //Mage::getSingleton('eav/config')->clear();
        return parent::_afterSave();
    }

    /**
     * Return is attribute global
     *
     * @access public
     * @return integer
     * @author Ultimate Module Creator
     */
    public function getIsGlobal()
    {
        return $this->_getData('is_global');
    }

    /**
     * Retrieve attribute is global scope flag
     *
     * @access public
     * @return bool
     * @author Ultimate Module Creator
     */
    public function isScopeGlobal()
    {
        return $this->getIsGlobal() == self::SCOPE_GLOBAL;
    }

    /**
     * Retrieve attribute is website scope website
     *
     * @access public
     * @return bool
     * @author Ultimate Module Creator
     */
    public function isScopeWebsite()
    {
        return $this->getIsGlobal() == self::SCOPE_WEBSITE;
    }

    /**
     * Retrieve attribute is store scope flag
     *
     * @access public
     * @return bool
     * @author Ultimate Module Creator
     */
    public function isScopeStore()
    {
        return !$this->isScopeGlobal() && !$this->isScopeWebsite();
    }

    /**
     * Retrieve store id
     *
     * @access public
     * @return int
     * @author Ultimate Module Creator
     */
    public function getStoreId()
    {
        $dataObject = $this->getDataObject();
        if ($dataObject) {
            return $dataObject->getStoreId();
        }
        return $this->getData('store_id');
    }
    /**
     * Retrieve source model
     *
     * @access public
     * @return Mage_Eav_Model_Entity_Attribute_Source_Abstract
     * @author Ultimate Module Creator
     */
    public function getSourceModel()
    {
        $model = $this->getData('source_model');
        if (empty($model)) {
            if ($this->getBackendType() == 'int' && $this->getFrontendInput() == 'select') {
                return $this->_getDefaultSourceModel();
            }
        }
        return $model;
    }


    /**
     * Retrieve not translated frontend label
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function getFrontendLabel()
    {
        return $this->_getData('frontend_label');
    }

    /**
     * Get Attribute translated label for store
     *
     * @access protected
     * @deprecated
     * @return string
     * @author Ultimate Module Creator
     */
    protected function _getLabelForStore()
    {
        return $this->getFrontendLabel();
    }

    /**
     * Get default attribute source model
     *
     * @access public
     * @return string
     * @author Ultimate Module Creator
     */
    public function _getDefaultSourceModel()
    {
        return 'Magento\Eav\Model\Entity\Attribute\Source\Table';
    }
}
