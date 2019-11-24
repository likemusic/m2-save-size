<?php

namespace Likemusic\SaveSize\Plugin\Magento\CatalogSearch\Model\Layer\Filter;

use Magento\CatalogSearch\Model\Layer\Filter\Attribute as AttributeFilter;
use Magento\Framework\App\RequestInterface;
use Likemusic\SaveSize\Api\Model\Config\ProviderInterface as ConfigProviderInterface;
use Likemusic\SaveSize\Api\Model\Session\ManagerInterface as SessionManagerInterface;

class AttributePlugin
{
    /** @var ConfigProviderInterface  */
    private $configProvider;

    /** @var SessionManagerInterface */
    private $sessionManager;

    public function __construct(
        ConfigProviderInterface $configProvider,
        SessionManagerInterface $sessionManager
    )
    {
        $this->configProvider = $configProvider;
        $this->sessionManager = $sessionManager;
    }

    public function beforeApply(AttributeFilter $subject, RequestInterface $request)
    {
        if (!$this->isUpdateSessionStoredSizeRequired($subject, $request)) {
            return null;
        }

        $this->updateSessionStoredSize($subject, $request);
    }

    private function updateSessionStoredSize(AttributeFilter $attributeFilter, RequestInterface $request)
    {
        $attributeValue = $this->getAttributeFilterValueByRequest($attributeFilter, $request);

        $this->setSessionSizeValueId($attributeValue);
    }

    private function setSessionSizeValueId(int $attributeValueId)
    {
        $this->sessionManager->setSizeValueId($attributeValueId);
    }

    private function isUpdateSessionStoredSizeRequired(AttributeFilter $attributeFilter, RequestInterface $request)
    {
        return $this->isSizeAttributeFilter($attributeFilter)
            && $this->isFilterUsedInRequest($attributeFilter, $request);
    }

    private function isFilterUsedInRequest(AttributeFilter $attributeFilter, RequestInterface $request)
    {
        $attributeValue = $this->getAttributeFilterValueByRequest($attributeFilter, $request);

        if (empty($attributeValue) && !is_numeric($attributeValue)) {
            return false;
        }

        return true;
    }

    private function getAttributeFilterValueByRequest(AttributeFilter $attributeFilter, RequestInterface $request)
    {
        $requestVar = $attributeFilter->getRequestVar();

        return $request->getParam($requestVar);
    }

    private function isSizeAttributeFilter(AttributeFilter $attributeFilter)
    {
        $sizeAttributeCode = $this->getConfigSizeAttributeCode();
        $filterAttributeCode = $this->getAttributeCodeByFilter($attributeFilter);

        return $sizeAttributeCode == $filterAttributeCode;
    }

    private function getConfigSizeAttributeCode()
    {
        return $this->configProvider->getSizeAttributeCode();
    }

    private function getAttributeCodeByFilter(AttributeFilter $attributeFilter)
    {
        return $attributeFilter->getAttributeModel()->getAttributeCode();
    }
}
