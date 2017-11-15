<?php
namespace Ajay\Brand\Block\Adminhtml\Brand\Edit;

use Magento\Eav\Model\Config;
use Magento\Backend\Block\Template\Context;
use Magento\Backend\Block\Widget\Tabs as WigetTabs;
use Magento\Framework\Json\EncoderInterface;
use Magento\Backend\Model\Auth\Session;
/**
 * Admin Locator left menu.
 * @category Ajay
 * @package  Ajay_Brand
 * @module   Brand
 * @author   Ajay Developer
 */
/**
 * @var Config
 */

class Tabs extends WigetTabs
{
    private $eavConfig;

    /**
     * @return void
     */
    public function __construct(
        Context $context,
        EncoderInterface $jsonEncoder,
        Session $authSession,
        array $data = [],
        Config $eavConfig)
    {
        $this->eavConfig = $eavConfig;
        parent::__construct($context, $jsonEncoder, $authSession, $data);
    }

    protected function _construct()
    {
        parent::_construct();

        $this->setId('brand_tabs');
        $this->setDestElementId('edit_form');
        $this->setTitle(__('Brand Information'));
    }

    /**
     * @return $this
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    protected function _prepareLayout()
    {        
        $entityType = $this->getEavConfig()->getEntityType('ajay_brand_eav_attribute');
        $attributes = $entityType->getAttributeCollection();
        $attributes->getSelect()->order('main_table.attribute_id ' . 'ASC');

        $this->addTab(
            'general',
            [
                'label' => __('Brand Information'),
                'content' =>
                    $this->getLayout()->createBlock(
                        'Ajay\Brand\Block\Adminhtml\Brand\Edit\Tab\Form'
                    )->setAttributes($attributes)->toHtml()
                ,
                'group_code' => 'general'
            ]
        );
        return parent::_beforeToHtml();
    }

    /**
     * Retrieve EAV Config Singleton
     *
     * @return \Magento\Eav\Model\Config
     */
    private function getEavConfig()
    {
        return $this->eavConfig;
    }

}
