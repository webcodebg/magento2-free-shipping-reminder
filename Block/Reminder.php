<?php
/*
 * @package      Webcode_Magento2
 *
 * @author       Kostadin Bashev (bashev@webcode.bg)
 * @copyright    Copyright © 2020 Webcode Ltd. (https://webcode.bg/)
 * @license      See LICENSE.txt for license details.
 */

namespace Webcode\FreeShippingReminder\Block;

use Exception;
use Magento\Framework\View\Element\Template;
use Webcode\FreeShippingReminder\Helper\Data;

class Reminder extends Template
{
    /**
     * @var Data
     */
    private $helper;

    /**
     * @var \Magento\Tax\Helper\Data
     */
    private \Magento\Tax\Helper\Data $taxHelper;

    /**
     * Sidebar constructor.
     *
     * @param Template\Context $context
     * @param \Magento\Tax\Helper\Data $taxHelper
     * @param Data $helper
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        \Magento\Tax\Helper\Data $taxHelper,
        Data $helper,
        array $data = []
    ) {
        $this->helper = $helper;
        $this->taxHelper = $taxHelper;
        parent::__construct($context, $data);
    }

    /**
     * Get free shipping subtotal.
     *
     * @return float
     * @throws Exception
     */
    public function getFreeShippingSubtotal(): float
    {
        if (!$this->helper->isActive()) {
            return 0;
        }

        return $this->helper->getSubtotalThreshold() ?: 0;
    }

    /**
     * Pass the configured message to the JS component.
     *
     * @return string
     */
    public function getJsLayout()
    {
        $message = $this->helper->getMessage();
        if ($message !== '' && isset($this->jsLayout['components']['cart-free-shipping-reminder'])) {
            $this->jsLayout['components']['cart-free-shipping-reminder']['config']['message'] = $message;
        }

        return parent::getJsLayout();
    }

    /**
     * Get PRice format based on Tax and current store.
     * @return string
     */
    public function getPriceFormat(): string
    {
        return $this->taxHelper->getPriceFormat($this->helper->getCurrentStore());
    }
}
