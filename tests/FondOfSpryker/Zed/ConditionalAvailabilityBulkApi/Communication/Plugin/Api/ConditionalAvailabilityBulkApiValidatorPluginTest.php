<?php

namespace FondOfSpryker\Zed\ConditionalAvailabilityBulkApi\Communication\Plugin\Api;

use Codeception\Test\Unit;
use FondOfSpryker\Zed\ConditionalAvailabilityBulkApi\ConditionalAvailabilityBulkApiConfig;
use Generated\Shared\Transfer\ApiRequestTransfer;

class ConditionalAvailabilityBulkApiValidatorPluginTest extends Unit
{
    /**
     * @var \Generated\Shared\Transfer\ApiRequestTransfer|\PHPUnit\Framework\MockObject\MockObject
     */
    protected $apiRequestTransferMock;

    /**
     * @var \FondOfSpryker\Zed\ConditionalAvailabilityBulkApi\Communication\Plugin\Api\ConditionalAvailabilityBulkApiValidatorPlugin
     */
    protected $plugin;

    /**
     * @return void
     */
    protected function _before(): void
    {
        parent::_before();

        $this->apiRequestTransferMock = $this->getMockBuilder(ApiRequestTransfer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->plugin = new ConditionalAvailabilityBulkApiValidatorPlugin();
    }

    /**
     * @return void
     */
    public function testGetResourceName(): void
    {
        static::assertEquals(
            ConditionalAvailabilityBulkApiConfig::RESOURCE_CONDITIONAL_AVAILABILITIES_BULK,
            $this->plugin->getResourceName(),
        );
    }

    /**
     * @return void
     */
    public function testValidate(): void
    {
        static::assertEmpty(
            $this->plugin->validate($this->apiRequestTransferMock),
        );
    }
}
