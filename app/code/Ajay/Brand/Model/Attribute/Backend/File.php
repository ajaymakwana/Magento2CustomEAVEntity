<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ajay\Brand\Model\Attribute\Backend;

/**
 * Backend model for attribute with file
 *
 * @author      Magento Core Team <core@magentocommerce.com>
 */
class File extends \Magento\Eav\Model\Entity\Attribute\Backend\AbstractBackend
{
    /**
     * File Uploader factory
     *
     * @var \Magento\MediaStorage\Model\File\UploaderFactory
     */
    protected $_fileUploaderFactory;
    /**
     * Filesystem facade
     *
     * @var \Magento\Framework\Filesystem
     */
    protected $_filesystem;

    /**
     * @param
     * @codeCoverageIgnore
     */
    public function __construct(
        \Ajay\Brand\Helper\Brand $brandHelper,
        \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory,
        \Magento\Framework\Filesystem $filesystem
    )
    {
        $this->_fileUploaderFactory = $fileUploaderFactory;
        $this->_filesystem = $filesystem;
    }
    /**
     * Save uploaded file and set its name to file object
     *
     * @access public
     * @param Varien_Object $object
     * @return null
     * @author Ultimate Module Creator
     */
    public function afterSave($object)
    {
        $value = $object->getData($this->getAttribute()->getName());
        if (is_array($value) && !empty($value['delete'])) {
            $object->setData($this->getAttribute()->getName(), '');
            $this->getAttribute()->getEntity()
                ->saveAttribute($object, $this->getAttribute()->getName());
            return;
        }
        
        try {
            $uploader = $this->_fileUploaderFactory->create(['fileId' => 'image']);
            $uploader->setAllowedExtensions(['jpg', 'jpeg', 'gif', 'png']);
            $uploader->setAllowRenameFiles(false);
            $uploader->setFilesDispersion(false);
            $path = $this->_filesystem->getDirectoryRead(DirectoryList::MEDIA)
                ->getAbsolutePath(\Ajay\Brand\Helper\Brand::BASE_MEDIA_PATH);

            $result = $uploader->save($path);

            $uploader = new Varien_File_Uploader($this->getAttribute()->getName());
            //set allowed file extensions if you need
            //$uploader->setAllowedExtensions(array('mp4', 'mov', 'f4v', 'flv'));
            $uploader->setAllowRenameFiles(true);
            $uploader->setFilesDispersion(true);
            $uploader->save($path);

            $fileName = $uploader->getUploadedFileName();
            if ($fileName) {
                $object->setData($this->getAttribute()->getName(), $fileName);
                $this->getAttribute()->getEntity()->saveAttribute($object, $this->getAttribute()->getName());
            }

            $object->setData($this->getAttribute()->getName(), $result['file']);
            $this->getAttribute()->getEntity()->saveAttribute($object, $this->getAttribute()->getName());

        } catch (\Exception $e) {
            return $this;
        }
    }
}
