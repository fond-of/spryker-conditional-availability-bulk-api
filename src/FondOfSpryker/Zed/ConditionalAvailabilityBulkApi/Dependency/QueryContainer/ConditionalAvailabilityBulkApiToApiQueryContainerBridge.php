<?php

namespace FondOfSpryker\Zed\ConditionalAvailabilityBulkApi\Dependency\QueryContainer;

use Generated\Shared\Transfer\ApiItemTransfer;
use Spryker\Shared\Kernel\Transfer\AbstractTransfer;
use Spryker\Zed\Api\Persistence\ApiQueryContainerInterface;

class ConditionalAvailabilityBulkApiToApiQueryContainerBridge implements
    ConditionalAvailabilityBulkApiToApiQueryContainerInterface
{
    /**
     * @var \Spryker\Zed\Api\Persistence\ApiQueryContainerInterface
     */
    protected $apiQueryContainer;

    /**
     * @param \Spryker\Zed\Api\Persistence\ApiQueryContainerInterface $apiQueryContainer
     */
    public function __construct(ApiQueryContainerInterface $apiQueryContainer)
    {
        $this->apiQueryContainer = $apiQueryContainer;
    }

    /**
     * @param \Spryker\Shared\Kernel\Transfer\AbstractTransfer $data
     * @param int|null $id
     *
     * @return \Generated\Shared\Transfer\ApiItemTransfer
     */
    public function createApiItem(AbstractTransfer $data, ?int $id = null): ApiItemTransfer
    {
        return $this->apiQueryContainer->createApiItem($data, $id);
    }
}
