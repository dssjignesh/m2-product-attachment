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
namespace Dss\ProductAttachment\Setup\Patch\Data;

use Magento\Catalog\Model\Product;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class AddAttachmentAttributes implements DataPatchInterface
{
    /**
     * @param EavSetupFactory $eavSetupFactory
     * @param ModuleDataSetupInterface $moduleDataSetup
     */
    public function __construct(
        protected EavSetupFactory $eavSetupFactory,
        protected ModuleDataSetupInterface $moduleDataSetup
    ) {
    }

    /**
     * Apply data patch
     */
    public function apply()
    {
        $setup = $this->moduleDataSetup;
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);

        $attributes = [
            'dss_attachment_file' => [
                'label' => 'Attachment File',
                'input' => 'file',
                'backend' => \Dss\ProductAttachment\Model\Product\Attribute\Backend\File::class,
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
            ],
            'dss_attachment_label' => [
                'label' => 'Attachment Label',
                'input' => 'text',
                'backend' => '',
                'global' => ScopedAttributeInterface::SCOPE_STORE,
            ],
        ];

        foreach ($attributes as $code => $attribute) {
            $eavSetup->addAttribute(
                Product::ENTITY,
                $code,
                [
                    'group' => 'Attachments',
                    'type' => 'varchar',
                    'label' => $attribute['label'],
                    'input' => $attribute['input'],
                    'backend' => $attribute['backend'],
                    'frontend' => '',
                    'class' => '',
                    'source' => '',
                    'global' => $attribute['global'],
                    'visible' => true,
                    'required' => false,
                    'user_defined' => true,
                    'default' => '',
                    'searchable' => false,
                    'filterable' => false,
                    'comparable' => false,
                    'unique' => false,
                    'visible_on_front' => false,
                    'used_in_product_listing' => false,
                ]
            );
        }
    }

    /**
     * Retrieve list of dependencies
     *
     * @return array
     */
    public static function getDependencies(): array
    {
        return [];
    }

    /**
     * Retrieve list of aliases
     *
     * @return array
     */
    public function getAliases(): array
    {
        return [];
    }
}
