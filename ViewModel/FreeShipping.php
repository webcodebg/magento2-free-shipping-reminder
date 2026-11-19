<?php
/**
 * @package   Webcode_FreeShippingReminder
 * @copyright Copyright © 2021–2026 Webcode (https://webcode.bg/)
 * @license   https://opensource.org/licenses/OSL-3.0 Open Software License (OSL 3.0)
 */
declare(strict_types=1);

namespace Webcode\FreeShippingReminder\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Webcode\FreeShippingReminder\Helper\Data;

/**
 * The free-shipping threshold for theme templates.
 *
 * Hyvä: `$viewModels->require(FreeShipping::class)`. A theme that draws its own
 * "X left for free delivery" bar or a "Free delivery" label on the product page
 * reads the threshold here instead of hard-coding it, so the admin setting
 * (per website) is the only source.
 *
 * getSubtotalThreshold() is 0 whenever the reminder is disabled for the current
 * store or no threshold is configured, so a template hides its bar or label on
 * that one check.
 */
class FreeShipping implements ArgumentInterface
{
    /**
     * @var Data
     */
    private Data $helper;

    /**
     * @param Data $helper
     */
    public function __construct(Data $helper)
    {
        $this->helper = $helper;
    }

    /**
     * Enabled for the current store and a positive threshold is set.
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->getSubtotalThreshold() > 0;
    }

    /**
     * Subtotal from which shipping is free, in the store's base currency; 0 when inactive.
     *
     * @return float
     */
    public function getSubtotalThreshold(): float
    {
        if (!$this->helper->isActive()) {
            return 0.0;
        }

        return max(0.0, (float)$this->helper->getSubtotalThreshold());
    }

    /**
     * The configured reminder text (may be empty).
     *
     * @return string
     */
    public function getMessage(): string
    {
        return $this->helper->getMessage();
    }
}
