<?php
/**
 * @category    M2Commerce Enterprise
 * @package     M2Commerce_SearchAutocomplete
 * @copyright   Copyright (c) 2023 M2Commerce Enterprise
 * @author      dawoodgondaldev@gmail.com
 */

namespace M2Commerce\SearchAutocomplete\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

/**
 * Search Autocomplete config data helper
 */
class Data extends AbstractHelper
{
    const XML_PATH_ENABLED= 'searchAutoComplete/general/enabled';
    const XML_PATH_SEARCH_DELAY = 'searchAutoComplete/general/delay';
    const XML_PATH_RESULT_NUMBER = 'searchAutoComplete/general/result_number';

    /**
     * @param $storeId
     * @return int
     */
    public function isEnabled($storeId = null)
    {
        return (int)$this->scopeConfig->getValue(self::XML_PATH_ENABLED, ScopeInterface::SCOPE_STORE, $storeId);
    }

    /**
     * @param $storeId
     * @return int
     */
    public function getSearchDelay($storeId = null)
    {
        return (int)$this->scopeConfig->getValue(self::XML_PATH_SEARCH_DELAY, ScopeInterface::SCOPE_STORE, $storeId);
    }

    /**
     * @param $storeId
     * @return int
     */
    public function getProductResultNumber($storeId = null)
    {
        return (int)$this->scopeConfig->getValue(self::XML_PATH_RESULT_NUMBER, ScopeInterface::SCOPE_STORE, $storeId);
    }
}
