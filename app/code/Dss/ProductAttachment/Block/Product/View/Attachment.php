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
namespace Dss\ProductAttachment\Block\Product\View;

use Magento\Framework\Registry;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Dss\ProductAttachment\Model\AttachmentResolver;
use Magento\Catalog\Model\Product;
use Dss\ProductAttachment\Helper\Data;

class Attachment extends Template
{
    public const CACHE_TAG = 'dss_productattachment_cache';

    /**
     * @param Context $context
     * @param Registry $registry
     * @param AttachmentResolver $attachmentResolver
     * @param Data $dataHelper
     * @param array $data
     */
    public function __construct(
        Context $context,
        private Registry $registry,
        private AttachmentResolver $attachmentResolver,
        protected Data $dataHelper,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    /**
     * Retrieve attachments associated with the current product.
     *
     * @return array
     */
    public function getAttachments(): array
    {
        return $this->attachmentResolver->getAttachments($this->getProduct());
    }

    /**
     * Retrieve the current product from the registry.
     *
     * @return Product|null
     */
    public function getProduct()
    {
        return $this->registry->registry('current_product');
    }

    /**
     * Retrieve block identities used for caching purposes.
     *
     * @return array
     */
    public function getIdentities(): array
    {
        return [self::CACHE_TAG];
    }

    /**
     * Class Helper::Data
     *
     * @return Data
     */
    public function getHelper(): Data
    {
        return $this->dataHelper;
    }
}
