<?php

namespace Likemusic\SaveSize\Model\HttpContext;

use Magento\Framework\App\Http\Context as HttpContext;
use Likemusic\SaveSize\Api\Model\HttpContext\ManagerInterface;

class Manager implements ManagerInterface
{
    const CONTEXT_KEY = 'lm_ss_size';

    private $httpContext;

    public function __construct(HttpContext $httpContext)
    {
        $this->httpContext = $httpContext;
    }

    public function set($attributeValue)
    {
        $this->httpContext->setValue(self::CONTEXT_KEY, $attributeValue, 0);
    }

    public function unset()
    {
        $this->httpContext->unsValue(self::CONTEXT_KEY);
    }
}
