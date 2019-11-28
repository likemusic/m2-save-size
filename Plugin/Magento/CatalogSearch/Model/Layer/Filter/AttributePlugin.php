<?php

namespace Likemusic\SaveSize\Plugin\Magento\CatalogSearch\Model\Layer\Filter;

use Likemusic\SaveSize\Api\Model\Config\ProviderInterface as ConfigProviderInterface;
use Likemusic\SaveSize\Api\Model\HttpContext\UpdaterInterface as HttpContextUpdaterInterface;
use Likemusic\SaveSize\Api\Model\Session\ManagerInterface as SessionManagerInterface;
use Magento\CatalogSearch\Model\Layer\Filter\Attribute as AttributeFilter;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\HTTP\PhpEnvironment\Request;

class AttributePlugin
{
    const UNSET_ATTRIBUTE_VALUE = 'null';

    /** @var ConfigProviderInterface */
    private $configProvider;

    /** @var SessionManagerInterface */
    private $sessionManager;

    /** @var HttpContextUpdaterInterface */
    private $httpContextUpdater;

    public function __construct(
        ConfigProviderInterface $configProvider,
        SessionManagerInterface $sessionManager,
        HttpContextUpdaterInterface $httpContextUpdater
    )
    {
        $this->configProvider = $configProvider;
        $this->sessionManager = $sessionManager;
        $this->httpContextUpdater = $httpContextUpdater;
    }

    public function afterGetResetValue(AttributeFilter $subject, $result)
    {
        return $this->afterGetResetOrCleanValue($subject, $result);
    }

    private function afterGetResetOrCleanValue(AttributeFilter $subject, $result)
    {
        if (!$this->isSizeAttributeFilter($subject)) {
            return $result;
        }

        return self::UNSET_ATTRIBUTE_VALUE;
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

    public function afterGetCleanValue(AttributeFilter $subject, $result)
    {
        return $this->afterGetResetOrCleanValue($subject, $result);
    }

    public function beforeApply(AttributeFilter $subject, RequestInterface $request)
    {
        if (!$this->isSizeAttributeFilter($subject)) {
            return null;
        }

        if (!$this->isUnsetSizeRequested($subject, $request)) {
            return null;
        }

        $this->unsetSessionSizeAttributeValueId();
        $this->unsetRequestValue($subject, $request);

        return null;
    }

    private function isUnsetSizeRequested(AttributeFilter $subject, RequestInterface $request)
    {
        $attributeFilterValue = $this->getAttributeFilterValueByRequest($subject, $request);

        return $attributeFilterValue === self::UNSET_ATTRIBUTE_VALUE;
    }

    private function getAttributeFilterValueByRequest(AttributeFilter $attributeFilter, RequestInterface $request)
    {
        $requestVar = $attributeFilter->getRequestVar();

        return $request->getParam($requestVar);
    }

    private function unsetSessionSizeAttributeValueId()
    {
        $this->sessionManager->unsetSizeValueId();
    }

    private function unsetRequestValue(AttributeFilter $subject, RequestInterface $request)
    {
        $requestParamName = $subject->getRequestVar();
        $request->setParams([$requestParamName => null]);
        // It doesn't work. Possible M2 bug? @see \Magento\Framework\HTTP\PhpEnvironment\Request::setParam()

        if ($request instanceof Request) {
            $request->setQueryValue($requestParamName, null);
        }
    }

    public function afterApply(AttributeFilter $subject, AttributeFilter $result, RequestInterface $request)
    {
        if (!$this->isSizeAttributeFilter($subject)) {
            return $result;
        }

        if ($this->isFilterUsedInRequest($subject, $request)) {
            $attributeValue = $this->getAttributeFilterValueByRequest($subject, $request);
            $this->setSessionSizeValueId($attributeValue);
            $this->updateHttpContext($attributeValue);
        } elseif ($this->isSessionStoredSizeExists()) {
            $attributeValue = $this->getSessionSizeValueId();
            $this->applyFilterBySessionStoredSize($subject, $request, $attributeValue);
        }

        return $result;
    }

    private function isFilterUsedInRequest(AttributeFilter $attributeFilter, RequestInterface $request)
    {
        $attributeValue = $this->getAttributeFilterValueByRequest($attributeFilter, $request);

        if (empty($attributeValue) && !is_numeric($attributeValue)) {
            return false;
        }

        return true;
    }

    private function setSessionSizeValueId(int $attributeValueId)
    {
        $this->sessionManager->setSizeValueId($attributeValueId);
    }

    private function updateHttpContext($attributeValue)
    {
        $this->httpContextUpdater->update($attributeValue);
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

    private function applyFilterBySessionStoredSize(
        AttributeFilter $attributeFilter,
        RequestInterface $request,
        $attributeValue)
    {
        $requestVar = $attributeFilter->getRequestVar();

        $requestClone = clone $request;

        $requestParams = $request->getParams();
        $requestParams[$requestVar] = $attributeValue;
        $requestClone->setParams($requestParams);

        return $attributeFilter->apply($requestClone);
    }
}
