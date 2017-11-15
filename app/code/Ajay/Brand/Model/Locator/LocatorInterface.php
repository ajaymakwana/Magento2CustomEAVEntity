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

use Ajay\Brand\Api\Data\BrandInterface;
use Ajay\Brand\Api\Data\Category\CategoryInterface;

/**
 * Brand Locator Interface
 *
 * @category Ajay
 * @package  Ajay\Brand
 * @author   Ajay Makwana
 */
interface LocatorInterface
{
    /**
     * @return BrandInterface
     */
    public function getBrand();

    /**
     * @return CategoryInterface
     */
    public function getBrandCategory();

    /**
     * @return array
     */
    public function getCategoryWebsiteIds();

    /**
     * @return array
     */
    public function getBrandWebsiteIds();
}
