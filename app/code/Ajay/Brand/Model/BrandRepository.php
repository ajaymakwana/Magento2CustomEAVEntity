<?php

namespace Ajay\Brand\Model;

use Magento\Framework\Exception\NoSuchEntityException;

class BrandRepository implements \Ajay\Brand\Api\BrandRepositoryInterface
{
    /**
     * @var Brand[]
     */
    protected $instances = [];

    /**
     * @var \Ajay\Brand\Model\BrandFactory
     */
    protected $brandFactory;

    /**
     * @var \Ajay\Brand\Model\ResourceModel\Brand
     */
    protected $resourceModel;

    /**
     * @param \Ajay\Brand\Model\BrandFactory $brandFactory
     * @param \Ajay\Brand\Model\ResourceModel\Brand $resourceModel
     */
    public function __construct(
        \Ajay\Brand\Model\BrandFactory $brandFactory,
        \Ajay\Brand\Model\ResourceModel\Brand $resourceModel
    ) {
        $this->brandFactory = $brandFactory;
        $this->resourceModel = $resourceModel;
    }

    /**
     * @inheritdoc
     */
    public function save(\Ajay\Brand\Api\Data\BrandInterface $brand)
    {
        try {
            $this->resourceModel->save($brand);
        } catch (\Exception $e) {
            throw new \Magento\Framework\Exception\CouldNotSaveException(
                __(
                    'Could not save brand: %1',
                    $e->getMessage()
                ),
                $e
            );
        }
        unset($this->instances[$brand->getId()]);
        return $brand;
    }

    /**
     * @inheritdoc
     */
    public function get($brandId, $storeId = null)
    {
        $cacheKey = null !== $storeId ? $storeId : 'all';
        if (!isset($this->instances[$brandId][$cacheKey])) {
            /** @var Brand $brand */
            $brand = $this->brandFactory->create();
            if (null !== $storeId) {
                $brand->setStoreId($storeId);
            }
            $brand->load($brandId);
            if (!$brand->getId()) {
                throw NoSuchEntityException::singleField('id', $brandId);
            }
            $this->instances[$brandId][$cacheKey] = $brand;
        }
        return $this->instances[$brandId][$cacheKey];
    }

    /**
     * @inheritdoc
     */
    public function delete(\Ajay\Brand\Api\Data\BrandInterface $brand)
    {
        try {
            $brandId = $brand->getId();
            $this->resourceModel->delete($brand);
        } catch (\Exception $e) {
            throw new \Magento\Framework\Exception\StateException(
                __(
                    'Cannot delete brand with id %1',
                    $brand->getId()
                ),
                $e
            );
        }
        unset($this->instances[$brandId]);
        return true;
    }

    /**
     * @inheritdoc
     */
    public function deleteById($id)
    {
        $brand = $this->get($id);
        return $this->delete($brand);
    }

    /**
     * @inheritdoc
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria)
    {
        // TODO: Implement getList method
        throw new \BadMethodCallException(__CLASS__.'::'.__METHOD__.' has not been implemented yet');
    }
}