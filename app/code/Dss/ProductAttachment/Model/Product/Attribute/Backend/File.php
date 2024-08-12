<?php

declare(strict_types=1);
/**
 * Digit Software Solutions.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 *
 * @category  Dss
 * @package   Dss_ProductAttachment
 * @author    Extension Team
 * @copyright Copyright (c) 2024 Digit Software Solutions. ( https://digitsoftsol.com )
 */
namespace Dss\ProductAttachment\Model\Product\Attribute\Backend;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Eav\Model\Entity\Attribute\Backend\AbstractBackend;
use Magento\Framework\Filesystem;
use Magento\MediaStorage\Model\File\Uploader;
use Magento\MediaStorage\Model\File\UploaderFactory;
use Psr\Log\LoggerInterface;
use Dss\ProductAttachment\Model\Config;

class File extends AbstractBackend
{
    /**
     * @var Filesystem\Driver\File
     */
    protected $_file;

    /**
     * @var LoggerInterface
     */
    protected $_logger;

    /**
     * @var Filesystem
     */
    protected $_filesystem;

    /**
     * @var UploaderFactory
     */
    protected $_fileUploaderFactory;

    /**
     * @var Config
     */
    protected $config;

    /**
     * @param LoggerInterface $logger
     * @param Filesystem $filesystem
     * @param Filesystem\Driver\File $file
     * @param UploaderFactory $fileUploaderFactory
     * @param Config $config
     */
    public function __construct(
        LoggerInterface $logger,
        Filesystem $filesystem,
        Filesystem\Driver\File $file,
        UploaderFactory $fileUploaderFactory,
        Config $config
    ) {
        $this->_file = $file;
        $this->_filesystem = $filesystem;
        $this->_fileUploaderFactory = $fileUploaderFactory;
        $this->_logger = $logger;
        $this->config = $config;
    }

    /**
     * Save file after saving the product
     *
     * @param \Magento\Catalog\Model\Product $object
     * @return $this
     */
    public function afterSave($object)
    {
        $path = $this->_filesystem->getDirectoryRead(
            DirectoryList::MEDIA
        )->getAbsolutePath(
            'catalog/product/attachment/'
        );
        $delete = $object->getData($this->getAttribute()->getName() . '_delete');

        if ($delete) {
            $fileName = $object->getData($this->getAttribute()->getName());
            $object->setData($this->getAttribute()->getName(), '');
            $this->getAttribute()->getEntity()->saveAttribute($object, $this->getAttribute()->getName());
            if ($this->_file->isExists($path . $fileName)) {
                $this->_file->deleteFile($path . $fileName);
            }
        }

        $fileData = $this->getFileData($object);

        if (!$fileData) {
            return $this;
        }

        try {
            /** @var $uploader Uploader */
            $uploader = $this->_fileUploaderFactory->create([
                'fileId' => $fileData['fileId']
            ]);
            $uploader->setAllowRenameFiles(true);
            $uploader->setFilesDispersion(true);
            $uploader->setAllowedExtensions($this->config->getAttachmentAllowedExtensions());
            $result = $uploader->save($path);
            $object->setData($this->getAttribute()->getName(), $result['file']);
            $this->getAttribute()->getEntity()->saveAttribute($object, $this->getAttribute()->getName());
        } catch (\Exception $e) {
            if ($e->getCode() != Uploader::TMP_NAME_EMPTY) {
                $this->_logger->critical($e);
            }
        }

        return $this;
    }

    /**
     * Retrieve file data from the request
     *
     * @param \Magento\Catalog\Model\Product $object
     * @return array|null
     */
    private function getFileData($object): ?array
    {
        return [
            'fileId' => 'product[' . $this->getAttribute()->getName() . ']'
        ];
    }
}
