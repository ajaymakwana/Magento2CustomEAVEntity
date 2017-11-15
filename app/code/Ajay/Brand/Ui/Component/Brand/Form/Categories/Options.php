<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ajay\Brand\Ui\Component\Brand\Form\Categories;

use Magento\Framework\Data\OptionSourceInterface;
use Ajay\Brand\Model\ResourceModel\Category\CollectionFactory;
use Magento\Framework\App\RequestInterface;
use Ajay\Brand\Model\Category as CategoryModel;

/**
 * Options tree for "Categories" field
 */
class Options implements OptionSourceInterface
{
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Category\CollectionFactory
     */
    protected $categoryCollectionFactory;

    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var array
     */
    protected $categoriesTree;

    /**
     * @param CollectionFactory $categoryCollectionFactory
     * @param RequestInterface $request
     */
    public function __construct(
        CollectionFactory $categoryCollectionFactory,
        RequestInterface $request
    ) {
        $this->categoryCollectionFactory = $categoryCollectionFactory;
        $this->request = $request;
    }

    /**
     * {@inheritdoc}
     */
    public function toOptionArray()
    {
        return $this->getCategoriesTree();
    }

    /**
     * Retrieve categories tree
     *
     * @return array
     */
    protected function getCategoriesTree()
    {
        if ($this->categoriesTree === null) {
            $storeId = $this->request->getParam('store');
            /* @var $matchingNamesCollection \Magento\Catalog\Model\ResourceModel\Category\Collection */
            $matchingNamesCollection = $this->categoryCollectionFactory->create();

            $matchingNamesCollection->addAttributeToSelect('path')
                ->addAttributeToFilter('entity_id', ['neq' => CategoryModel::TREE_ROOT_ID])
                ->setStoreId($storeId);

            $shownCategoriesIds = [];

            /** @var \Magento\Catalog\Model\Category $category */
            foreach ($matchingNamesCollection as $category) {
                foreach (explode('/', $category->getPath()) as $parentId) {
                    $shownCategoriesIds[$parentId] = 1;
                }
            }

            /* @var $collection \Magento\Catalog\Model\ResourceModel\Category\Collection */
            $collection = $this->categoryCollectionFactory->create();

            $collection->addAttributeToSelect(['name', 'is_active', 'parent_id']);
            // ->setStoreId($storeId);

            $categoryById = [
                CategoryModel::TREE_ROOT_ID => [
                    'value' => CategoryModel::TREE_ROOT_ID,
                    'optgroup' => null,
                ],
            ];

            $categories = [];
            $i = 0;
            foreach ($collection as $category) {
                foreach ([$category->getId(), $category->getParentId()] as $categoryId) {
                    if (!isset($categoryById[$categoryId])) {
                        //$categoryById[$categoryId] = ['value' => $categoryId];

                    }
                }

                $categories[$i]['is_active'] = 1;
                $categories[$i]['label'] = $category->getTitle();
                $categories[$i]['value'] = $category->getId();
                $i++;
            }
            $this->categoriesTree = &$categories;

        }

        return $this->categoriesTree;
    }
}
