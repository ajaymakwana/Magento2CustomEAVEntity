<?php
/**
 * Copyright © 2017 Ajay Makwana (ajaymakwana.mail@gmail.com). All rights reserved.
 * See LICENSE.txt for license details (http://opensource.org/licenses/osl-3.0.php).
 *
 * Life is a code
 */

namespace Ajay\Brand\Block\Sidebar;

/**
 * Brand tag claud sidebar block
 */
class TagClaud extends \Magento\Framework\View\Element\Template
{
    use Widget;

    /**
     * @var string
     */
    protected $_widgetKey = 'tag_claud';

    /**
     * @var \Ajay\Brand\Model\ResourceModel\Tag\CollectionFactory
     */
    protected $_tagCollectionFactory;

    /**
     * @var \Ajay\Brand\Model\ResourceModel\Tag\Collection
     */
    protected $_tags;

    /**
     * @var int
     */
    protected $_maxCount;

    /**
     * Construct
     *
     * @param \Magento\Framework\View\Element\Context $context
     * @param \Ajay\Brand\Model\ResourceModel\Tag\CollectionFactory $_tagCollectionFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Ajay\Brand\Model\ResourceModel\Tag\CollectionFactory $tagCollectionFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->_tagCollectionFactory = $tagCollectionFactory;
    }

    /**
     * Retrieve tags
     * @return array
     */
    public function getTags()
    {
        if ($this->_tags === null) {
            $this->_tags = $this->_tagCollectionFactory->create();
            $resource = $this->_tags->getResource();
            $this->_tags->getSelect()->joinLeft(
                ['pt' => $resource->getTable('ajay_brand_brand_tag')],
                'main_table.tag_id = pt.tag_id',
                []
            )->joinLeft(
                ['p' => $resource->getTable('ajay_brand_brand')],
                'p.brand_id = pt.brand_id',
                []
            )->joinLeft(
                ['ps' => $resource->getTable('ajay_brand_brand_store')],
                'p.brand_id = ps.brand_id',
                ['count' => 'count(main_table.tag_id)']
            )->group(
                'main_table.tag_id'
            )->where(
                'ps.store_id IN (?)',
                [0, (int)$this->_storeManager->getStore()->getId()]
            );
        }

        return $this->_tags;
    }

    /**
     * Retrieve max tag number
     * @return array
     */
    public function getMaxCount()
    {
        if ($this->_maxCount == null) {
            $this->_maxCount = 0;
            foreach ($this->getTags() as $tag) {
                $count = $tag->getCount();
                if ($count > $this->_maxCount) {
                    $this->_maxCount = $count;
                }
            }
        }
        return $this->_maxCount;
    }

    /**
     * Retrieve tag class
     * @return array
     */
    public function getTagClass($tag)
    {
        $maxCount = $this->getMaxCount();
        $percent = floor(($tag->getCount() / $maxCount) * 100);

        if ($percent < 20) {
            return 'smallest';
        }
        if ($percent >= 20 and $percent < 40) {
            return 'small';
        }
        if ($percent >= 40 and $percent < 60) {
            return 'medium';
        }
        if ($percent >= 60 and $percent < 80) {
            return 'large';
        }
        return 'largest';
    }

    /**
     * Retrieve block identities
     * @return array
     */
    public function getIdentities()
    {
        return [\Magento\Cms\Model\Block::CACHE_TAG . '_brand_tag_claud_widget'  ];
    }

}
