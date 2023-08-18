<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Module;

interface GlueBackendApiClassNames
{
    /**
     * @var string
     */
    public const GLUE_BACKEND_API_APPLICATION_DEPENDENCY_PROVIDER = 'Spryker\Glue\GlueBackendApiApplication\GlueBackendApiApplicationDependencyProvider';

    /**
     * @var string
     */
    public const PROJECT_GLUE_BACKEND_API_APPLICATION_DEPENDENCY_PROVIDER = 'Pyz\Glue\GlueBackendApiApplication\GlueBackendApiApplicationDependencyProvider';

    /**
     * @var string
     */
    public const GLUE_ABSTRACT_FACTORY = 'Spryker\Glue\Kernel\Backend\AbstractFactory';

    /**
     * @var string
     */
    public const GLUE_BACKEND_API_FACTORY = 'Spryker\Glue\FooBarsBackendApi\FooBarsBackendApiFactory';

    /**
     * @var string
     */
    public const PROJECT_GLUE_BACKEND_API_FACTORY = 'Pyz\Glue\FooBarsBackendApi\FooBarsBackendApiFactory';

    /**
     * @var string
     */
    public const GLUE_BACKEND_API_DEPENDENCY_PROVIDER = 'Spryker\Glue\FooBarsBackendApi\FooBarsBackendApiDependencyProvider';

    /**
     * @var string
     */
    public const GLUE_BACKEND_API_INDEX_CONTROLLER = 'Spryker\Glue\FooBarsBackendApi\Controller\IndexResourceController';

    /**
     * @var string
     */
    public const GLUE_BACKEND_API_INDEX_CONTROLLER_TEST = 'SprykerTest\Glue\FooBarsBackendApi\Controller\IndexResourceControllerTest';

    /**
     * @var string
     */
    public const GLUE_BACKEND_API_INDEX_REST_API_RESOURCE_TEST = 'SprykerTest\Glue\FooBarsBackendApi\RestApi\FooBarsIndexRestApiCest';

    /**
     * @var string
     */
    public const GLUE_BACKEND_API_GET_REST_API_RESOURCE_TEST = 'SprykerTest\Glue\FooBarsBackendApi\RestApi\FooBarsGetRestApiCest';

    /**
     * @var string
     */
    public const GLUE_BACKEND_API_DELETE_REST_API_RESOURCE_TEST = 'SprykerTest\Glue\FooBarsBackendApi\RestApi\FooBarsDeleteRestApiCest';

    /**
     * @var string
     */
    public const GLUE_BACKEND_API_DELETE_REST_API_CEST_TEST = 'SprykerTest\Glue\FooBarsBackendApi\RestApi\FooBarsDeleteRestApiCest';

    /**
     * @var string
     */
    public const GLUE_BACKEND_API_FOO_BAR_CONTROLLER = 'Spryker\Glue\FooBarsBackendApi\Controller\FooBarResourceController';

    /**
     * @var string
     */
    public const GLUE_BACKEND_API_GET_CONTROLLER_TEST = 'SprykerTest\Glue\FooBarsBackendApi\RestApi\FooBarsGetRestApiCest';

    /**
     * @var string
     */
    public const GLUE_BACKEND_API_GET_COLLECTION_CONTROLLER_TEST = 'SprykerTest\Glue\FooBarsBackendApi\RestApi\FooBarsGetCollectionRestApiCest';

    /**
     * @var string
     */
    public const GLUE_BACKEND_API_POST_CONTROLLER_TEST = 'SprykerTest\Glue\FooBarsBackendApi\RestApi\FooBarsPostRestApiCest';

    /**
     * @var string
     */
    public const GLUE_BACKEND_API_PATCH_CONTROLLER_TEST = 'SprykerTest\Glue\FooBarsBackendApi\RestApi\FooBarsPatchRestApiCest';

    /**
     * @var string
     */
    public const GLUE_BACKEND_API_DELETE_CONTROLLER_TEST = 'SprykerTest\Glue\FooBarsBackendApi\RestApi\FooBarsDeleteRestApiCest';

    /**
     * @var string
     */
    public const GLUE_BACKEND_API_DELETE_COLLECTION_CONTROLLER_TEST = 'SprykerTest\Glue\FooBarsBackendApi\RestApi\FooBarsDeleteCollectionRestApiCest';

    /**
     * @var string
     */
    public const GLUE_BACKEND_API_TESTER_CLASS = 'SprykerTest\Glue\FooBarsBackendApi\FooBarsBackendApiTester';

    /**
     * @var string
     */
    public const GLUE_BACKEND_API_FIXTURES_CLASS = 'SprykerTest\Glue\FooBarsBackendApi\RestApi\Fixtures\FooBars%s%sRestApiFixtures';

    /**
     * @var string
     */
    public const GLUE_BACKEND_API_RESOURCE_PLUGIN = 'Spryker\Glue\FooBarsBackendApi\Plugin\FooBarsBackendApiResource';

    /**
     * @var string
     */
    public const GLUE_BACKEND_API_REQUEST_MAPPER = '\Spryker\Glue\FooBarsBackendApi\Mapper\GlueRequestFooBarMapper';

    /**
     * @var string
     */
    public const GLUE_BACKEND_API_REQUEST_MAPPER_INTERFACE = '\Spryker\Glue\FooBarsBackendApi\Mapper\GlueRequestFooBarMapperInterface';

    /**
     * @var string
     */
    public const GLUE_BACKEND_API_RESPONSE_MAPPER = '\Spryker\Glue\FooBarsBackendApi\Mapper\GlueResponseFooBarMapper';

    /**
     * @var string
     */
    public const GLUE_BACKEND_API_RESPONSE_MAPPER_INTERFACE = '\Spryker\Glue\FooBarsBackendApi\Mapper\GlueResponseFooBarMapperInterface';
}
