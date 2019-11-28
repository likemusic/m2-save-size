<?php

namespace Likemusic\SaveSize\Plugin\Magento\Framework\App\Action;

use Likemusic\SaveSize\Api\Model\Session\ManagerInterface as SessionManagerInterface;
use Magento\Framework\App\Action\AbstractAction;
use Magento\Framework\App\RequestInterface;
use Likemusic\SaveSize\Api\Model\HttpContext\ManagerInterface as HttpContextManagerInterface;

class AbstractActionPlugin
{
    /**
     * @var SessionManagerInterface
     */
    protected $sessionManager;

    /**
     * @var HttpContextManagerInterface
     */
    protected $httpContextManager;

    /**
     * @param SessionManagerInterface $sessionManager
     * @param HttpContextManagerInterface $httpContextUpdater
     */
    public function __construct(SessionManagerInterface $sessionManager, HttpContextManagerInterface $httpContextUpdater)
    {
        $this->sessionManager = $sessionManager;
        $this->httpContextManager = $httpContextUpdater;
    }

    /**
     * Set customer group and customer session id to HTTP context
     *
     * @param AbstractAction $subject
     * @param RequestInterface $request
     * @return void
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function beforeDispatch(AbstractAction $subject, RequestInterface $request)
    {
        $savedSizeAttributeValueId = $this->getSessionSizeAttributeValueId();

        if ($savedSizeAttributeValueId !== null) {
            $this->setHttpContextValue($savedSizeAttributeValueId);
        } else {
            $this->setHttpContextDefaultValue();
        }
    }

    private function setHttpContextValue($sizeAttributeValueId)
    {
        $this->httpContextManager->set($sizeAttributeValueId);
    }

    private function setHttpContextDefaultValue()
    {
        $this->httpContextManager->setDefault();
    }

    private function getSessionSizeAttributeValueId()
    {
        return $this->sessionManager->getSizeValueId();
    }
}
