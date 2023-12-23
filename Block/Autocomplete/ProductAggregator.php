<?php
/**
 * @category    M2Commerce Enterprise
 * @package     M2Commerce_SearchAutocomplete
 * @copyright   Copyright (c) 2023 M2Commerce Enterprise
 * @author      dawoodgondaldev@gmail.com
 */

namespace M2Commerce\SearchAutocomplete\Block\Autocomplete;

use Magento\Catalog\Block\Product\AbstractProduct as ProductBlock;
use Magento\Catalog\Helper\ImageFactory;
use Magento\Catalog\Helper\Output as CatalogHelperOutput;
use Magento\Framework\Data\Form\FormKey;
use Magento\Framework\DataObject;
use Magento\Framework\Escaper;
use Magento\Framework\Stdlib\StringUtils;
use Magento\Framework\Url\Helper\Data as UrlHelper;
use Magento\Framework\View\Asset\Repository;
use Magento\Framework\View\Asset\Repository as AssetRepository;

/**
 * ProductAggregator class for autocomplete data
 */
class ProductAggregator extends DataObject
{
    /**
     * @var ProductBlock
     */
    protected $productBlock;

    /**
     * @var UrlHelper
     */
    protected $urlHelper;

    /**
     * @var FormKey
     */
    protected $formKey;

    /**
     * @var AssetRepository
     */
    protected $assetRepo;

    /**
     * @var CatalogHelperOutput
     */
    protected $catalogHelperOutput;

    /**
     * @var Escaper
     */
    protected $escaper;

    /**
     * @var StringUtils
     */
    protected $string;

    /**
     * @var ImageFactory
     */
    private $imageFactory;

    /**
     * @param ImageFactory $imageFactory
     * @param ProductBlock $productBlock
     * @param StringUtils $string
     * @param UrlHelper $urlHelper
     * @param AssetRepository $assetRepo
     * @param CatalogHelperOutput $catalogHelperOutput
     * @param FormKey $formKey
     * @param Escaper $escaper
     */
    public function __construct(
        ImageFactory        $imageFactory,
        ProductBlock        $productBlock,
        StringUtils         $string,
        UrlHelper           $urlHelper,
        Repository          $assetRepo,
        CatalogHelperOutput $catalogHelperOutput,
        FormKey             $formKey,
        Escaper             $escaper
    ) {
        $this->imageFactory        = $imageFactory;
        $this->productBlock        = $productBlock;
        $this->string              = $string;
        $this->urlHelper           = $urlHelper;
        $this->assetRepo           = $assetRepo;
        $this->catalogHelperOutput = $catalogHelperOutput;
        $this->formKey             = $formKey;
        $this->escaper             = $escaper;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return strip_tags(html_entity_decode((string)$this->getProduct()->getName()));
    }

    /**
     * @return mixed
     */
    public function getSku()
    {
        return $this->getProduct()->getSku();
    }

    /**
     * @return string
     */
    public function getSmallImage()
    {
        $product = $this->getProduct();
        $image = $this->imageFactory->create()->init($product, 'product_small_image');
        return $image->getUrl();
    }

    /**
     * @param string|null $route
     * @param array|null $params
     * @return string
     */
    public function getUrl(?string $route = '', ?array $params = [])
    {
        return $this->productBlock->getProductUrl($this->getProduct());
    }

    /**
     * @param string $html
     * @return string
     */
    protected function cropDescription(string $html)
    {
        $string = strip_tags($html);
        return (strlen($string) > 50) ? $this->string->substr($string, 0, 50) . '...' : $string;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        $description = html_entity_decode((string)$this->getProduct()->getDescription());
        return $this->cropDescription($description);
    }
}
