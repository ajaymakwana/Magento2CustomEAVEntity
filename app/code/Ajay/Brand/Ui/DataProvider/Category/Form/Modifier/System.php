<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ajay\Brand\Ui\DataProvider\Category\Form\Modifier;

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
    protected $categoryUrls = [
        self::KEY_SUBMIT_URL => 'brand/category/save',
        //self::KEY_VALIDATE_URL => 'brand/brand/validate',
        self::KEY_RELOAD_URL => 'brand/category/reload'
    ];

    /**
     * @param LocatorInterface $locator
     * @param UrlInterface $urlBuilder
     * @param array $categoryUrls
     */
    public function __construct(
        LocatorInterface $locator,
        UrlInterface $urlBuilder,
        array $categoryUrls = []
    ) {
        $this->locator = $locator;
        $this->urlBuilder = $urlBuilder;
        $this->categoryUrls = array_replace_recursive($this->categoryUrls, $categoryUrls);
    }

    /**
     * {@inheritdoc}
     */
    public function modifyData(array $data)
    {
        $model = $this->locator->getBrandCategory();
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
                'type' => $this->locator->getBrandCategory()->getTypeId()
            ]
        );

        $submitUrl = $this->urlBuilder->getUrl($this->categoryUrls[self::KEY_SUBMIT_URL], $actionParameters);
        //$validateUrl = $this->urlBuilder->getUrl($this->brandUrls[self::KEY_VALIDATE_URL], $actionParameters);
        $reloadUrl = $this->urlBuilder->getUrl($this->categoryUrls[self::KEY_RELOAD_URL], $reloadParameters);

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
