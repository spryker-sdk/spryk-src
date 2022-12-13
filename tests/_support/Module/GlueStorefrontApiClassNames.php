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
    public const PROJECT_GLUE_STOREFRONT_API_APPLICATION_DEPENDENCY_PROVIDER = 'Pyz\Glue\GlueStorefrontApiApplication\GlueStorefrontApiApplicationDependencyProvider';

    /**
     * @var string
     */
    public const GLUE_STOREFRONT_ABSTRACT_FACTORY = 'Spryker\Glue\Kernel\AbstractFactory';

    /**
     * @var string
     */
    public const GLUE_STOREFRONT_API_BUSINESS_FACTORY = 'Spryker\Glue\FooBarsStorefrontApi\FooBarsStorefrontApiFactory';

    /**
     * @var string
     */
    public const PROJECT_GLUE_STOREFRONT_API_BUSINESS_FACTORY = 'Pyz\Glue\FooBarsStorefrontApi\FooBarsStorefrontApiFactory';

    /**
     * @var string
     */
    public const GLUE_STOREFRONT_API_DEPENDENCY_PROVIDER = 'Spryker\Glue\FooBarsStorefrontApi\FooBarsStorefrontApiDependencyProvider';

    /**
     * @var string
     */
    public const GLUE_STOREFRONT_API_INDEX_CONTROLLER = 'Spryker\Glue\FooBarsStorefrontApi\Controller\IndexResourceController';

    /**
     * @var string
     */
    public const GLUE_STOREFRONT_API_INDEX_CONTROLLER_TEST = 'SprykerTest\Glue\FooBarsStorefrontApi\Controller\IndexResourceControllerTest';

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
}
