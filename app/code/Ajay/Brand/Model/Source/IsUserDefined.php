<?php
/**
 * Used in creating options for Yes|No config value selection
 *
 */

namespace Ajay\Brand\Model\Source;

class IsUserDefined implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
         return array(
            array('value' => 0, 'label'=>'Yes'),
            array('value' => 1, 'label'=>'No'),
        );
    }
}