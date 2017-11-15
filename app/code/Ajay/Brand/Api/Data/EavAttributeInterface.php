<?php
/**
 *
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ajay\Brand\Api\Data;

/**
 * @api
 */
interface EavAttributeInterface extends \Magento\Eav\Api\Data\AttributeInterface
{
    const IS_WYSIWYG_ENABLED = 'is_wysiwyg_enabled';

    const IS_HTML_ALLOWED_ON_FRONT = 'is_html_allowed_on_front';

    const USED_FOR_SORT_BY = 'used_for_sort_by';

    const IS_FILTERABLE = 'is_filterable';

    const IS_FILTERABLE_IN_SEARCH = 'is_filterable_in_search';

    const IS_USED_IN_GRID = 'is_used_in_grid';

    const IS_VISIBLE_IN_GRID = 'is_visible_in_grid';

    const IS_FILTERABLE_IN_GRID = 'is_filterable_in_grid';

    const POSITION = 'position';

    const APPLY_TO = 'apply_to';

    const IS_SEARCHABLE = 'is_searchable';

    const IS_VISIBLE_IN_ADVANCED_SEARCH = 'is_visible_in_advanced_search';

    const IS_COMPARABLE = 'is_comparable';

    const IS_USED_FOR_PROMO_RULES = 'is_used_for_promo_rules';

    const IS_VISIBLE_ON_FRONT = 'is_visible_on_front';

    const USED_IN_PRODUCT_LISTING = 'used_in_product_listing';

    const IS_VISIBLE = 'is_visible';

    const SCOPE_STORE_TEXT = 'store';

    const SCOPE_GLOBAL_TEXT = 'global';

    const SCOPE_WEBSITE_TEXT = 'website';

    /**
     * Whether attribute is visible on frontend.
     *
     * @return bool|null
     */
    public function getIsVisible();

    /**
     * Set whether attribute is visible on frontend.
     *
     * @param bool $isVisible
     * @return $this
     */
    public function setIsVisible($isVisible);

    /**
     * Retrieve attribute scope
     *
     * @return string|null
     */
    public function getScope();

    /**
     * Set attribute scope
     *
     * @param string $scope
     * @return $this
     */
    public function setScope($scope);
}
