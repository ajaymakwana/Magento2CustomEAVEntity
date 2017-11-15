<?php

namespace Ajay\Brand\Api\Data\Category;

/**
 * @api
 */
interface CategorySearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{
    /**
     * Get brands list.
     *
     * @return \Ajay\Brand\Api\Data\Category\CategoryInterface[]
     */
    public function getItems();

    /**
     * Set brands list.
     *
     * @param \Ajay\Brand\Api\Data\Category\CategoryInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
