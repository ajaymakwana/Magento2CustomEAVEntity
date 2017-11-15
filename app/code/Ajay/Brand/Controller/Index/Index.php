<?php
/**
 * Copyright © 2017 Ajay Makwana (ajaymakwana.mail@gmail.com). All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 *
 * Life is a code
 */
namespace Ajay\Brand\Controller\Index;

/**
 * Brand home page view
 */
class Index extends \Ajay\Brand\App\Action\Action
{
    /**
     * View brand homepage action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        if (!$this->moduleEnabled()) {
            //return $this->_forwardNoroute();
        }

        $this->_view->loadLayout();
        $this->_view->renderLayout();
    }

}
