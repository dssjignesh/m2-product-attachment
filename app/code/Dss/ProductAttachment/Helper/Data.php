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
namespace Dss\ProductAttachment\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\StoreManagerInterface;
use Dss\ProductAttachment\Logger\Logger as ModuleLogger;
use Dss\ProductAttachment\Model\Config;
use Magento\Framework\UrlInterface;

class Data extends AbstractHelper
{
    /**
     * @param Context $context
     * @param ModuleLogger $moduleLogger
     * @param Config $config
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        Context $context,
        protected ModuleLogger $moduleLogger,
        protected Config $config,
        protected StoreManagerInterface $storeManager
    ) {
        parent::__construct($context);
    }

    /**
     * Get Config Helper
     *
     * @return Config
     */
    public function getConfigHelper(): Config
    {
        return $this->config;
    }

    /**
     * Get Base URL
     *
     * @return string
     */
    public function getBaseUrl(): string
    {
        return $this->storeManager->getStore()->getBaseUrl(
            UrlInterface::URL_TYPE_WEB,
            true
        );
    }

    /**
     * Check if the module is active
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->config->isEnabled();
    }

    /**
     * Logging Utility
     *
     * @param string $message
     * @param bool|false $useSeparator
     */
    public function log($message, $useSeparator = false)
    {
        if ($this->config->isEnabled()
            && $this->config->isDebugEnabled()
        ) {
            if ($useSeparator) {
                $this->moduleLogger->customLog(str_repeat('=', 100));
            }

            $this->moduleLogger->customLog($message);
        }
    }

    /**
     * Get Attachment Tab Label
     *
     * @return string
     */
    public function getAttachmentTabLabel(): string
    {
        return $this->config->getAttachmentTabLabel() ?? 'Attachment';
    }
}
