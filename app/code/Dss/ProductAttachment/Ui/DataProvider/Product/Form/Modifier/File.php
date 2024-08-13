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
namespace Dss\ProductAttachment\Ui\DataProvider\Product\Form\Modifier;

use Magento\Framework\Stdlib\ArrayManager;
use Magento\Framework\UrlInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;

class File extends AbstractModifier
{
    /**
     * @param ArrayManager $arrayManager
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        protected ArrayManager $arrayManager,
        protected StoreManagerInterface $storeManager
    ) {
    }

    /**
     * Modify meta information for the product form.
     *
     * @param array $meta
     * @return array
     */
    public function modifyMeta(array $meta): array
    {
        $fieldCode = 'dss_attachment_file';
        $elementPath = $this->arrayManager->findPath($fieldCode, $meta, null, 'children');
        $containerPath = $this->arrayManager->findPath(
            static::CONTAINER_PREFIX . $fieldCode,
            $meta,
            null,
            'children'
        );

        if (!$elementPath) {
            return $meta;
        }

        $mediaUrl = $this->storeManager->getStore()->getBaseUrl(UrlInterface::URL_TYPE_MEDIA);
        $meta = $this->arrayManager->merge(
            $containerPath,
            $meta,
            [
                'children' => [
                    $fieldCode => [
                        'arguments' => [
                            'data' => [
                                'config' => [
                                    'elementTmpl' => 'Dss_ProductAttachment/elements/file',
                                    'media_url' => $mediaUrl
                                ],
                            ],
                        ],
                    ]
                ]
            ]
        );

        return $meta;
    }

    /**
     * Modify data for the product form.
     *
     * @param array $data
     * @return array
     */
    public function modifyData(array $data): array
    {
        return $data;
    }
}
