<?php

namespace Ajay\Brand\Controller\Adminhtml;

/**
 * Abstract Action
 * @category Ajay
 * @package  Ajay_Brand
 * @module   Brand
 * @author   Ajay Developer
 */
abstract class AbstractAction extends \Magento\Backend\App\Action
{
    const PARAM_ID = 'id';

    /**
     * @var \Magento\Backend\Helper\Js
     */
    protected $_jsHelper;

    /**
     * @var \Magento\Backend\Model\View\Result\ForwardFactory
     */
    protected $_resultForwardFactory;

    /**
     * @var \Magento\Framework\View\Result\LayoutFactory
     */
    protected $_resultLayoutFactory;

    /**
     * @var \Magento\Framework\View\LayoutFactory
     */
    protected $layoutFactory;

    /**
     * @var \Magento\Framework\View\Result\PageFactory
     */
    protected $_resultPageFactory;

    /**
     * Banner factory.
     *
     * @var \Ajay\Brand\Model\BannerFactory
     */
    protected $_bannerFactory;

    /**
     * Brand factory.
     *
     * @var \Ajay\Brand\Model\BrandFactory
     */
    protected $_brandFactory;

    /**
     * Brand Category factory.
     *
     * @var \Ajay\Brand\Model\CategoryFactory
     */
    protected $_categoryFactory;

    /**
     * Banner Collection Factory.
     *
     * @var \Ajay\Brand\Model\ResourceModel\Banner\CollectionFactory
     */
    protected $_bannerCollectionFactory;

    /**
     * Brand Collection Factory.
     *
     * @var \Ajay\Brand\Model\ResourceModel\Brand\CollectionFactory
     */
    protected $_brandCollectionFactory;

    /**
     * Brand Category Collection Factory.
     *
     * @var \Ajay\Brand\Model\ResourceModel\Category\CollectionFactory
     */
    protected $_categoryCollectionFactory;

    /**
     * Registry object.
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * File Factory.
     *
     * @var \Magento\Framework\App\Response\Http\FileFactory
     */
    protected $_fileFactory;

    /**
     * @param \Magento\Backend\App\Action\Context                                        $context
     * @param \Magento\Framework\Registry                                                $coreRegistry
     * @param \Magento\Framework\App\Response\Http\FileFactory                           $fileFactory
     * @param \Magento\Framework\View\Result\PageFactory                                 $resultPageFactory
     * @param \Magento\Framework\View\Result\LayoutFactory                               $resultLayoutFactory
     * @param \Magento\Framework\View\LayoutFactory                                         $layoutFactory
     * @param \Magento\Framework\Controller\Result\JsonFactory                              $resultJsonFactory
     * @param \Magento\Backend\Model\View\Result\ForwardFactory                          $resultForwardFactory
     * @param \Magento\Backend\Helper\Js                                                 $jsHelper
     * @param \Ajay\Brand\Model\BannerFactory                           $bannerFactory
     * @param \Ajay\Brand\Model\BrandFactory                           $brandFactory
     * @param \Ajay\Brand\Model\ResourceModel\Banner\CollectionFactory  $bannerCollectionFactory
     * @param \Ajay\Brand\Model\ResourceModel\Brand\CollectionFactory  $brandCollectionFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Framework\App\Response\Http\FileFactory $fileFactory,
        \Magento\Framework\View\Result\PageFactory $resultPageFactory,
        \Magento\Framework\View\Result\LayoutFactory $resultLayoutFactory,
        \Magento\Framework\View\LayoutFactory $layoutFactory,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
        \Magento\Backend\Model\View\Result\ForwardFactory $resultForwardFactory,
        \Magento\Backend\Helper\Js $jsHelper,
        \Ajay\Brand\Model\BrandFactory $brandFactory,
        \Ajay\Brand\Model\ResourceModel\Brand\CollectionFactory $brandCollectionFactory,
        \Ajay\Brand\Model\CategoryFactory $categoryFactory,
        \Ajay\Brand\Model\ResourceModel\Category\CollectionFactory $categoryCollectionFactory
    ) {
        parent::__construct($context);

        $this->_coreRegistry = $coreRegistry;
        $this->_fileFactory = $fileFactory;
        $this->_resultPageFactory = $resultPageFactory;
        $this->_resultLayoutFactory = $resultLayoutFactory;
        $this->layoutFactory = $layoutFactory;
        $this->resultJsonFactory = $resultJsonFactory;
        $this->_resultForwardFactory = $resultForwardFactory;
        $this->_jsHelper = $jsHelper;
        $this->_brandFactory = $brandFactory;
        $this->_brandCollectionFactory = $brandCollectionFactory;
        $this->_categoryFactory = $categoryFactory;
        $this->_categoryCollectionFactory = $categoryCollectionFactory;
    }

    /**
     * Get result redirect after add/edit action
     *
     * @param \Magento\Framework\Controller\Result\Redirect $resultRedirect
     * @param null                                          $paramCrudId
     * @return \Magento\Framework\Controller\Result\Redirect
     */
    protected function _getResultRedirect(\Magento\Framework\Controller\Result\Redirect $resultRedirect, $paramId = null)
    {
        $back = $this->getRequest()->getParam('back');

        switch ($back) {
            case 'new':
                $resultRedirect->setPath('*/*/new', ['_current' => true]);
                break;
            case 'edit':
                $resultRedirect->setPath('*/*/edit', ['id' => $paramId, '_current' => true]);
                break;
            default:
                $resultRedirect->setPath('*/*/');
        }

        return $resultRedirect;
    }
}
