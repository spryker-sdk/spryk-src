<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Spryk\Integration\Glue\BackendApi\Mapper;

use Codeception\Test\Unit;

/**
 * Auto-generated group annotations
 *
 * @group SprykerSdkTest
 * @group Spryk
 * @group Integration
 * @group Glue
 * @group BackendApi
 * @group Mapper
 * @group AddGlueBackendApiRequestMapperTest
 * Add your own group annotations below this line
 */
class AddGlueBackendApiRequestMapperTest extends Unit
{
    /**
     * @var string
     */
    protected const CLASS_NAME = '\Spryker\Glue\FooBar\Mapper\GlueRequestFooBarMapper';

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
        $this->tester->assertClassOrInterfaceExists(static::CLASS_NAME . 'Interface');

        $this->tester->assertClassHasMethod(static::CLASS_NAME, 'mapGlueRequestTransferTo' . static::MODULE . 'CriteriaTransfer');
        $this->tester->assertClassHasMethod(static::CLASS_NAME, 'mapGlueRequestTransferTo' . static::MODULE . 'CollectionDeleteCriteriaTransfer');
        $this->tester->assertClassHasMethod(static::CLASS_NAME, 'mapIdentifierTo' . static::MODULE . 'CriteriaTransfer');
        $this->tester->assertClassHasMethod(static::CLASS_NAME, 'mapIdentifierTo' . static::MODULE . 'CollectionDeleteCriteriaTransfer');
        $this->tester->assertClassHasMethod(static::CLASS_NAME, 'map' . static::MODULE . 'TransferTo' . static::MODULE . 'CollectionRequestTransfer');
    }
}
