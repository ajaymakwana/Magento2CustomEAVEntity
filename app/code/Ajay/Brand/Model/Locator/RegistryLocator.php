<?php
/**
 * DISCLAIMER
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future.
 *
 * @category  Smile
 * @package   Smile\Offer
 * @author    Romain Ruaud <romain.ruaud@smile.fr>
 * @copyright 2016 Smile
 * @license   Open Software License ("OSL") v. 3.0
 */
namespace Ajay\Brand\Model\Locator;

use Magento\Framework\Exception\NotFoundException;
use Magento\Framework\Registry;
use Ajay\Brand\Api\Data\BrandInterface;

/**
 * Registry Locator for Brand
 *
 * @category Ajay
 * @package  Ajay\Brand
 * @author   Ajay Makwana
 */
class RegistryLocator implements LocatorInterface
{
    /**
     * @var Registry
     */
    private $registry;

    /**
     * @var BrandInterface
     */
    private $brand;
    /**
     * @var CategoryInterface
     */
    private $category;
    /**
     * @var StoreInterface
     */
    private $store;
    /**
     * @param Registry $registry The application registry
     */
    public function __construct(Registry $registry)
    {
        $this->registry = $registry;
    }

    /**
     * {@inheritdoc}
     *
     * @throws NotFoundException
     */
    public function getBrand()
    {
        if (null !== $this->brand) {
            return $this->brand;
        }

        if ($this->registry->registry('current_brand')) {
            return $this->brand = $this->registry->registry('current_brand');
        }

        throw new NotFoundException(__('Brand was not registered'));
    }

    /**
     * {@inheritdoc}
     * @throws NotFoundException
     */
    public function getStore()
    {
        if (null !== $this->store) {
            return $this->store;
        }

        if ($store = $this->registry->registry('current_store')) {
            return $this->store = $store;
        }

        throw new NotFoundException(__('Store was not registered'));
    }

    /**
     * {@inheritdoc}
     *
     * @throws NotFoundException
     */
    public function getBrandCategory()
    {
        if (null !== $this->category) {
            return $this->category;
        }

        if ($this->registry->registry('current_category')) {
            return $this->category = $this->registry->registry('current_category');
        }

        throw new NotFoundException(__('Category was not registered'));
    }

    /**
     * {@inheritdoc}
     */
    public function getCategoryWebsiteIds()
    {
        return $this->getBrandCategory()->getWebsiteIds();
    }

    /**
     * {@inheritdoc}
     */
    public function getBrandWebsiteIds()
    {
        return $this->getBrand()->getWebsiteIds();
    }
}
