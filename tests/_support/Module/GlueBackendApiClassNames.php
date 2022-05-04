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
    public const GLUE_BACKEND_API_BOOTSTRAP = 'Pyz\Glue\GlueApplication\Bootstrap\GlueBackendApiBootstrap';

    /**
     * @var string
     */
    public const GLUE_APPLICATION_DEPENDENCY_PROVIDER = 'Spryker\Glue\GlueApplication\GlueApplicationDependencyProvider';

    /**
     * @var string
     */
    public const GLUE_REST_API_CONVENTION_DEPENDENCY_PROVIDER = 'Spryker\Glue\GlueRestApiConvention\GlueRestApiConventionDependencyProvider';

    /**
     * @var string
     */
    public const GLUE_BACKEND_API_APPLICATION_DEPENDENCY_PROVIDER = 'Spryker\Glue\GlueBackendApiApplication\GlueBackendApiApplicationDependencyProvider';

    /**
     * @var string
     */
    public const GLUE_BACKEND_API_FACTORY = 'Spryker\Glue\FooBarsBackendApi\FooBarsBackendApiFactory';

    /**
     * @var string
     */
    public const GLUE_BACKEND_API_DEPENDENCY_PROVIDER = 'Spryker\Glue\FooBarsBackendApi\FooBarsBackendApiDependencyProvider';

    /**
     * @var string
     */
    public const GLUE_BACKEND_API_CONTROLLER = 'Spryker\Glue\FooBarsBackendApi\Controller\FooBarsResourceController';

    /**
     * @var string
     */
    public const GLUE_BACKEND_API_GET_CONTROLLER_TEST = 'SprykerTest\Glue\FooBarsBackendApi\Controller\FooBarsGetResourceControllerTest';

    /**
     * @var string
     */
    public const GLUE_BACKEND_API_GET_COLLECTION_CONTROLLER_TEST = 'SprykerTest\Glue\FooBarsBackendApi\Controller\FooBarsGetCollectionResourceControllerTest';

    /**
     * @var string
     */
    public const GLUE_BACKEND_API_POST_CONTROLLER_TEST = 'SprykerTest\Glue\FooBarsBackendApi\Controller\FooBarsPostResourceControllerTest';

    /**
     * @var string
     */
    public const GLUE_BACKEND_API_PATCH_CONTROLLER_TEST = 'SprykerTest\Glue\FooBarsBackendApi\Controller\FooBarsPatchResourceControllerTest';

    /**
     * @var string
     */
    public const GLUE_BACKEND_API_DELETE_CONTROLLER_TEST = 'SprykerTest\Glue\FooBarsBackendApi\Controller\FooBarsDeleteResourceControllerTest';

    /**
     * @var string
     */
    public const GLUE_BACKEND_API_DELETE_COLLECTION_CONTROLLER_TEST = 'SprykerTest\Glue\FooBarsBackendApi\Controller\FooBarsDeleteCollectionResourceControllerTest';

    /**
     * @var string
     */
    public const GLUE_BACKEND_API_RESOURCE_PLUGIN = 'Spryker\Glue\FooBarsBackendApi\Plugin\FooBarBackendApiResource';

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

    /**
     * @var string
     */
    public const GLUE_TEST_HELPER = 'SprykerTest\Glue\FooBarsBackendApi\Helper\FooBarsHelper';
}
