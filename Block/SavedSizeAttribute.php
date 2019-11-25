<?php

namespace Likemusic\SaveSize\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Likemusic\SaveSize\ViewModel\SavedSizeAttribute as SavedSizeAttributeViewModel;

class SavedSizeAttribute extends Template
{
    protected $_isScopePrivate = true;

    protected $_template = 'Likemusic_SaveSize::saved-size-attribute.phtml';

    /** @var  */
    private $viewModel;

    public function __construct(
        Context $context,
        SavedSizeAttributeViewModel $viewModel,
        array $data = [])
    {
        $this->viewModel = $viewModel;

        parent::__construct($context, $data);
    }

    public function getViewModel()
    {
        return $this->viewModel;
    }
}
