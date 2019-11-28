<?php

namespace Likemusic\SaveSize\Model\HttpContext;

use Magento\Framework\App\Http\Context as HttpContext;
use Likemusic\SaveSize\Api\Model\HttpContext\ManagerInterface;

class Manager implements ManagerInterface
{
    const CONTEXT_KEY = 'lm_ss_size';
    const DEFAULT_VALUE = 0;
    const DEFAULT_NOT_SET = -1;

    private $httpContext;

    public function __construct(HttpContext $httpContext)
    {
        $this->httpContext = $httpContext;
    }

    public function set($attributeValue)
    {
        $this->httpContext->setValue(self::CONTEXT_KEY, $attributeValue, self::DEFAULT_VALUE);
    }

    public function unset()
    {
        $this->httpContext->unsValue(self::CONTEXT_KEY);
    }

    public function setDefaultNotSetValue()
    {
        $this->set(self::DEFAULT_NOT_SET);
    }
}
