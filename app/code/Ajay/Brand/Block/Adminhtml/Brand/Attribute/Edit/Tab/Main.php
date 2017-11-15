<?php
/**
 * Customer attribute add/edit form main tab
 *
 */

namespace Ajay\Brand\Block\Adminhtml\Brand\Attribute\Edit\Tab;

use Magento\Eav\Block\Adminhtml\Attribute\Edit\Main\AbstractMain;

/**
 * @SuppressWarnings(PHPMD.DepthOfInheritance)
 */
class Main extends AbstractMain
{
    /**
     * Adding product form elements for editing attribute
     *
     * @return $this
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    protected function _prepareForm()
    {
        parent::_prepareForm();
        /** @var \Magento\Catalog\Model\ResourceModel\Eav\Attribute $attributeObject */
        $attributeObject = $this->getAttributeObject();
        /* @var $form \Magento\Framework\Data\Form */
        $form = $this->getForm();
        /* @var $fieldset \Magento\Framework\Data\Form\Element\Fieldset */
        $fieldset = $form->getElement('base_fieldset');
        $fiedsToRemove = ['attribute_code', 'is_unique', 'frontend_class'];

        foreach ($fieldset->getElements() as $element) {
            /** @var \Magento\Framework\Data\Form\AbstractForm $element  */
            if (substr($element->getId(), 0, strlen('default_value')) == 'default_value') {
                $fiedsToRemove[] = $element->getId();
            }
        }
        foreach ($fiedsToRemove as $id) {
            $fieldset->removeField($id);
        }

        $frontendInputElm = $form->getElement('frontend_input');

        $additionalTypes = [
            ['value' => 'image', 'label' => __('Image')],
            ['value' => 'file', 'label' => __('File')],
        ];
        
        $response = new \Magento\Framework\DataObject();
        $response->setTypes([]);
        $this->_eventManager->dispatch('adminhtml_product_attribute_types', ['response' => $response]);

        $_hiddenFields = [];
        foreach ($response->getTypes() as $type) {
            //$additionalTypes[] = $type;
            if (isset($type['hide_fields'])) {
                $_hiddenFields[$type['value']] = $type['hide_fields'];
            }
        }
        $this->_coreRegistry->register('attribute_type_hidden_fields', $_hiddenFields);


        //$frontendInputValues = $frontendInputElm->getValues();
        $frontendInputValues = array_merge($frontendInputElm->getValues(), $additionalTypes);
        $frontendInputElm->setValues($frontendInputValues);

        $frontendInputElm->setLabel('Input Type');
        return $this;
    }
}
