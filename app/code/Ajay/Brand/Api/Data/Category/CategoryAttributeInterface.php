<?php

namespace Ajay\Brand\Api\Data\Category;

/**
 * @api
 */
interface CategoryAttributeInterface extends \Ajay\Brand\Api\Data\EavAttributeInterface
{
    /**#@+
     * Constants defined for keys of  data array
     */
    const ENTITY_TYPE_CODE = 'ajay_brand_category';

    const NAME = 'name';
    
    const DESCRIPTION = 'description';

    const CREATED_AT = 'created_at';

    const UPDATED_AT = 'updated_at';

    
}
