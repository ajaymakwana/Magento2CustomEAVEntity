<?php

namespace Ajay\Brand\Api;

/**
 * @api
 */
interface BrandRepositoryInterface
{
    /**
     * Create brand
     *
     * @param \Ajay\Brand\Api\Data\BrandInterface $brand
     * @return \Ajay\Brand\Api\Data\BrandInterface
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Exception\StateException
     * @throws \Magento\Framework\Exception\CouldNotSaveException
     */
    public function save(\Ajay\Brand\Api\Data\BrandInterface $brand);

    /**
     * Get info about brand by brand id
     *
     * @param string $sku
     * @param int|null $storeId
     * @return \Ajay\Brand\Api\Data\BrandInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function get($brandId, $storeId = null);

    /**
     * Delete brand
     *
     * @param \Ajay\Brand\Api\Data\BrandInterface $brand
     * @return bool Will returned True if deleted
     * @throws \Magento\Framework\Exception\StateException
     */
    public function delete(\Ajay\Brand\Api\Data\BrandInterface $brand);

    /**
     * @param string $id
     * @return bool Will returned True if deleted
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\StateException
     */
    public function deleteById($id);

    /**
     * Get brand list
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Ajay\Brand\Api\Data\BrandSearchResultsInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);
}