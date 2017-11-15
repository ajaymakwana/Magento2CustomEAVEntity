<?php

namespace Ajay\Brand\Api\Data;

/**
 * @api
 */
interface BrandAttributeInterface extends \Ajay\Brand\Api\Data\EavAttributeInterface
{
    /**#@+
     * Constants defined for keys of  data array
     */
    const ENTITY_TYPE_CODE = 'ajay_brand';

    const NAME = 'name';
    
    const DESCRIPTION = 'description';

    const CREATED_AT = 'created_at';

    const UPDATED_AT = 'updated_at';

    
}
