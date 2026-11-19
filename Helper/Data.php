<?php

namespace Webcode\FreeShippingReminder\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

class Data extends AbstractHelper
{
    /**
     * Path to module active flag.
     */
    private const XML_PATH_ACTIVE = 'webcode_freeshippingreminder/general/active';

    /**
     * Path to module enabled flag. Alternative for active.
     */
    private const XML_PATH_ENABLED = 'webcode_freeshippingreminder/general/enabled';

    private const SHIPPING_METHOD_SUBTOTAL_THRESHOLD = 'carriers/freeshipping/free_shipping_subtotal';

    private const USE_SHIPPING_METHOD_SUBTOTAL = 'webcode_freeshippingreminder/general/use_free_shipping_method_value';

    private const SUBTOTAL_THRESHOLD = 'webcode_freeshippingreminder/general/free_shipping_subtotal';

    private const XML_PATH_MESSAGE = 'webcode_freeshippingreminder/general/message';

    /**
     * @var StoreManagerInterface
     */
    private StoreManagerInterface $storeManager;

    /**
     * @param StoreManagerInterface $storeManager
     * @param Context $context
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        Context $context
    ) {
        $this->storeManager = $storeManager;
        parent::__construct($context);
    }

    /**
     * Check is module active for selected store.
     *
     * @param int|null $storeId
     *
     * @return bool
     */
    public function isActive(?int $storeId = null): bool
    {
        $isActive = $this->scopeConfig->getValue(self::XML_PATH_ACTIVE, ScopeInterface::SCOPE_STORE, $storeId);

        if ($isActive === null) {
            return (bool)$this->scopeConfig->getValue(self::XML_PATH_ENABLED, ScopeInterface::SCOPE_STORE, $storeId);
        }

        return (bool)$isActive;
    }

    /**
     * Get Subtotal Threshold.
     *
     * @return mixed
     */
    public function getSubtotalThreshold()
    {
        if ($this->useShippingMethodSubtotal()) {
            return $this->getFreeShippingMethodSubtotal();
        }

        return $this->getCustomSubtotalThreshold();
    }

    /**
     * Get Custom Threshold which is defined in the admin.
     *
     * @return float
     */
    private function getCustomSubtotalThreshold(): float
    {
        return (float)$this->scopeConfig->getValue(self::SUBTOTAL_THRESHOLD, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Check which subtotal to be used.
     *
     * @return bool
     */
    private function useShippingMethodSubtotal(): bool
    {
        return (bool)$this->scopeConfig->getValue(self::USE_SHIPPING_METHOD_SUBTOTAL, ScopeInterface::SCOPE_STORE);
    }

    /**
     * Get Free Shipping Method threshold.
     *
     * @return mixed
     */
    private function getFreeShippingMethodSubtotal()
    {
        return $this->scopeConfig->getValue(
            self::SHIPPING_METHOD_SUBTOTAL_THRESHOLD,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get the reminder text configured for the store ("%1" is the remaining amount).
     *
     * Empty means the module's translatable default.
     *
     * @param int|null $storeId
     *
     * @return string
     */
    public function getMessage(?int $storeId = null): string
    {
        return trim((string)$this->scopeConfig->getValue(self::XML_PATH_MESSAGE, ScopeInterface::SCOPE_STORE, $storeId));
    }

    /**
     * Get Current Magento Store.
     *
     * @return StoreInterface|null
     */
    public function getCurrentStore(): ?StoreInterface
    {
        try {
            return $this->storeManager->getStore();
        } catch (NoSuchEntityException $e) {
            $this->_logger->alert($e->getMessage());
        }

        return null;
    }
}
