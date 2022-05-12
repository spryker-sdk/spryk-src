<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Spryk\Integration\Glue;

use Codeception\Test\Unit;
use SprykerSdkTest\Module\GlueBackendApiClassNames;
use SprykerSdkTest\SprykIntegrationTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerSdkTest
 * @group Spryk
 * @group Integration
 * @group Glue
 * @group AddGlueResourceMethodResponseTest
 * Add your own group annotations below this line
 */
class AddGlueResourceMethodResponseTest extends Unit
{
    /**
     * @var \SprykerSdkTest\SprykIntegrationTester
     */
    protected SprykIntegrationTester $tester;

    /**
     * @dataProvider resourceMethodResponse
     *
     * @param string $httpMethod
     * @param int $httpResponseCode
     * @param bool|null $isBulk
     * @param string|null $module
     * @param string|null $dataModule
     * @param string|null $zedDomainEntity
     * @param string|null $resourceDataObject
     *
     * @return void
     */
    public function testAddsGlueBackendJSONApiResourceMethodResponseCode(
        string $httpMethod,
        int $httpResponseCode,
        ?bool $isBulk = null,
        ?string $module = null,
        ?string $dataModule = null,
        ?string $zedDomainEntity = null,
        ?string $resourceDataObject = null
    ): void {
        $commandOptions = [
            // TODO We also need to add tests for project level
//            '--mode' => 'project',
//            '--organization' => 'Pyz',
            '--resource' => '/foo-bars',
            '--httpMethod' => $httpMethod,
            '--httpResponseCode' => $httpResponseCode,
        ];
        $expectedControllerMethodName = $httpMethod . 'Action';
        $expectedTestController = $this->getExpectedTestController($httpMethod, $isBulk);
        $expectedTestControllerMethodName = $this->getExpectedTestControllerMethodName($httpMethod, $httpResponseCode, $isBulk);

        if ($isBulk) {
            $commandOptions['--isBulk'] = $isBulk;
            $expectedControllerMethodName = $httpMethod . 'CollectionAction';
        }

        if ($module) {
            $commandOptions['--module'] = $module;
        }
        if ($dataModule) {
            $commandOptions['--dataModule'] = $dataModule;
        }
        if ($zedDomainEntity) {
            $commandOptions['--zedDomainEntity'] = $zedDomainEntity;
        }
        if ($resourceDataObject) {
            $commandOptions['--resourceDataObject'] = $resourceDataObject;
        }

        $this->tester->run($this, $commandOptions);

        // Controller and method
        $this->tester->assertClassOrInterfaceHasMethod(GlueBackendApiClassNames::GLUE_BACKEND_API_CONTROLLER, $expectedControllerMethodName);

        // Controller test class
        $this->tester->assertClassOrInterfaceExists($expectedTestController);

        // Test method in the test controller
        $this->tester->assertClassOrInterfaceHasMethod($expectedTestController, $expectedTestControllerMethodName);

        // Test Helper methods
        $this->tester->assertClassOrInterfaceHasMethod(GlueBackendApiClassNames::GLUE_TEST_HELPER, sprintf('haveValidFooBars%s%sUri', ucfirst($httpMethod), ($isBulk) ? 'Collection' : ''));
        $this->tester->assertClassOrInterfaceHasMethod(GlueBackendApiClassNames::GLUE_TEST_HELPER, sprintf('haveInvalidFooBars%s%sUri', ucfirst($httpMethod), ($isBulk) ? 'Collection' : ''));
        $this->tester->assertClassOrInterfaceHasMethod(GlueBackendApiClassNames::GLUE_TEST_HELPER, sprintf('haveValidFooBars%s%sRequestData', ucfirst($httpMethod), ($isBulk) ? 'Collection' : ''));
        $this->tester->assertClassOrInterfaceHasMethod(GlueBackendApiClassNames::GLUE_TEST_HELPER, sprintf('haveInvalidFooBars%s%sRequestData', ucfirst($httpMethod), ($isBulk) ? 'Collection' : ''));

        // Resource
        $this->tester->assertClassOrInterfaceExists(GlueBackendApiClassNames::GLUE_BACKEND_API_RESOURCE_PLUGIN);

        // Request mapper
        $this->tester->assertClassOrInterfaceExists(GlueBackendApiClassNames::GLUE_BACKEND_API_REQUEST_MAPPER);
        $this->tester->assertClassOrInterfaceExists(GlueBackendApiClassNames::GLUE_BACKEND_API_REQUEST_MAPPER_INTERFACE);

        // Response mapper
        $this->tester->assertClassOrInterfaceExists(GlueBackendApiClassNames::GLUE_BACKEND_API_REQUEST_MAPPER);
        $this->tester->assertClassOrInterfaceExists(GlueBackendApiClassNames::GLUE_BACKEND_API_REQUEST_MAPPER_INTERFACE);

        // Factory
        $this->tester->assertClassOrInterfaceHasMethod(GlueBackendApiClassNames::GLUE_BACKEND_API_FACTORY, sprintf('get%sFacade', ($dataModule ?? 'FooBar')));
        $this->tester->assertClassOrInterfaceHasMethod(GlueBackendApiClassNames::GLUE_BACKEND_API_FACTORY, sprintf('createGlueRequest%sMapper', ($zedDomainEntity ?? 'FooBar')));
        $this->tester->assertClassOrInterfaceHasMethod(GlueBackendApiClassNames::GLUE_BACKEND_API_FACTORY, sprintf('createGlueResponse%sMapper', ($zedDomainEntity ?? 'FooBar')));

        // Dependency provider
        $this->tester->assertClassOrInterfaceHasMethod(GlueBackendApiClassNames::GLUE_BACKEND_API_DEPENDENCY_PROVIDER, 'provideBackendDependencies');
        $this->tester->assertClassOrInterfaceHasMethod(GlueBackendApiClassNames::GLUE_BACKEND_API_DEPENDENCY_PROVIDER, sprintf('add%sFacade', ($dataModule ?? 'FooBar')));
    }

    /**
     * string $httpMethod,
     * int $httpResponseCode,
     * bool $isBulk
     * string $module
     * string $dataModule
     * string $zedDomainEntity
     * string $resourceDataObject
     *
     * @return array<array<\string>>
     */
    public function resourceMethodResponse(): array
    {
        return [
            ['get', 200],
            ['get', 200, true],
            ['post', 200],
            ['patch', 200],
            ['delete', 200],
            ['delete', 200, true],
        ];
    }

    /**
     * @param string $httpMethod
     * @param bool|null $isBulk
     *
     * @return string
     */
    protected function getExpectedTestController(string $httpMethod, ?bool $isBulk = null): string
    {
        switch ($httpMethod) {
            case 'post':
                return GlueBackendApiClassNames::GLUE_BACKEND_API_POST_CONTROLLER_TEST;
            case 'patch':
                return GlueBackendApiClassNames::GLUE_BACKEND_API_PATCH_CONTROLLER_TEST;
            case 'delete':
                if ($isBulk) {
                    return GlueBackendApiClassNames::GLUE_BACKEND_API_DELETE_COLLECTION_CONTROLLER_TEST;
                }

                return GlueBackendApiClassNames::GLUE_BACKEND_API_DELETE_CONTROLLER_TEST;
            default:
                if ($isBulk) {
                    return GlueBackendApiClassNames::GLUE_BACKEND_API_GET_COLLECTION_CONTROLLER_TEST;
                }

                return GlueBackendApiClassNames::GLUE_BACKEND_API_GET_CONTROLLER_TEST;
        }
    }

    /**
     * @param string $httpMethod
     * @param int $httpResponseCode
     * @param bool|null $isBulk
     *
     * @return string
     */
    protected function getExpectedTestControllerMethodName(string $httpMethod, int $httpResponseCode, ?bool $isBulk): string
    {
        return sprintf('testFooBar%s%sReturnsHttpResponseCode%s', ucfirst($httpMethod), ($isBulk) ? 'Collection' : '', $httpResponseCode);
    }
}
