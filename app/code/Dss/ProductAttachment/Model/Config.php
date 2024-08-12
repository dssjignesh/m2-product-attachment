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

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Config implements ConfigInterface
{
    public const XML_PATH_ENABLED = 'dss_productattachment/general/enabled';
    public const XML_PATH_DEBUG = 'dss_productattachment/general/debug';

    public const XML_PATH_ATTACHMENT_TAB_LABEL = 'dss_productattachment/attachment/tab_label';
    public const XML_PATH_ATTACHMENT_DOWNLOAD_LABEL = 'dss_productattachment/attachment/download_label';
    public const XML_PATH_ATTACHMENT_ALLOWED_EXTENSIONS = 'dss_productattachment/attachment/allowed_extensions';

    /**
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        protected ScopeConfigInterface $scopeConfig
    ) {
    }

    /**
     * Get the configuration flag for a given XML path and store ID.
     *
     * @param string $xmlPath
     * @param int|null $storeId
     * @return bool
     */
    public function getConfigFlag($xmlPath, $storeId = null): bool
    {
        return $this->scopeConfig->isSetFlag(
            $xmlPath,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Get the configuration value for a given XML path and store ID.
     *
     * @param string $xmlPath
     * @param int|null $storeId
     * @return string|null
     */
    public function getConfigValue($xmlPath, $storeId = null): ?string
    {
        return $this->scopeConfig->getValue(
            $xmlPath,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Check if the product attachment module is enabled.
     *
     * @param int|null $storeId
     * @return bool
     */
    public function isEnabled($storeId = null): bool
    {
        return $this->getConfigFlag(self::XML_PATH_ENABLED, $storeId);
    }

    /**
     * Check if debug mode is enabled for the product attachment module.
     *
     * @param int|null $storeId
     * @return bool
     */
    public function isDebugEnabled($storeId = null): bool
    {
        return $this->getConfigFlag(self::XML_PATH_DEBUG, $storeId);
    }

    /**
     * Get the label for the product attachment tab.
     *
     * @param int|null $storeId
     * @return string|null
     */
    public function getAttachmentTabLabel($storeId = null): ?string
    {
        return $this->getConfigValue(self::XML_PATH_ATTACHMENT_TAB_LABEL, $storeId);
    }

    /**
     * Get the label for the product attachment download link.
     *
     * @param int|null $storeId
     * @return string|null
     */
    public function getAttachmentDownloadLabel($storeId = null): ?string
    {
        return $this->getConfigValue(self::XML_PATH_ATTACHMENT_DOWNLOAD_LABEL, $storeId);
    }

    /**
     * Get the allowed file extensions for product attachments.
     *
     * @param int|null $storeId
     * @return array
     */
    public function getAttachmentAllowedExtensions($storeId = null): array
    {
        $value = $this->getConfigValue(self::XML_PATH_ATTACHMENT_ALLOWED_EXTENSIONS, $storeId);
        return explode(',', $value);
    }
}
