<?php
/**
 * Customer attribute data helper
 */

namespace Ajay\Brand\Helper;

class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * Return information array of customer attribute input types
     *
     * @param string $inputType
     * @return array
     */
    public function getAttributeInputTypes($inputType = null)
    {
        $inputTypes = [
            'multiselect' => ['backend_model' => 'Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend',
                               'source_model' => 'Magento\Eav\Model\Entity\Attribute\Source\Table'],
            'boolean' => ['source_model' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean'],
            'file' => ['backend_model' => 'Ajay\Brand\Model\Attribute\Backend\File'],
            'image' => ['backend_model' => 'Ajay\Brand\Model\Attribute\Backend\Image'],
        ];

        if ($inputType === null) {
            return $inputTypes;
        } else {
            if (isset($inputTypes[$inputType])) {
                return $inputTypes[$inputType];
            }
        }
        return [];
    }
    
    /**
     * Return default attribute backend model by input type
     *
     * @param string $inputType
     * @return string|null
     */
    public function getAttributeBackendModelByInputType($inputType)
    {
        $inputTypes = $this->getAttributeInputTypes();
        
        if (!empty($inputTypes[$inputType]['backend_model'])) {
            return $inputTypes[$inputType]['backend_model'];
        }
        return null;
    }
    
    /**
     * Return default attribute source model by input type
     *
     * @param string $inputType
     * @return string|null
     */
    public function getAttributeSourceModelByInputType($inputType)
    {
        $inputTypes = $this->getAttributeInputTypes();
        if (!empty($inputTypes[$inputType]['source_model'])) {
            return $inputTypes[$inputType]['source_model'];
        }
        return null;
    }
}
