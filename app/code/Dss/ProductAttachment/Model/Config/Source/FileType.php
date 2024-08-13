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
namespace Dss\ProductAttachment\Model\Config\Source;

use Magento\Framework\Data\OptionSourceInterface;

class FileType implements OptionSourceInterface
{
    /**
     * @var array|null
     */
    protected $_options;

    /**
     * Get all options
     *
     * @param bool $withEmpty
     * @return array
     */
    public function getAllOptions($withEmpty = false): array
    {
        if ($this->_options === null) {
            foreach ($this->getTypes() as $type) {
                $this->_options[] = ['value' => $type, 'label' => '.' . $type];
            }
        }
        $options = $this->_options;
        if ($withEmpty) {
            array_unshift($options, ['value' => '', 'label' => '']);
        }
        return $options;
    }

    /**
     * Get file types
     *
     * @return array
     */
    public function getTypes(): array
    {
        return ['pdf', 'doc', 'docx', 'ppt', 'txt', 'jpg', 'jpeg', 'gif', 'png', 'zip', 'tar', 'gz'];
    }

    /**
     * Get options as key-value pairs
     *
     * @param bool $withEmpty
     * @return array
     */
    public function getOptionsArray($withEmpty = true): array
    {
        $options = [];
        foreach ($this->getAllOptions($withEmpty) as $option) {
            $options[$option['value']] = $option['label'];
        }
        return $options;
    }

    /**
     * Get option label by value
     *
     * @param string $value
     * @return string|false
     */
    public function getOptionText($value)
    {
        $options = $this->getAllOptions(false);
        foreach ($options as $item) {
            if ($item['value'] == $value) {
                return $item['label'];
            }
        }
        return false;
    }

    /**
     * Get options as array
     *
     * @return array
     */
    public function toOptionArray(): array
    {
        return $this->getAllOptions();
    }

    /**
     * Get options as key-value pairs
     *
     * @param bool $withEmpty
     * @return array
     */
    public function toOptionHash($withEmpty = true): array
    {
        return $this->getOptionsArray($withEmpty);
    }
}
