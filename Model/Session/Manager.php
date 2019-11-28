<?php

namespace Likemusic\SaveSize\Model\Session;

use Likemusic\SaveSize\Api\Model\Session\ManagerInterface;
use Magento\Framework\App\Request\Http;
use Magento\Framework\Session\Config\ConfigInterface;
use Magento\Framework\Session\SaveHandlerInterface;
use Magento\Framework\Session\SessionManager;
use Magento\Framework\Session\SessionStartChecker;
use Magento\Framework\Session\SidResolverInterface;
use Magento\Framework\Session\StorageInterface;
use Magento\Framework\Session\ValidatorInterface;

class Manager extends SessionManager implements ManagerInterface
{
    const KEY_SIZE_VALUE_ID = 'size_value_id';

    public function setSizeValueId($valueId)
    {
        $this->storage[self::KEY_SIZE_VALUE_ID] = $valueId;

        return $this;
    }

    public function getSizeValueId()
    {
        return $this->getData(self::KEY_SIZE_VALUE_ID);
    }

    public function unsetSizeValueId()
    {
        $this->storage[self::KEY_SIZE_VALUE_ID] = null;

        return $this;
    }
}
