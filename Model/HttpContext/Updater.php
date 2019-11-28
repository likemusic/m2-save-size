<?php

namespace Likemusic\SaveSize\Model\HttpContext;

use Magento\Framework\App\Http\Context as HttpContext;
use Likemusic\SaveSize\Api\Model\HttpContext\UpdaterInterface;

class Updater implements UpdaterInterface
{
    const CONTEXT_KEY = 'USER_SIZE';

    private $httpContext;

    public function __construct(HttpContext $httpContext)
    {
        $this->httpContext = $httpContext;
    }

    public function update($attributeValue)
    {
        $this->httpContext->setValue(self::CONTEXT_KEY, $attributeValue, null);
    }
}
