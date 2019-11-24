<?php

namespace Likemusic\SaveSize\Model\Config\Source\ProductAttribute;

use Magento\Catalog\Api\Data\ProductAttributeInterface;
use Magento\Catalog\Api\Data\ProductAttributeSearchResultsInterface;
use Magento\Catalog\Api\ProductAttributeRepositoryInterface;
use Magento\Framework\Api\SearchCriteria;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Data\OptionSourceInterface;

class Options implements OptionSourceInterface
{
    /** @var ProductAttributeRepositoryInterface */
    private $productAttributeRepository;

    /** @var */
    private $searchCriteriaBuilder;

    public function __construct(
        ProductAttributeRepositoryInterface $productAttributeRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder
    )
    {
        $this->productAttributeRepository = $productAttributeRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    public function toOptionArray()
    {
        $allProductAttributes = $this->getAllProductAttributes();

        return $this->convertProductAttributesToOptionArray($allProductAttributes);
        // TODO: Implement toOptionArray() method.
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

    /**
     * @return ProductAttributeInterface[]
     */
    private function getAllProductAttributes()
    {
        $searchCriteria = $this->createSearchCriteria();

        return $this->getProductAttributesBySearchCriteria($searchCriteria);
    }

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return ProductAttributeInterface[]
     */
    private function getProductAttributesBySearchCriteria(SearchCriteriaInterface $searchCriteria)
    {
        $searchResults = $this->getProductAttributeSearchResults($searchCriteria);

        return $searchResults->getItems();
    }

    /**
     * @return SearchCriteria
     */
    private function createSearchCriteria()
    {
        return $this->searchCriteriaBuilder->create();
    }

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return ProductAttributeSearchResultsInterface
     */
    private function getProductAttributeSearchResults(SearchCriteriaInterface $searchCriteria)
    {
        return $this->productAttributeRepository->getList($searchCriteria);
    }

}
