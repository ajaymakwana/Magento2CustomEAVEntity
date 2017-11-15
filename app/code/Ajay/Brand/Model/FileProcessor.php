<?php
/**
 * Copyright © 2013-2017 Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Ajay\Brand\Model;

use Magento\Customer\Api\AddressMetadataInterface;
use Magento\Customer\Api\CustomerMetadataInterface;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\File\Mime;
use Magento\Framework\Filesystem;
use Magento\Framework\Filesystem\Directory\WriteInterface;
use Magento\Framework\Url\EncoderInterface;
use Magento\Framework\UrlInterface;
use Magento\MediaStorage\Model\File\Uploader;
use Magento\MediaStorage\Model\File\UploaderFactory;

/**
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class FileProcessor
{
    /**
     * Temporary directory name
     */
    const TMP_DIR = 'tmp';

    /**
     * @var WriteInterface
     */
    private $mediaDirectory;

    /**
     * @var UploaderFactory
     */
    private $uploaderFactory;

    /**
     * @var UrlInterface
     */
    private $urlBuilder;

    /**
     * @var EncoderInterface
     */
    private $urlEncoder;

    /**
     * @var array
     */
    private $allowedExtensions = [];

    /**
     * @var Mime
     */
    private $mime;

    /**
     * @param Filesystem $filesystem
     * @param UploaderFactory $uploaderFactory
     * @param UrlInterface $urlBuilder
     * @param EncoderInterface $urlEncoder
     * @param Mime $mime
     * @param array $allowedExtensions
     */
    public function __construct(
        Filesystem $filesystem,
        UploaderFactory $uploaderFactory,
        UrlInterface $urlBuilder,
        EncoderInterface $urlEncoder,
        Mime $mime,
        array $allowedExtensions = []
    ) {
        $this->mediaDirectory = $filesystem->getDirectoryWrite(DirectoryList::MEDIA);
        $this->uploaderFactory = $uploaderFactory;
        $this->urlBuilder = $urlBuilder;
        $this->urlEncoder = $urlEncoder;        
        $this->mime = $mime;
        $this->allowedExtensions = $allowedExtensions;
    }

    /**
     * Retrieve base64 encoded file content
     *
     * @param string $fileName
     * @return string
     */
    public function getBase64EncodedData($fileName)
    {
        $filePath = \Ajay\Brand\Helper\Brand::BASE_MEDIA_PATH . '/' . ltrim($fileName, '/');

        $fileContent = $this->mediaDirectory->readFile($filePath);

        $encodedContent = base64_encode($fileContent);
        return $encodedContent;
    }

    /**
     * Get file statistics data
     *
     * @param string $fileName
     * @return array
     */
    public function getStat($fileName)
    {
        $filePath = \Ajay\Brand\Helper\Brand::BASE_MEDIA_PATH . '/' . ltrim($fileName, '/');

        $result = $this->mediaDirectory->stat($filePath);
        return $result;
    }

    /**
     * Retrieve MIME type of requested file
     *
     * @param string $fileName
     * @return string
     */
    public function getMimeType($fileName)
    {
        $filePath = \Ajay\Brand\Helper\Brand::BASE_MEDIA_PATH . '/' . ltrim($fileName, '/');
        $absoluteFilePath = $this->mediaDirectory->getAbsolutePath($filePath);

        $result = $this->mime->getMimeType($absoluteFilePath);
        return $result;
    }

    /**
     * Check if the file exists
     *
     * @param string $fileName
     * @return bool
     */
    public function isExist($fileName)
    {
        $filePath = \Ajay\Brand\Helper\Brand::BASE_MEDIA_PATH . '/' . ltrim($fileName, '/');

        $result = $this->mediaDirectory->isExist($filePath);
        return $result;
    }

    /**
     * Retrieve customer/index/viewfile action URL
     *
     * @param string $filePath
     * @param string $type
     * @return string
     */
    public function getViewUrl($filePath, $type)
    {
        $viewUrl = '';


            $filePath = \Ajay\Brand\Helper\Brand::BASE_MEDIA_PATH . '/' . ltrim($filePath, '/');
            $viewUrl = $this->urlBuilder->getBaseUrl(['_type' => UrlInterface::URL_TYPE_MEDIA])
                . $this->mediaDirectory->getRelativePath($filePath);



        return $viewUrl;
    }

    /**
     * Save uploaded file to temporary directory
     *
     * @param string $fileId
     * @return \string[]
     * @throws LocalizedException
     */
    public function saveTemporaryFile($fileId)
    {
        /** @var Uploader $uploader */
        $uploader = $this->uploaderFactory->create(['fileId' => $fileId]);
        $uploader->setFilesDispersion(false);
        $uploader->setFilenamesCaseSensitivity(false);
        $uploader->setAllowRenameFiles(true);
        $uploader->setAllowedExtensions($this->allowedExtensions);

        $path = $this->mediaDirectory->getAbsolutePath(
            \Ajay\Brand\Helper\Brand::BASE_MEDIA_PATH . '/' . self::TMP_DIR
        );

        $result = $uploader->save($path);
        if (!$result) {
            throw new LocalizedException(__('File can not be saved to the destination folder.'));
        }

        return $result;
    }



    /**
     * Remove uploaded file
     *
     * @param string $fileName
     * @return bool
     */
    public function removeUploadedFile($fileName)
    {
        $filePath = \Ajay\Brand\Helper\Brand::BASE_MEDIA_PATH . '/' . ltrim($fileName, '/');

        $result = $this->mediaDirectory->delete($filePath);
        return $result;
    }
}
