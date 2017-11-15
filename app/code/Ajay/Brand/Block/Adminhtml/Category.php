<?php

namespace Ajay\Brand\Block\Adminhtml;

/**
 * Brand grid container
 * @category Ajay
 * @package  Ajay_Brand
 * @module   Brand
 * @author   Ajay Developer
 */
class Category extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * Internal constructor, that is called from real constructor
     * @return void
     */
    protected function _construct()
    {
        $this->_controller = 'adminhtml_category';
        $this->_blockGroup = 'Ajay_Brand';
        $this->_headerText = __('Category');
        $this->_addButtonLabel = __('Add New Category');

        parent::_construct();
    }
}
