<?php
/**
 * @category    M2Commerce Enterprise
 * @package     M2Commerce_SearchAutocomplete
 * @copyright   Copyright (c) 2023 M2Commerce Enterprise
 * @author      dawoodgondaldev@gmail.com
 */

namespace M2Commerce\SearchAutocomplete\Model;

use M2Commerce\SearchAutocomplete\Helper\Data as HelperData;
use Magento\Catalog\Model\Layer\Resolver as LayerResolver;
use Magento\Catalog\Model\Product as ProductModel;
use Magento\Catalog\Model\ResourceModel\Product\Collection as ProductCollection;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\ObjectManagerInterface as ObjectManager;
use Magento\Search\Helper\Data as SearchHelper;
use Magento\Search\Model\QueryFactory;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Search class returns needed search data
 */
class Search
{
    const NAME = 'name';
    const SKU = 'sku';
    const IMAGE = 'image';
    const DESCRIPTION = 'description';
    const URL = 'url';

    /**
     * @var HelperData
     */
    protected $helperData;

    /**
     * @var SearchHelper
     */
    protected $searchHelper;

    /**
     * @var LayerResolver
     */
    protected $layerResolver;

    /**
     * @var ObjectManager
     */
    protected $objectManager;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var QueryFactory
     */
    private $queryFactory;

    /**
     * @param StoreManagerInterface $storeManager
     * @param HelperData $helperData
     * @param SearchHelper $searchHelper
     * @param LayerResolver $layerResolver
     * @param ObjectManager $objectManager
     * @param QueryFactory $queryFactory
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        HelperData            $helperData,
        SearchHelper          $searchHelper,
        LayerResolver         $layerResolver,
        ObjectManager         $objectManager,
        QueryFactory          $queryFactory
    ) {
        $this->storeManager       = $storeManager;
        $this->helperData         = $helperData;
        $this->searchHelper       = $searchHelper;
        $this->layerResolver      = $layerResolver;
        $this->objectManager      = $objectManager;
        $this->queryFactory       = $queryFactory;
    }

    /**
     * @return array
     */
    public function getData()
    {
        $data = [];
        try {
            $data[] = $this->getResponseData();
        } catch (\Exception $exception) {
        }
        return $data;
    }

    /**
     * @return array
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    public function getResponseData()
    {
        $responseData['code'] = 'product';
        $responseData['data'] = [];
        $query = $this->queryFactory->get();
        $queryText = $query->getQueryText();
        $productCollection = $this->getProductCollection($queryText);

        foreach ($productCollection as $product) {
            $responseData['data'][] = $this->getProductData($product);
        }

        $responseData['size'] = $productCollection->getSize();
        $responseData['url']  = ($productCollection->getSize() > 0) ? $this->searchHelper->getResultUrl($queryText) : '';
        $query->saveNumResults($responseData['size']);
        $query->saveIncrementalPopularity();
        return $responseData;
    }

    /**
     * @param string $queryText
     * @return ProductCollection
     * @throws LocalizedException
     * @throws NoSuchEntityException
     */
    protected function getProductCollection(string $queryText): ProductCollection
    {
        $productResultNumber = $this->helperData->getProductResultNumber();
        $this->layerResolver->create(LayerResolver::CATALOG_LAYER_SEARCH);
        $productCollection = $this->layerResolver->get()
            ->getProductCollection()
            ->addAttributeToSelect([self::DESCRIPTION])
            ->setPageSize($productResultNumber)
            ->addAttributeToSort('relevance')
            ->setOrder('relevance');

        if ($this->queryFactory->get()->isQueryTextShort()) {
            $productCollection->addSearchFilter($queryText);
        }

        return $productCollection;
    }

    /**
     * @return int
     * @throws NoSuchEntityException
     */
    public function getStoreId()
    {
        return $this->storeManager->getStore()->getId();
    }

    /**
     * @param ProductModel $product
     * @return array
     */
    protected function getProductData(ProductModel $product)
    {
        $productAggregator = $this->objectManager->create(
            'M2Commerce\SearchAutocomplete\Block\Autocomplete\ProductAggregator'
        )->setProduct($product);

        return [
            self::NAME         => $productAggregator->getName(),
            self::SKU          => $productAggregator->getSku(),
            self::IMAGE        => $productAggregator->getSmallImage(),
            self::DESCRIPTION  => $productAggregator->getDescription(),
            self::URL          => $productAggregator->getUrl()
        ];
    }
}
