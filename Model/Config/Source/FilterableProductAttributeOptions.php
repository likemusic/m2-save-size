<?php

namespace Likemusic\SaveSize\Model\Config\Source;

use Magento\Catalog\Api\Data\ProductAttributeInterface;
use Magento\Catalog\Model\Layer\FilterableAttributeListInterface;
use Magento\Catalog\Model\ResourceModel\Product\Attribute\Collection as ProductAttributeResourceCollection;
use Magento\Framework\Data\OptionSourceInterface;

class FilterableProductAttributeOptions implements OptionSourceInterface
{
    /** @var FilterableAttributeListInterface */
    private $filterableAttributeList;

    public function __construct(FilterableAttributeListInterface $filterableAttributeList)
    {
        $this->filterableAttributeList = $filterableAttributeList;
    }

    public function toOptionArray()
    {
        $allProductAttributes = $this->getFilterableProductAttributes();

        return $this->convertProductAttributesToOptionArray($allProductAttributes);
        // TODO: Implement toOptionArray() method.
    }

    /**
     * @return ProductAttributeInterface[]|ProductAttributeResourceCollection
     */
    private function getFilterableProductAttributes()
    {
        return $this->filterableAttributeList->getList();
    }

    private function convertProductAttributesToOptionArray($productAttributes)
    {
        $options = [];

        foreach ($productAttributes as $productAttribute) {
            $options[] = $this->getOptionByProductAttribute($productAttribute);
        }

        return $options;
    }

    private function getOptionByProductAttribute(ProductAttributeInterface $productAttribute)
    {
        return [
            'value' => $productAttribute->getAttributeCode(),
            'label' => $productAttribute->getDefaultFrontendLabel(),
        ];
    }
}
