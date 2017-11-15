<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ajay\Brand\Ui\DataProvider\Brand\Form\Modifier;

use Ajay\Brand\Model\Locator\LocatorInterface;
use Magento\Framework\UrlInterface;

/**
 * Class SystemDataProvider
 */
class System extends AbstractModifier
{
    const KEY_SUBMIT_URL = 'submit_url';
    const KEY_VALIDATE_URL = 'validate_url';
    const KEY_RELOAD_URL = 'reloadUrl';

    /**
     * @var LocatorInterface
     */
    protected $locator;

    /**
     * @var UrlInterface
     */
    protected $urlBuilder;

    /**
     * @var array
     */
    protected $brandUrls = [
        self::KEY_SUBMIT_URL => 'brand/brand/save',
        //self::KEY_VALIDATE_URL => 'brand/brand/validate',
        self::KEY_RELOAD_URL => 'brand/brand/reload'
    ];

    /**
     * @param LocatorInterface $locator
     * @param UrlInterface $urlBuilder
     * @param array $brandUrls
     */
    public function __construct(
        LocatorInterface $locator,
        UrlInterface $urlBuilder,
        array $brandUrls = []
    ) {
        $this->locator = $locator;
        $this->urlBuilder = $urlBuilder;
        $this->brandUrls = array_replace_recursive($this->brandUrls, $brandUrls);
    }

    /**
     * {@inheritdoc}
     */
    public function modifyData(array $data)
    {
        $model = $this->locator->getBrand();
        $attributeSetId = $model->getAttributeSetId();

        $parameters = [
            'id' => $model->getId(),
            'type' => $model->getTypeId(),
            'store' => $model->getStoreId(),
        ];
        $actionParameters = array_merge($parameters, ['set' => $attributeSetId]);
        $reloadParameters = array_merge(
            $parameters,
            [
                'popup' => 1,
                'componentJson' => 1,
                'prev_set_id' => $attributeSetId,
                'type' => $this->locator->getBrand()->getTypeId()
            ]
        );

        $submitUrl = $this->urlBuilder->getUrl($this->brandUrls[self::KEY_SUBMIT_URL], $actionParameters);
        //$validateUrl = $this->urlBuilder->getUrl($this->brandUrls[self::KEY_VALIDATE_URL], $actionParameters);
        $reloadUrl = $this->urlBuilder->getUrl($this->brandUrls[self::KEY_RELOAD_URL], $reloadParameters);

        return array_replace_recursive(
            $data,
            [
                'config' => [
                    self::KEY_SUBMIT_URL => $submitUrl,
                    //self::KEY_VALIDATE_URL => $validateUrl,
                    self::KEY_RELOAD_URL => $reloadUrl,
                ]
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function modifyMeta(array $meta)
    {
        return $meta;
    }
}
