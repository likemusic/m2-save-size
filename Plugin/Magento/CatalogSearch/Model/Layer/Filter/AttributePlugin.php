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

    public function afterApply(AttributeFilter $subject, AttributeFilter $result, RequestInterface $request)
    {
        if (!$this->isSizeAttributeFilter($subject)) {
            return $result;
        }

        if ($this->isFilterUsedInRequest($subject, $request)) {
            $this->updateSessionStoredSize($subject, $request);
        } elseif ($this->isSessionStoredSizeExists()) {
            $this->applyFilterBySessionStoredSize($subject, $request);
        }

        return $result;
    }

    private function applyFilterBySessionStoredSize(AttributeFilter $attributeFilter, RequestInterface $request)
    {
        $attributeValue = $this->getSessionSizeValueId();
        $requestVar = $attributeFilter->getRequestVar();

        $requestClone = clone $request;

        $requestParams = $request->getParams();
        $requestParams[$requestVar] = $attributeValue;
        $requestClone->setParams($requestParams);

        return $attributeFilter->apply($requestClone);
    }

    private function isSessionStoredSizeExists()
    {
        $sessionSizeValueId = $this->getSessionSizeValueId();

        return $sessionSizeValueId !== null;
    }

    private function getSessionSizeValueId()
    {
        return $this->sessionManager->getSizeValueId();
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
