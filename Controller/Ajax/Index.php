<?php
/**
 * @category    M2Commerce Enterprise
 * @package     M2Commerce_SearchAutocomplete
 * @copyright   Copyright (c) 2023 M2Commerce Enterprise
 * @author      dawoodgondaldev@gmail.com
 */

namespace M2Commerce\SearchAutocomplete\Controller\Ajax;

use Magento\Framework\App\ActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Search\Model\QueryFactory;
use Magento\Store\Model\StoreManagerInterface;
use M2Commerce\SearchAutocomplete\Helper\Data as HelperData;
use M2Commerce\SearchAutocomplete\Model\Search as SearchModel;

/**
 * Search Autocomplete ajax controller
 */
class Index implements ActionInterface
{
    /**
     * @var HelperData
     */
    protected $helperData;

    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var QueryFactory
     */
    protected $queryFactory;

    /**
     * @var SearchModel
     */
    protected $searchModel;

    /**
     * @var ResultFactory
     */
    protected $resultFactory;

    /**
     * @param HelperData $helperData
     * @param QueryFactory $queryFactory
     * @param StoreManagerInterface $storeManager
     * @param SearchModel $searchModel
     * @param ResultFactory $resultFactory
     */
    public function __construct(
        HelperData            $helperData,
        QueryFactory          $queryFactory,
        StoreManagerInterface $storeManager,
        SearchModel           $searchModel,
        ResultFactory         $resultFactory
    ) {
        $this->helperData   = $helperData;
        $this->storeManager = $storeManager;
        $this->queryFactory = $queryFactory;
        $this->searchModel  = $searchModel;
        $this->resultFactory = $resultFactory;
    }

    /**
     * Retrieve json of result data
     *
     * @return ResultInterface
     * @throws NoSuchEntityException
     */
    public function execute(): ResultInterface
    {
        $query = $this->queryFactory->get();
        $query->setStoreId($this->storeManager->getStore()->getId());

        $responseData = [];
        if ($query->getQueryText() != '') {
            $query->setId(0)->setIsActive(1)->setIsProcessed(1);
            $responseData['result'] = $this->searchModel->getData();
        }

        $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $resultJson->setData($responseData);
        return $resultJson;
    }
}
