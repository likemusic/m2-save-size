<?php

namespace Likemusic\SaveSize\CustomerData;

use Likemusic\SaveSize\Api\Model\Config\ProviderInterface as ConfigProviderInterface;
use Likemusic\SaveSize\Api\Model\Session\ManagerInterface as SessionManagerInterface;
use Magento\Customer\CustomerData\SectionSourceInterface;

class SavedSizeAttribute implements SectionSourceInterface
{
    const KEY_ATTRIBUTE_CODE = 'attribute_code';
    const KEY_VALUE_ID = 'value_id';

    /** @var SessionManagerInterface */
    private $sessionManager;

    /** @var ConfigProviderInterface */
    private $configProvider;

    public function __construct(
        ConfigProviderInterface $configProvider,
        SessionManagerInterface $sessionManager)
    {
        $this->configProvider = $configProvider;
        $this->sessionManager = $sessionManager;
    }

    public function getSectionData()
    {
        return $this->getSavedSizeAttributeArray();
    }

    private function getSavedSizeAttributeArray()
    {
        $sizeAttributeCode = $this->getSizeAttributeCode();
        $sizeAttributeValueId = $this->getSavedAttributeValueId();

        return [
            self::KEY_ATTRIBUTE_CODE => $sizeAttributeCode,
            self::KEY_VALUE_ID => $sizeAttributeValueId
        ];
    }

    private function getSizeAttributeCode()
    {
        return $this->configProvider->getSizeAttributeCode();
    }

    private function getSavedAttributeValueId()
    {
        return $this->sessionManager->getSizeValueId();
    }

}
