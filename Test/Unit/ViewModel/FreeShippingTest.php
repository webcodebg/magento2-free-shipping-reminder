<?php
/**
 * @package   Webcode_FreeShippingReminder
 * @copyright Copyright © 2021–2026 Webcode (https://webcode.bg/)
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */
declare(strict_types=1);

namespace Webcode\FreeShippingReminder\Test\Unit\ViewModel;

use PHPUnit\Framework\TestCase;
use Webcode\FreeShippingReminder\Helper\Data;
use Webcode\FreeShippingReminder\ViewModel\FreeShipping;

class FreeShippingTest extends TestCase
{
    private function viewModel(bool $active, $threshold): FreeShipping
    {
        $helper = $this->createMock(Data::class);
        $helper->method('isActive')->willReturn($active);
        $helper->method('getSubtotalThreshold')->willReturn($threshold);
        $helper->method('getMessage')->willReturn('msg');

        return new FreeShipping($helper);
    }

    public function testActiveWithThresholdReturnsIt(): void
    {
        $vm = $this->viewModel(true, '79');
        $this->assertSame(79.0, $vm->getSubtotalThreshold());
        $this->assertTrue($vm->isActive());
    }

    public function testDisabledReturnsZeroEvenWithThreshold(): void
    {
        $vm = $this->viewModel(false, '79');
        $this->assertSame(0.0, $vm->getSubtotalThreshold());
        $this->assertFalse($vm->isActive());
    }

    /**
     * The free-shipping carrier's subtotal is empty when unset: no threshold, not "free from 0".
     */
    public function testEnabledWithoutThresholdIsInactive(): void
    {
        foreach ([null, '', '0', -5] as $empty) {
            $vm = $this->viewModel(true, $empty);
            $this->assertSame(0.0, $vm->getSubtotalThreshold(), var_export($empty, true));
            $this->assertFalse($vm->isActive());
        }
    }

    public function testMessageIsPassedThrough(): void
    {
        $this->assertSame('msg', $this->viewModel(true, 79)->getMessage());
    }
}
