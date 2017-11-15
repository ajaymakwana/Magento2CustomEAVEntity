<?php
/**
 * Customer attribute edit page
 *
 */
namespace Ajay\Brand\Block\Adminhtml\Brand\Attribute;

class Edit extends \Magento\Backend\Block\Widget\Form\Container
{
    /**
     * Block group name
     *
     * @var string
     */
    protected $_blockGroup = 'Ajay_Brand';
    
    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry = null;
    
    /**
     * @param \Magento\Backend\Block\Widget\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param array $data
     */
    public function __construct(
        \Magento\Backend\Block\Widget\Context $context,
        \Magento\Framework\Registry $registry,
        array $data = []
    ) {
        $this->_coreRegistry = $registry;
        parent::__construct($context, $data);
    }
    
    /**
     * Initialize attribute edit block
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_objectId = 'attribute_id';
        $this->_controller = 'adminhtml_brand_attribute';

        parent::_construct();

        $entityAttribute = $this->_coreRegistry->registry('entity_attribute');
        
        $this->buttonList->add(
            'saveandcontinue',
            [
                'label' => __('Save and Continue Edit'),
                'class' => 'save',
                'data_attribute' => [
                    'mage-init' => [
                        'button' => ['event' => 'saveAndContinueEdit', 'target' => '#edit_form'],
                    ],
                ]
            ],
            -100
        );

        $this->buttonList->update('save', 'label', __('Save Attribute'));
        $this->buttonList->update('save', 'class', 'save primary');
        $this->buttonList->update(
            'save',
            'data_attribute',
            ['mage-init' => ['button' => ['event' => 'save', 'target' => '#edit_form']]]
        );
       
        if (!$entityAttribute || !$entityAttribute->getIsUserDefined()
                || !$this->_isAllowedAction('Ajay_Brand::attribute_delete')) {
            $this->buttonList->remove('delete');
        } else {
            $this->buttonList->update('delete', 'label', __('Delete Attribute'));
        }

        /*if (!$entityAttribute || !$entityAttribute->getIsUserDefined()) {
            $this->buttonList->remove('save');
            $this->buttonList->remove('saveandcontinue');
            $this->buttonList->remove('reset');
        }*/
    }
    
    /**
     * Retrieve header text
     *
     * @return \Magento\Framework\Phrase
     */
    public function getHeaderText()
    {
       if ($this->_coreRegistry->registry('customer_attribute')->getId()) {
            $frontendLabel = $this->_coreRegistry->registry('customer_attribute')->getFrontendLabel();
            if (is_array($frontendLabel)) {
                $frontendLabel = $frontendLabel[0];
            }
            return __('Edit Brand Attribute "%1"', $this->escapeHtml($frontendLabel));
        }
        return __('New Brand Attribute');
    }
    
    /**
     * Check permission for passed action
     *
     * @param string $resourceId
     * @return bool
     */
    protected function _isAllowedAction($resourceId)
    {
        return $this->_authorization->isAllowed($resourceId);
    }
    
    /**
     * Getter of url for "Save and Continue" button
     * tab_id will be replaced by desired by JS later
     *
     * @return string
     */
    protected function _getSaveAndContinueUrl()
    {
        return $this->getUrl('attribute/*/save', ['_current' => true, 'back' => null, 'active_tab' => '']);
    }
}