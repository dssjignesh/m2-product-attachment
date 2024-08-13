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
namespace Dss\ProductAttachment\Test\Unit\Helper;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Dss\ProductAttachment\Model\Config;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Magento\Framework\TestFramework\Unit\Helper\ObjectManager as ObjectManagerHelper;

class ConfigTest extends TestCase
{
    /**
     * @var Config
     */
    protected $model;

    /**
     * @var ScopeConfigInterface|MockObject
     */
    protected $scopeConfigMock;

    protected function setUp(): void
    {
        $this->scopeConfigMock = $this->getMockBuilder(ScopeConfigInterface::class)
            ->setMethods(['getValue', 'isSetFlag'])
            ->disableOriginalConstructor()
            ->getMockForAbstractClass();

        $objectManager = new ObjectManagerHelper($this);
        $this->model = $objectManager->getObject(
            Config::class,
            [
                'scopeConfig' => $this->scopeConfigMock
            ]
        );
    }

    /**
     * Test isEnabled()
     *
     * @return void
     * @dataProvider isEnabledDataProvider
     */
    public function testIsEnabled($isSetFlag, $result): void
    {
        $this->scopeConfigMock->expects($this->once())
            ->method('isSetFlag')
            ->with(Config::XML_PATH_ENABLED, ScopeInterface::SCOPE_STORE)
            ->willReturn($isSetFlag);

        $this->assertEquals($result, $this->model->isEnabled());
    }

    /**
     * Data provider for isEnabled()
     *
     * @return array
     */
    public function isEnabledDataProvider(): array
    {
        return [
            [true, true],
            [false, false]
        ];
    }

    /**
     * Test isDebugEnabled()
     *
     * @return void
     * @dataProvider isEnabledDataProvider
     */
    public function testIsDebugEnabled($isSetFlag, $result): void
    {
        $this->scopeConfigMock->expects($this->once())
            ->method('isSetFlag')
            ->with(Config::XML_PATH_DEBUG, ScopeInterface::SCOPE_STORE)
            ->willReturn($isSetFlag);

        $this->assertEquals($result, $this->model->isDebugEnabled());
    }
}
