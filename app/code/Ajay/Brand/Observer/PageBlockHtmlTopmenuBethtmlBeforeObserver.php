<?php
/**
 * Copyright © 2017 Ajay Makwana (ajaymakwana.mail@gmail.com). All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 *
 * Life is a code
 */

namespace Ajay\Brand\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Data\Tree\Node;
use Magento\Store\Model\ScopeInterface;
use Ajay\Brand\Helper\Config;

/**
 * Brand observer
 */
class PageBlockHtmlTopmenuBethtmlBeforeObserver implements ObserverInterface
{
    /**
     * Show top menu item config path
     */
    const XML_PATH_TOP_MENU_SHOW_ITEM = 'dnbbrand/top_menu/show_item';

    /**
     * Top menu item text config path
     */
    const XML_PATH_TOP_MENU_ITEM_TEXT = 'dnbbrand/top_menu/item_text';

    /**
     * @var \Ajay\Brand\Model\Url
     */
    protected $_url;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $_scopeConfig;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * Core registry
     *
     * @var \Magento\Framework\Registry
     */
    protected $_coreRegistry;

    /**
     * @param \Ajay\Brand\Model\Url $url
     */
    public function __construct(
        \Ajay\Brand\Model\Url $url,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Registry $registry,
        \Ajay\Brand\Model\ResourceModel\Category\Collection $categoryCollection
    ) {
        $this->_scopeConfig = $scopeConfig;
        $this->_url = $url;
        $this->_storeManager = $storeManager;
        $this->_coreRegistry = $registry;
        $this->_categoryCollection = $categoryCollection;
    }

    /**
     * Page block html topmenu gethtml before
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return void
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        if (!$this->_scopeConfig->isSetFlag(static::XML_PATH_TOP_MENU_SHOW_ITEM, ScopeInterface::SCOPE_STORE)) {
            return;
        }

        if (!$this->_scopeConfig->isSetFlag(Config::XML_PATH_EXTENSION_ENABLED, ScopeInterface::SCOPE_STORE)) {
            return;
        }

        /** @var \Magento\Framework\Data\Tree\Node $menu */
        $menu = $observer->getMenu();
        $block = $observer->getBlock();

        $tree = $menu->getTree();
        $data = [
            'name'      => $this->_scopeConfig->getValue(static::XML_PATH_TOP_MENU_ITEM_TEXT, ScopeInterface::SCOPE_STORE),
            'id'        => 'ajay-brand',
            'url'       => $this->_url->getBaseUrl(),
            'is_active' => ($block->getRequest()->getModuleName() == 'brand'),
        ];
        $node = new Node($data, 'id', $tree, $menu);
        $menu->addChild($node);

        $rootId = $this->_storeManager->getStore()->getRootCategoryId();
        $currentCategory = $this->getCurrentCategory();

        $collection = $this->_categoryCollection
            ->addAttributeToSelect('*')
            ->addFieldToFilter('status', 1)
            //->addStoreFilter($this->_storeManager->getStore()->getId())
            ->setOrder('position');
            //->getTreeOrderedArray();

        $mapping = [$rootId => $menu];  // use nodes stack to avoid recursion

        foreach ($collection as $category) {
            $tree = $node->getTree();

            $categoryNode = new Node(
                $this->getCategoryAsArray($category, $currentCategory),
                'id',
                $tree,
                $node
            );
            $node->addChild($categoryNode);
        }
        
    }

    /**
     * Convert category to array
     *
     * @param \Magento\Catalog\Model\Category $category
     * @param \Magento\Catalog\Model\Category $currentCategory
     * @return array
     */
    private function getCategoryAsArray($category, $currentCategory)
    {
        return [
            'name' => $category->getTitle(),
            'id' => 'category-node-' . $category->getId(),
            'url' => $category->getCategoryUrl(),
            //'has_active' => in_array((string)$category->getId(), explode('/', $currentCategory->getPath()), true),
            //'is_active' => $category->getId() == $currentCategory->getId()
        ];
    }

    /**
     * @return void
     */

    public function getCurrentCategory()
    {
        return $this->_coreRegistry->registry('current_category');
    }
}
