<?php

namespace SprykerSdkTest\Spryk\Integration\Glue\BackendApi\Mapper;

use Codeception\Test\Unit;
use SprykerSdkTest\Module\GlueBackendApiClassNames;

/**
 * Auto-generated group annotations
 *
 * @group SprykerSdkTest
 * @group Spryk
 * @group Integration
 * @group Glue
 * @group BackendApi
 * @group Mapper
 * @group AddGlueBackendApiResponseMapperTest
 * Add your own group annotations below this line
 */
class AddGlueBackendApiResponseMapperTest extends Unit
{
    /**
     * @var string
     */
    protected const CLASS_NAME = '\Spryker\Glue\FooBar\Mapper\GlueResponseFooBarMapper';

    protected const INTERFACE_NAME = self::CLASS_NAME.'Interface';

    /**
     * @var string
     */
    protected const MODULE = 'FooBar';

    /**
     * @var \SprykerSdkTest\SprykIntegrationTester
     */
    protected $tester;

    /**
     * @return void
     */
    public function testAddsGlueBackendApiResponseMapper(): void
    {
        $this->tester->run($this, [
            '--resource' => '/foo-bars',
            '--module' => static::MODULE,
        ]);

        $this->tester->assertClassOrInterfaceExists(static::CLASS_NAME);
        $this->tester->assertClassOrInterfaceExists(static::CLASS_NAME.'Interface');

        $this->tester->assertClassHasMethod(static::CLASS_NAME, 'map' . static::MODULE . 'CollectionTransferToGlueResponseTransfer');
        $this->tester->assertClassHasMethod(static::CLASS_NAME, 'map' . static::MODULE . 'CollectionTransferToSingleResourceGlueResponseTransfer');
        $this->tester->assertClassHasMethod(static::CLASS_NAME, 'map' . static::MODULE . 'CollectionResponseTransferToGlueResponseTransfer');
        $this->tester->assertClassHasMethod(static::CLASS_NAME, 'map' . static::MODULE . 'CollectionResponseTransferToSingleResourceGlueResponseTransfer');
        $this->tester->assertClassHasMethod(static::CLASS_NAME, 'addNotFoundError');
        $this->tester->assertClassHasMethod(static::CLASS_NAME, 'addResourceToGlueResponse');
    }
}
