<?php

namespace FondOfSpryker\Zed\ConditionalAvailabilityBulkApi\Communication\Plugin\Api;

use FondOfSpryker\Zed\ConditionalAvailabilityBulkApi\ConditionalAvailabilityBulkApiConfig;
use Generated\Shared\Transfer\ApiRequestTransfer;
use Spryker\Zed\ApiExtension\Dependency\Plugin\ApiValidatorPluginInterface;
use Spryker\Zed\Kernel\Communication\AbstractPlugin;

/**
 * @method \FondOfSpryker\Zed\ConditionalAvailabilityBulkApi\ConditionalAvailabilityBulkApiConfig getConfig()
 * @method \FondOfSpryker\Zed\ConditionalAvailabilityBulkApi\Business\ConditionalAvailabilityBulkApiFacadeInterface getFacade()
 */
class ConditionalAvailabilityBulkApiValidatorPlugin extends AbstractPlugin implements ApiValidatorPluginInterface
{
    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @return string
     */
    public function getResourceName(): string
    {
        return ConditionalAvailabilityBulkApiConfig::RESOURCE_CONDITIONAL_AVAILABILITIES_BULK;
    }

    /**
     * {@inheritDoc}
     *
     * @api
     *
     * @param \Generated\Shared\Transfer\ApiRequestTransfer $apiRequestTransfer
     *
     * @return array<\Generated\Shared\Transfer\ApiValidationErrorTransfer>
     */
    public function validate(ApiRequestTransfer $apiRequestTransfer): array
    {
        return [];
    }
}
