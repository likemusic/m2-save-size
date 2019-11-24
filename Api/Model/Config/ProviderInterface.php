<?php

namespace Likemusic\SaveSize\Api\Model\Config;

interface ProviderInterface
{
    /**
     * @return string
     */
    public function getSizeAttributeCode();
}
