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
namespace Dss\ProductAttachment\Model;

use Magento\Catalog\Model\Product;
use Magento\Framework\Filesystem\Driver\File;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;
use Dss\ProductAttachment\Model\Config;

class AttachmentResolver
{
    /**
     * @param StoreManagerInterface $storeManager
     * @param File $file
     * @param Config $config
     */
    public function __construct(
        private StoreManagerInterface $storeManager,
        private File $file,
        private Config $config
    ) {
    }

    /**
     * Retrieves product attachments if they are valid.
     *
     * @param Product $product
     * @return array
     */
    public function getAttachments(Product $product): array
    {
        $attachments = [];
        if ($this->isValidFile($product->getData('dss_attachment_file'))) {
            $attachments[] = $this->prepareAttachment(
                $product->getData('dss_attachment_label'),
                $product->getData('dss_attachment_file')
            );
        }
        return $attachments;
    }

    /**
     * Prepares an attachment array with label and URL.
     *
     * @param string|null $label
     * @param string $file
     * @return array
     */
    private function prepareAttachment($label, $file): array
    {
        return [
            'label' => $this->getAttachmentLabel($label),
            'link' => $this->getAttachmentUrl($file)
        ];
    }

    /**
     * Returns the base media URL.
     *
     * @return string
     */
    public function getMediaUrl(): string
    {
        return $this->storeManager->getStore()
            ->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
    }

    /**
     * Validates if a given file path points to a valid file.
     *
     * @param string|null $file
     * @return bool
     */
    public function isValidFile($file): bool
    {
        if (!is_string($file) || !strlen($file)) {
            return false;
        }
        return $this->file->isFile($this->getAttachmentPath($file));
    }

    /**
     * Returns the full file path for an attachment.
     *
     * @param string $file
     * @return string
     */
    public function getAttachmentPath($file): string
    {
        return $this->storeManager->getStore()
            ->getBaseMediaDir() . '/catalog/product/attachment' . $file;
    }

    /**
     * Returns the URL for an attachment file.
     *
     * @param string $file
     * @return string
     */
    public function getAttachmentUrl($file): string
    {
        return $this->getMediaUrl() . 'catalog/product/attachment' . $file;
    }

    /**
     * Returns the label for an attachment.
     *
     * @param string|null $label
     * @return string
     */
    public function getAttachmentLabel($label): string
    {
        return $label ?? $this->config->getAttachmentDownloadLabel() ?? 'Download';
    }
}
