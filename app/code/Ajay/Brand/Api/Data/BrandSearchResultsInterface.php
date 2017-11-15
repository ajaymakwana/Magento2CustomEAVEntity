<?php

namespace Ajay\Brand\Api\Data;

/**
 * @api
 */
interface BrandSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{
    /**
     * Get brands list.
     *
     * @return \Ajay\Brand\Api\Data\BrandInterface[]
     */
    public function getItems();

    /**
     * Set brands list.
     *
     * @param \Ajay\Brand\Api\Data\BrandInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
