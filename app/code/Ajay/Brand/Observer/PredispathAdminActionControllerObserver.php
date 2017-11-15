<?php
/**
 * Copyright © 2017 Ajay Makwana (ajaymakwana.mail@gmail.com). All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 *
 * Life is a code
 */

namespace Ajay\Brand\Observer;

use Magento\Framework\Event\ObserverInterface;

/**
 * Brand observer
 */
class PredispathAdminActionControllerObserver implements ObserverInterface
{
    /**
     * @var \Ajay\Brand\Model\AdminNotificationFeedFactory
     */
    protected $_feedFactory;

    /**
     * @var \Magento\Backend\Model\Auth\Session
     */
    protected $_backendAuthSession;

    /**
     * @param \Ajay\Brand\Model\AdminNotificationFeedFactory $feedFactory
     * @param \Magento\Backend\Model\Auth\Session $backendAuthSession
     */
    public function __construct(
        \Ajay\Brand\Model\AdminNotificationFeedFactory $feedFactory,
        \Magento\Backend\Model\Auth\Session $backendAuthSession
    ) {
        $this->_feedFactory = $feedFactory;
        $this->_backendAuthSession = $backendAuthSession;
    }

    /**
     * Predispath admin action controller
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if ($this->_backendAuthSession->isLoggedIn()) {
            $feedModel = $this->_feedFactory->create();
            /* @var $feedModel \Ajay\Brand\Model\AdminNotificationFeed */
            $feedModel->checkUpdate();
        }
    }
}
