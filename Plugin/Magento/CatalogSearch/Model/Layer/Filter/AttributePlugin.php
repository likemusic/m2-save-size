<?php

namespace Likemusic\SaveSize\Plugin\Magento\CatalogSearch\Model\Layer\Filter;

use Magento\CatalogSearch\Model\Layer\Filter\Attribute as AttributeFilter;
use Magento\Framework\App\RequestInterface;
use Likemusic\SaveSize\Api\Model\Config\ProviderInterface as ConfigProviderInterface;

class AttributePlugin
{
    private $configProvider;

    public function __construct(ConfigProviderInterface $configProvider)
    {
        $this->configProvider = $configProvider;
    }

    public function beforeApply(AttributeFilter $subject, RequestInterface $request)
    {
        if (!$this->isProcessingRequired($subject)) {
            return null;
        }

        $a = 2+2;
    }

    private function isProcessingRequired(AttributeFilter $subject)
    {
        $sizeAttributeCode = $this->getSizeAttributeCode();
        $filterAttributeCode = $this->getAttributeCodeByFilter($subject);

        return $sizeAttributeCode == $filterAttributeCode;
    }

    private function getSizeAttributeCode()
    {
        return $this->configProvider->getSizeAttributeCode();
    }

    private function getAttributeCodeByFilter(AttributeFilter $attributeFilter)
    {
        return $attributeFilter->getAttributeModel()->getAttributeCode();
    }
}
