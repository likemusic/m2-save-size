<?php

namespace Likemusic\SaveSize\ViewModel;

use Likemusic\SaveSize\Api\Model\Config\ProviderInterface as ConfigProviderInterface;
use Likemusic\SaveSize\Api\Model\Session\ManagerInterface as SessionManagerInterface;

class SavedSizeAttribute
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

    public function getAsJson()
    {
        $savedSizeAttributeArray = $this->getSavedSizeAttributeArray();

        return json_encode($savedSizeAttributeArray);
    }

    private function getSavedSizeAttributeArray()
    {
        $sizeAttributeCode = $this->getSizeAttributeCode();
        $sizeAttributeValueId = $this->getSavedAttributeValueId();

        return [$sizeAttributeCode => $sizeAttributeValueId];
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
