<?php

namespace FondOfSpryker\Zed\ConditionalAvailabilityBulkApi\Business\Model;

use FondOfSpryker\Zed\ConditionalAvailabilityBulkApi\Business\Mapper\ConditionalAvailabilityBulkApiMapperInterface;
use FondOfSpryker\Zed\ConditionalAvailabilityBulkApi\Dependency\Facade\ConditionalAvailabilityBulkApiToConditionalAvailabilityFacadeBridge;
use FondOfSpryker\Zed\ConditionalAvailabilityBulkApi\Dependency\Facade\ConditionalAvailabilityBulkApiToProductFacadeInterface;
use FondOfSpryker\Zed\ConditionalAvailabilityBulkApi\Dependency\QueryContainer\ConditionalAvailabilityBulkApiToApiQueryContainerInterface;
use Generated\Shared\Transfer\ApiDataTransfer;
use Generated\Shared\Transfer\ApiItemTransfer;
use Generated\Shared\Transfer\ConditionalAvailabilityBulkApiResponseTransfer;
use Generated\Shared\Transfer\ConditionalAvailabilityTransfer;

class ConditionalAvailabilityBulkApi implements ConditionalAvailabilityBulkApiInterface
{
    /**
     * @var \FondOfSpryker\Zed\ConditionalAvailabilityBulkApi\Business\Mapper\ConditionalAvailabilityBulkApiMapperInterface
     */
    protected $conditionalAvailabilityBulkApiMapper;

    /**
     * @var \FondOfSpryker\Zed\ConditionalAvailabilityBulkApi\Dependency\Facade\ConditionalAvailabilityBulkApiToConditionalAvailabilityFacadeBridge
     */
    protected $conditionalAvailabilityFacade;

    /**
     * @var \FondOfSpryker\Zed\ConditionalAvailabilityBulkApi\Dependency\Facade\ConditionalAvailabilityBulkApiToProductFacadeInterface
     */
    protected $productFacade;

    /**
     * @var \FondOfSpryker\Zed\ConditionalAvailabilityBulkApi\Dependency\QueryContainer\ConditionalAvailabilityBulkApiToApiQueryContainerInterface
     */
    protected $apiQueryContainer;

    /**
     * @param \FondOfSpryker\Zed\ConditionalAvailabilityBulkApi\Business\Mapper\ConditionalAvailabilityBulkApiMapperInterface $conditionalAvailabilityBulkApiMapper
     * @param \FondOfSpryker\Zed\ConditionalAvailabilityBulkApi\Dependency\Facade\ConditionalAvailabilityBulkApiToConditionalAvailabilityFacadeBridge $conditionalAvailabilityFacade
     * @param \FondOfSpryker\Zed\ConditionalAvailabilityBulkApi\Dependency\Facade\ConditionalAvailabilityBulkApiToProductFacadeInterface $productFacade
     * @param \FondOfSpryker\Zed\ConditionalAvailabilityBulkApi\Dependency\QueryContainer\ConditionalAvailabilityBulkApiToApiQueryContainerInterface $apiQueryContainer
     */
    public function __construct(
        ConditionalAvailabilityBulkApiMapperInterface $conditionalAvailabilityBulkApiMapper,
        ConditionalAvailabilityBulkApiToConditionalAvailabilityFacadeBridge $conditionalAvailabilityFacade,
        ConditionalAvailabilityBulkApiToProductFacadeInterface $productFacade,
        ConditionalAvailabilityBulkApiToApiQueryContainerInterface $apiQueryContainer
    ) {
        $this->conditionalAvailabilityBulkApiMapper = $conditionalAvailabilityBulkApiMapper;
        $this->conditionalAvailabilityFacade = $conditionalAvailabilityFacade;
        $this->productFacade = $productFacade;
        $this->apiQueryContainer = $apiQueryContainer;
    }

    /**
     * @param \Generated\Shared\Transfer\ApiDataTransfer $apiDataTransfer
     *
     * @return \Generated\Shared\Transfer\ApiItemTransfer
     */
    public function persist(ApiDataTransfer $apiDataTransfer): ApiItemTransfer
    {
        $groupedConditionalAvailabilityTransfers = $this->conditionalAvailabilityBulkApiMapper
            ->mapApiDataTransferToGroupedConditionalAvailabilityTransfers($apiDataTransfer);

        $conditionalAvailabilityBulkApiResponseTransfer = (new ConditionalAvailabilityBulkApiResponseTransfer())
            ->setConditionalAvailabilityIds([]);

        foreach ($groupedConditionalAvailabilityTransfers as $conditionalAvailabilityTransfers) {
            $this->hydrateConditionalAvailabilitiesWithProductId($conditionalAvailabilityTransfers);

            foreach ($conditionalAvailabilityTransfers as $conditionalAvailabilityTransfer) {
                $idConditionalAvailability = $this->persistConditionalAvailability($conditionalAvailabilityTransfer);

                if ($idConditionalAvailability === null) {
                    continue;
                }

                $conditionalAvailabilityBulkApiResponseTransfer->addConditionalAvailabilityId(
                    $idConditionalAvailability
                );
            }
        }

        return $this->apiQueryContainer->createApiItem($conditionalAvailabilityBulkApiResponseTransfer);
    }

    /**
     * @param \Generated\Shared\Transfer\ConditionalAvailabilityTransfer $conditionalAvailabilityTransfer
     *
     * @return int|null
     */
    protected function persistConditionalAvailability(
        ConditionalAvailabilityTransfer $conditionalAvailabilityTransfer
    ): ?int {
        $conditionalAvailabilityResponse = $this->conditionalAvailabilityFacade
            ->persistConditionalAvailability($conditionalAvailabilityTransfer);

        $conditionalAvailabilityTransfer = $conditionalAvailabilityResponse->getConditionalAvailabilityTransfer();

        if ($conditionalAvailabilityTransfer === null || !$conditionalAvailabilityResponse->getIsSuccessful()) {
            return null;
        }

        return $conditionalAvailabilityTransfer->getIdConditionalAvailability();
    }

    /**
     * @param array<string, \Generated\Shared\Transfer\ConditionalAvailabilityTransfer> $conditionalAvailabilityTransfers
     *
     * @return array<string, \Generated\Shared\Transfer\ConditionalAvailabilityTransfer>
     */
    protected function hydrateConditionalAvailabilitiesWithProductId(
        array $conditionalAvailabilityTransfers
    ): array {
        $skus = array_keys($conditionalAvailabilityTransfers);
        $productConcreteIds = $this->productFacade->getProductConcreteIdsByConcreteSkus($skus);

        foreach ($productConcreteIds as $sku => $productConcreteId) {
            if (empty($conditionalAvailabilityTransfers[$sku])) {
                continue;
            }

            $conditionalAvailabilityTransfers[$sku]->setFkProduct($productConcreteId);
        }

        return $conditionalAvailabilityTransfers;
    }
}
