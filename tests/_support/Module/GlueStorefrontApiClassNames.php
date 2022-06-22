<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Module;

interface GlueStorefrontApiClassNames
{
    /**
     * @var string
     */
    public const PROJECT_GLUE_STOREFRONT_API_BOOTSTRAP = 'Pyz\Glue\GlueApplication\Bootstrap\GlueStorefrontApiBootstrap';

    /**
     * @var string
     */
    public const GLUE_STOREFRONT_API_BOOTSTRAP = 'Spryker\Glue\GlueApplication\Bootstrap\GlueStorefrontApiBootstrap';

    /**
     * @var string
     */
    public const GLUE_APPLICATION_DEPENDENCY_PROVIDER = 'Spryker\Glue\GlueApplication\GlueApplicationDependencyProvider';

    /**
     * @var string
     */
    public const PROJECT_GLUE_APPLICATION_DEPENDENCY_PROVIDER = 'Pyz\Glue\GlueApplication\GlueApplicationDependencyProvider';

    /**
     * @var string
     */
    public const GLUE_REST_API_CONVENTION_DEPENDENCY_PROVIDER = 'Spryker\Glue\GlueRestApiConvention\GlueRestApiConventionDependencyProvider';

    /**
     * @var string
     */
    public const GLUE_STOREFRONT_API_APPLICATION_DEPENDENCY_PROVIDER = 'Spryker\Glue\GlueStorefrontApiApplication\GlueStorefrontApiApplicationDependencyProvider';

    /**
     * @var string
     */
    public const PROJECT_GLUE_STOREFRONT_API_APPLICATION_DEPENDENCY_PROVIDER = 'Pyz\Glue\GlueStorefrontApiApplication\GlueStorefrontApiApplicationDependencyProvider';

    /**
     * @var string
     */
    public const GLUE_STOREFRONT_API_FACTORY = 'Spryker\Glue\FooBarsStorefrontApi\FooBarsStorefrontApiFactory';

    /**
     * @var string
     */
    public const GLUE_STOREFRONT_API_DEPENDENCY_PROVIDER = 'Spryker\Glue\FooBarsStorefrontApi\FooBarsStorefrontApiDependencyProvider';

    /**
     * @var string
     */
    public const GLUE_STOREFRONT_API_CONTROLLER = 'Spryker\Glue\FooBarsStorefrontApi\Controller\FooBarsResourceController';

    /**
     * @var string
     */
    public const GLUE_BACKEND_API_CONTROLLER_TEST = 'SprykerTest\Glue\FooBarsStorefrontApi\Controller\FooBarsResourceControllerTest';

    /**
     * @var string
     */
    public const GLUE_STOREFRONT_API_GET_CONTROLLER_TEST = 'SprykerTest\Glue\FooBarsStorefrontApi\RestApi\FooBarsGetRestApiCest';

    /**
     * @var string
     */
    public const GLUE_STOREFRONT_API_GET_COLLECTION_CONTROLLER_TEST = 'SprykerTest\Glue\FooBarsStorefrontApi\Controller\FooBarsGetCollectionResourceControllerTest';

    /**
     * @var string
     */
    public const GLUE_STOREFRONT_API_POST_CONTROLLER_TEST = 'SprykerTest\Glue\FooBarsStorefrontApi\Controller\FooBarsPostResourceControllerTest';

    /**
     * @var string
     */
    public const GLUE_STOREFRONT_API_PATCH_CONTROLLER_TEST = 'SprykerTest\Glue\FooBarsStorefrontApi\Controller\FooBarsPatchResourceControllerTest';

    /**
     * @var string
     */
    public const GLUE_STOREFRONT_API_DELETE_CONTROLLER_TEST = 'SprykerTest\Glue\FooBarsStorefrontApi\Controller\FooBarsDeleteResourceControllerTest';

    /**
     * @var string
     */
    public const GLUE_STOREFRONT_API_DELETE_COLLECTION_CONTROLLER_TEST = 'SprykerTest\Glue\FooBarsStorefrontApi\Controller\FooBarsDeleteCollectionResourceControllerTest';

    /**
     * @var string
     */
    public const GLUE_STOREFRONT_API_RESOURCE_PLUGIN = 'Spryker\Glue\FooBarsStorefrontApi\Plugin\FooBarsStorefrontApiResource';

    /**
     * @var string
     */
    public const GLUE_STOREFRONT_API_REQUEST_MAPPER = '\Spryker\Glue\FooBarsStorefrontApi\Mapper\GlueRequestFooBarMapper';

    /**
     * @var string
     */
    public const GLUE_STOREFRONT_API_REQUEST_MAPPER_INTERFACE = '\Spryker\Glue\FooBarsStorefrontApi\Mapper\GlueRequestFooBarMapperInterface';

    /**
     * @var string
     */
    public const GLUE_STOREFRONT_API_RESPONSE_MAPPER = '\Spryker\Glue\FooBarsStorefrontApi\Mapper\GlueResponseFooBarMapper';

    /**
     * @var string
     */
    public const GLUE_STOREFRONT_API_RESPONSE_MAPPER_INTERFACE = '\Spryker\Glue\FooBarsStorefrontApi\Mapper\GlueResponseFooBarMapperInterface';

    /**
     * @var string
     */
    public const GLUE_TEST_HELPER = 'SprykerTest\Glue\FooBarsStorefrontApi\Helper\FooBarsHelper';
}
