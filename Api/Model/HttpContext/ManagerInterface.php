<?php

namespace Likemusic\SaveSize\Api\Model\HttpContext;

interface ManagerInterface
{
    public function set($attributeValue);
    public function unset();
    public function setDefaultNotSetValue();
}
