<?php

namespace Ajay\Brand\Controller\Adminhtml;

/**
 * Brand Abstract Action
 * @category Ajay
 * @package  Ajay_Brand
 * @module   Brand
 * @author   Ajay Developer
 */
abstract class Brand extends \Ajay\Brand\Controller\Adminhtml\AbstractAction
{
    const PARAM_ID = 'id';

    /**
     * Check if admin has permissions to visit brand pages.
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return $this->_authorization->isAllowed('Ajay_Brand::brand');
    }
}
