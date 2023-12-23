<?php
/**
 * @category    M2Commerce Enterprise
 * @package     M2Commerce_SearchAutocomplete
 * @copyright   Copyright (c) 2023 M2Commerce Enterprise
 * @author      dawoodgondaldev@gmail.com
 */

namespace M2Commerce\SearchAutocomplete\Block;

use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use M2Commerce\SearchAutocomplete\Helper\Data;

/**
 * Autocomplete class
 */
class Autocomplete extends Template
{
    /**
     * @var Data
     */
    protected $helperData;

    /**
     * @param Data $helperData
     * @param Context $context
     * @param array $data
     */
    public function __construct(
        Data    $helperData,
        Context $context,
        array   $data = []
    ) {
        $this->helperData = $helperData;
        parent::__construct($context, $data);
    }

    /**
     * @return int
     */
    public function isEnabled()
    {
        return $this->helperData->isEnabled();
    }

    /**
     * @return int
     */
    public function getSearchDelay()
    {
        return $this->helperData->getSearchDelay();
    }

    /**
     * @return string
     */
    public function getSearchUrl()
    {
        return $this->getUrl("searchAutoComplete/ajax/index");
    }
}
