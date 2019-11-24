<?php

namespace Likemusic\SaveSize\Model\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Likemusic\SaveSize\Api\Model\Config\ProviderInterface;

class Provider implements ProviderInterface
{
    /** @var ScopeConfigInterface */
    private $scopeConfig;

    const CONFIG_PATH_SIZE_ATTRIBUTE_CODE = 'catalog/layered_navigation/size_attribute_code';

    public function __construct(ScopeConfigInterface $scopeConfig)
    {
        $this->scopeConfig = $scopeConfig;
    }

    public function getSizeAttributeCode()
    {
        return $this->getDefaultConfig(self::CONFIG_PATH_SIZE_ATTRIBUTE_CODE);
    }

    private function getDefaultConfig(string $configPath)
    {
        return $this->scopeConfig->getValue($configPath);
    }
}
