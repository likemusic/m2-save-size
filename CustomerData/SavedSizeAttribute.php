<?php

namespace Likemusic\SaveSize\CustomerData;

use Likemusic\SaveSize\Api\Model\Config\ProviderInterface as ConfigProviderInterface;
use Likemusic\SaveSize\Api\Model\Session\ManagerInterface as SessionManagerInterface;
use Magento\Customer\CustomerData\SectionSourceInterface;

class SavedSizeAttribute implements SectionSourceInterface
{
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
            'attribute_code' => $sizeAttributeCode,
            'value_id' => $sizeAttributeValueId
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
