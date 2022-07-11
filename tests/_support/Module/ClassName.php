<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Module;

interface ClassName
{
    /**
     * @var string
     */
    public const CONFIG_YVES = 'Spryker\Yves\FooBar\FooBarConfig';

    /**
     * @var string
     */
    public const CONFIG_ZED = 'Spryker\Zed\FooBar\FooBarConfig';

    /**
     * @var string
     */
    public const CONFIG_CLIENT = 'Spryker\Client\FooBar\FooBarConfig';

    /**
     * @var string
     */
    public const CONTROLLER_ZED = 'Spryker\Zed\FooBar\Communication\Controller\IndexController';

    /**
     * @var string
     */
    public const BRIDGE = 'Spryker\Zed\FooBar\Dependency\Facade\FooBarToZipZapFacadeBridge';

    /**
     * @var string
     */
    public const BRIDGE_INTERFACE = 'Spryker\Zed\FooBar\Dependency\Facade\FooBarToZipZapFacadeInterface';

    /**
     * @var string
     */
    public const FACADE = 'Spryker\Zed\FooBar\Business\FooBarFacade';

    /**
     * @var string
     */
    public const FACADE_TEST = 'SprykerTest\Zed\FooBar\Business\FooBarFacadeTest';

    /**
     * @var string
     */
    public const FACADE_INTERFACE = 'Spryker\Zed\FooBar\Business\FooBarFacadeInterface';

    /**
     * @var string
     */
    public const REPOSITORY = 'Spryker\Zed\FooBar\Persistence\FooBarRepository';

    /**
     * @var string
     */
    public const REPOSITORY_INTERFACE = 'Spryker\Zed\FooBar\Persistence\FooBarRepositoryInterface';

    /**
     * @var string
     */
    public const ENTITY_MANAGER = 'Spryker\Zed\FooBar\Persistence\FooBarEntityManager';

    /**
     * @var string
     */
    public const ENTITY_MANAGER_INTERFACE = 'Spryker\Zed\FooBar\Persistence\FooBarEntityManagerInterface';

    /**
     * @var string
     */
    public const CLIENT_CONFIG = 'Spryker\Client\FooBar\FooBarConfig';

    /**
     * @var string
     */
    public const CLIENT = 'Spryker\Client\FooBar\FooBarClient';

    /**
     * @var string
     */
    public const CLIENT_INTERFACE = 'Spryker\Client\FooBar\FooBarClientInterface';

    /**
     * @var string
     */
    public const CLIENT_FACTORY = 'Spryker\Client\FooBar\FooBarFactory';

    /**
     * @var string
     */
    public const CLIENT_DEPENDENCY_PROVIDER = 'Spryker\Client\FooBar\FooBarDependencyProvider';

    /**
     * @var string
     */
    public const CLIENT_CLIENT_BRIDGE = 'Spryker\Client\FooBar\Dependency\Client\FooBarToZipZapClientBridge';

    /**
     * @var string
     */
    public const CLIENT_CLIENT_BRIDGE_INTERFACE = 'Spryker\Client\FooBar\Dependency\Client\FooBarToZipZapClientInterface';

    /**
     * @var string
     */
    public const CLIENT_SERVICE_BRIDGE = 'Spryker\Client\FooBar\Dependency\Service\FooBarToZipZapServiceBridge';

    /**
     * @var string
     */
    public const CLIENT_SERVICE_BRIDGE_INTERFACE = 'Spryker\Client\FooBar\Dependency\Service\FooBarToZipZapServiceInterface';

    /**
     * @var string
     */
    public const YVES_CONTROLLER = 'SprykerShop\Yves\FooBar\Controller\FooBarController';

    /**
     * @var string
     */
    public const YVES_FACTORY = 'SprykerShop\Yves\FooBar\FooBarFactory';

    /**
     * @var string
     */
    public const YVES_CONFIG = 'SprykerShop\Yves\FooBar\FooBarConfig';

    /**
     * @var string
     */
    public const YVES_ROUTE_PROVIDER = 'SprykerShop\Yves\FooBar\Plugin\Router\FooBarRouteProviderPlugin';

    /**
     * @var string
     */
    public const YVES_DEPENDENCY_PROVIDER = 'SprykerShop\Yves\FooBar\FooBarDependencyProvider';

    /**
     * @var string
     */
    public const YVES_CLIENT_BRIDGE = 'SprykerShop\Yves\FooBar\Dependency\Client\FooBarToZipZapClientBridge';

    /**
     * @var string
     */
    public const YVES_CLIENT_BRIDGE_INTERFACE = 'SprykerShop\Yves\FooBar\Dependency\Client\FooBarToZipZapClientInterface';

    /**
     * @var string
     */
    public const YVES_SERVICE_BRIDGE = 'SprykerShop\Yves\FooBar\Dependency\Service\FooBarToZipZapServiceBridge';

    /**
     * @var string
     */
    public const YVES_SERVICE_BRIDGE_INTERFACE = 'SprykerShop\Yves\FooBar\Dependency\Service\FooBarToZipZapServiceInterface';

    /**
     * @var string
     */
    public const ZED_CONTROLLER = 'Spryker\Zed\FooBar\Communication\Controller\IndexController';

    /**
     * @var string
     */
    public const ZED_CONFIG = 'Spryker\Zed\FooBar\FooBarConfig';

    /**
     * @var string
     */
    public const ZED_BUSINESS_FACTORY = 'Spryker\Zed\FooBar\Business\FooBarBusinessFactory';

    /**
     * @var string
     */
    public const ZED_DEPENDENCY_PROVIDER = 'Spryker\Zed\FooBar\FooBarDependencyProvider';

    /**
     * @var string
     */
    public const GLUE_CONTROLLER = 'Spryker\Glue\FooBar\Controller\BarController';

    /**
     * @var string
     */
    public const GLUE_CONFIG = 'Spryker\Glue\FooBar\FooBarConfig';

    /**
     * @var string
     */
    public const GLUE_BUSINESS_FACTORY = 'Spryker\Glue\FooBar\FooBarFactory';

    /**
     * @var string
     */
    public const GLUE_DEPENDENCY_PROVIDER = 'Spryker\Glue\FooBar\FooBarDependencyProvider';

    /**
     * @var string
     */
    public const GLUE_RESOURCE_MAPPER = 'Spryker\Glue\FooBar\Processor\Mapper\FooBarMapper';

    /**
     * @var string
     */
    public const GLUE_RESOURCE_MAPPER_INTERFACE = 'Spryker\Glue\FooBar\Processor\Mapper\FooBarMapperInterface';

    /**
     * @var string
     */
    public const GLUE_CLIENT_BRIDGE = 'Spryker\Glue\FooBar\Dependency\Client\FooBarToZipZapClientBridge';

    /**
     * @var string
     */
    public const GLUE_CLIENT_BRIDGE_INTERFACE = 'Spryker\Glue\FooBar\Dependency\Client\FooBarToZipZapClientInterface';

    /**
     * @var string
     */
    public const ZED_FACADE_BRIDGE = 'Spryker\Zed\FooBar\Dependency\Facade\FooBarToZipZapFacadeBridge';

    /**
     * @var string
     */
    public const ZED_FACADE_BRIDGE_INTERFACE = 'Spryker\Zed\FooBar\Dependency\Facade\FooBarToZipZapFacadeInterface';

    /**
     * @var string
     */
    public const ZED_CLIENT_BRIDGE = 'Spryker\Zed\FooBar\Dependency\Client\FooBarToZipZapClientBridge';

    /**
     * @var string
     */
    public const ZED_CLIENT_BRIDGE_INTERFACE = 'Spryker\Zed\FooBar\Dependency\Client\FooBarToZipZapClientInterface';

    /**
     * @var string
     */
    public const ZED_SERVICE_BRIDGE = 'Spryker\Zed\FooBar\Dependency\Service\FooBarToZipZapServiceBridge';

    /**
     * @var string
     */
    public const ZED_SERVICE_BRIDGE_INTERFACE = 'Spryker\Zed\FooBar\Dependency\Service\FooBarToZipZapServiceInterface';

    /**
     * @var string
     */
    public const ZED_FACADE = 'Spryker\Zed\FooBar\Business\FooBarFacade';

    /**
     * @var string
     */
    public const ZED_FACADE_TEST = 'SprykerTest\Zed\FooBar\Business\FooBarFacadeTest';

    /**
     * @var string
     */
    public const ZED_REPOSITORY = 'Spryker\Zed\FooBar\Persistence\FooBarRepository';

    /**
     * @var string
     */
    public const ZED_REPOSITORY_INTERFACE = 'Spryker\Zed\FooBar\Persistence\FooBarRepositoryInterface';

    /**
     * @var string
     */
    public const ZED_PERSISTENCE_FACTORY = 'Spryker\Zed\FooBar\Persistence\FooBarPersistenceFactory';

    /**
     * @var string
     */
    public const ZED_ENTITY_MANAGER = 'Spryker\Zed\FooBar\Persistence\FooBarEntityManager';

    /**
     * @var string
     */
    public const ZED_ENTITY_MANAGER_INTERFACE = 'Spryker\Zed\FooBar\Persistence\FooBarEntityManagerInterface';

    /**
     * @var string
     */
    public const DATA_IMPORT_BUSINESS_FACTORY = 'Spryker\Zed\FooBar\Business\FooBarBusinessFactory';

    /**
     * @var string
     */
    public const ZED_CHECKOUT_DO_SAVE_ORDER_PLUGIN = 'Spryker\Zed\FooBar\Communication\Plugin\Checkout\TestPaymentCheckoutDoSaveOrderPlugin';

    /**
     * @var string
     */
    public const ZED_CHECKOUT_POST_SAVE_PLUGIN = 'Spryker\Zed\FooBar\Communication\Plugin\Checkout\TestPaymentCheckoutPostSavePlugin';

    /**
     * @var string
     */
    public const ZED_CHECKOUT_PRE_CONDITION_PLUGIN = 'Spryker\Zed\FooBar\Communication\Plugin\Checkout\TestPaymentCheckoutPreConditionPlugin';

    /**
     * @var string
     */
    public const ZED_ORDER_PAYMENT_EXPANDER_PLUGIN = 'Spryker\Zed\FooBar\Communication\Plugin\TestPaymentOrderPaymentExpanderPlugin';

    /**
     * @var string
     */
    public const ZED_PAYMENT_METHOD_FILTER_PLUGIN = 'Spryker\Zed\FooBar\Communication\Plugin\Payment\TestPaymentPaymentMethodFilterPlugin';

    /**
     * @var string
     */
    public const ZED_PLUGIN_OMS_COMMAND_BY_ORDER_PLUGIN = 'Spryker\Zed\FooBar\Communication\Plugin\Oms\Command\TestPaymentCommandByOrder';

    /**
     * @var string
     */
    public const YVES_PLUGIN_SUB_FORM_PLUGIN = 'SprykerShop\Yves\FooBar\Plugin\TestPaymentSubFormPlugin';

    /**
     * @var string
     */
    public const SERVICE_CONFIG = 'Spryker\Service\FooBar\FooBarConfig';

    /**
     * @var string
     */
    public const PROJECT_SERVICE_CONFIG = 'Pyz\Service\FooBar\FooBarConfig';

    /**
     * @var string
     */
    public const PROJECT_CONFIG_YVES = 'Pyz\Yves\FooBar\FooBarConfig';

    /**
     * @var string
     */
    public const PROJECT_CONFIG_ZED = 'Pyz\Zed\FooBar\FooBarConfig';

    /**
     * @var string
     */
    public const PROJECT_CONFIG_CLIENT = 'Pyz\Client\FooBar\FooBarConfig';

    /**
     * @var string
     */
    public const PROJECT_CONTROLLER_ZED = 'Pyz\Zed\FooBar\Communication\Controller\IndexController';

    /**
     * @var string
     */
    public const PROJECT_BRIDGE = 'Pyz\Zed\FooBar\Dependency\Facade\FooBarToZipZapFacadeBridge';

    /**
     * @var string
     */
    public const PROJECT_BRIDGE_INTERFACE = 'Pyz\Zed\FooBar\Dependency\Facade\FooBarToZipZapFacadeInterface';

    /**
     * @var string
     */
    public const PROJECT_REPOSITORY_INTERFACE = 'Pyz\Zed\FooBar\Persistence\FooBarRepositoryInterface';

    /**
     * @var string
     */
    public const PROJECT_ENTITY_MANAGER = 'Pyz\Zed\FooBar\Persistence\FooBarEntityManager';

    /**
     * @var string
     */
    public const PROJECT_CLIENT_CONFIG = 'Pyz\Client\FooBar\FooBarConfig';

    /**
     * @var string
     */
    public const PROJECT_CLIENT = 'Pyz\Client\FooBar\FooBarClient';

    /**
     * @var string
     */
    public const PROJECT_CLIENT_INTERFACE = 'Pyz\Client\FooBar\FooBarClientInterface';

    /**
     * @var string
     */
    public const PROJECT_CLIENT_FACTORY = 'Pyz\Client\FooBar\FooBarFactory';

    /**
     * @var string
     */
    public const PROJECT_CLIENT_DEPENDENCY_PROVIDER = 'Pyz\Client\FooBar\FooBarDependencyProvider';

    /**
     * @var string
     */
    public const PROJECT_CLIENT_CLIENT_BRIDGE = 'Pyz\Client\FooBar\Dependency\Client\FooBarToZipZapClientBridge';

    /**
     * @var string
     */
    public const PROJECT_YVES_CONTROLLER = 'Pyz\Yves\FooBar\Controller\FooBarController';

    /**
     * @var string
     */
    public const PROJECT_YVES_FACTORY = 'Pyz\Yves\FooBar\FooBarFactory';

    /**
     * @var string
     */
    public const PROJECT_YVES_CONFIG = 'Pyz\Yves\FooBar\FooBarConfig';

    /**
     * @var string
     */
    public const PROJECT_YVES_ROUTE_PROVIDER = 'Pyz\Yves\FooBar\Plugin\Router\FooBarRouteProviderPlugin';

    /**
     * @var string
     */
    public const PROJECT_YVES_DEPENDENCY_PROVIDER = 'Pyz\Yves\FooBar\FooBarDependencyProvider';

    /**
     * @var string
     */
    public const PROJECT_ZED_CONTROLLER = 'Pyz\Zed\FooBar\Communication\Controller\IndexController';

    /**
     * @var string
     */
    public const PROJECT_ZED_CONFIG = 'Pyz\Zed\FooBar\FooBarConfig';

    /**
     * @var string
     */
    public const PROJECT_ZED_BUSINESS_FACTORY = 'Pyz\Zed\FooBar\Business\FooBarBusinessFactory';

    /**
     * @var string
     */
    public const PROJECT_ZED_DEPENDENCY_PROVIDER = 'Pyz\Zed\FooBar\FooBarDependencyProvider';

    /**
     * @var string
     */
    public const PROJECT_GLUE_CONTROLLER = 'Pyz\Glue\FooBar\Controller\BarController';

    /**
     * @var string
     */
    public const PROJECT_GLUE_CONFIG = 'Pyz\Glue\FooBar\FooBarConfig';

    /**
     * @var string
     */
    public const PROJECT_GLUE_BUSINESS_FACTORY = 'Pyz\Glue\FooBar\FooBarFactory';

    /**
     * @var string
     */
    public const PROJECT_GLUE_DEPENDENCY_PROVIDER = 'Pyz\Glue\FooBar\FooBarDependencyProvider';

    /**
     * @var string
     */
    public const PROJECT_GLUE_RESOURCE_MAPPER = 'Pyz\Glue\FooBar\Processor\Mapper\FooBarMapper';

    /**
     * @var string
     */
    public const PROJECT_GLUE_RESOURCE_MAPPER_INTERFACE = 'Pyz\Glue\FooBar\Processor\Mapper\FooBarMapperInterface';

    /**
     * @var string
     */
    public const PROJECT_ZED_CLIENT_BRIDGE = 'Pyz\Zed\FooBar\Dependency\Client\FooBarToZipZapClientBridge';

    /**
     * @var string
     */
    public const PROJECT_ZED_FACADE = 'Pyz\Zed\FooBar\Business\FooBarFacade';

    /**
     * @var string
     */
    public const PROJECT_ZED_FACADE_TEST = 'PyzTest\Zed\FooBar\Business\FooBarFacadeTest';

    /**
     * @var string
     */
    public const PROJECT_ZED_REPOSITORY = 'Pyz\Zed\FooBar\Persistence\FooBarRepository';

    /**
     * @var string
     */
    public const PROJECT_ZED_REPOSITORY_INTERFACE = 'Pyz\Zed\FooBar\Persistence\FooBarRepositoryInterface';

    /**
     * @var string
     */
    public const PROJECT_ZED_ENTITY_MANAGER = 'Pyz\Zed\FooBar\Persistence\FooBarEntityManager';

    /**
     * @var string
     */
    public const PROJECT_ZED_ENTITY_MANAGER_INTERFACE = 'Pyz\Zed\FooBar\Persistence\FooBarEntityManagerInterface';

    /**
     * @var string
     */
    public const PROJECT_DATA_IMPORT_BUSINESS_FACTORY = 'Pyz\Zed\FooBar\Business\FooBarBusinessFactory';

    /**
     * @var string
     */
    public const PROJECT_ZED_CHECKOUT_DO_SAVE_ORDER_PLUGIN = 'Pyz\Zed\FooBar\Communication\Plugin\Checkout\TestPaymentCheckoutDoSaveOrderPlugin';

    /**
     * @var string
     */
    public const PROJECT_ZED_CHECKOUT_POST_SAVE_PLUGIN = 'Pyz\Zed\FooBar\Communication\Plugin\Checkout\TestPaymentCheckoutPostSavePlugin';

    /**
     * @var string
     */
    public const PROJECT_ZED_CHECKOUT_PRE_CONDITION_PLUGIN = 'Pyz\Zed\FooBar\Communication\Plugin\Checkout\TestPaymentCheckoutPreConditionPlugin';

    /**
     * @var string
     */
    public const PROJECT_ZED_ORDER_PAYMENT_EXPANDER_PLUGIN = 'Pyz\Zed\FooBar\Communication\Plugin\TestPaymentOrderPaymentExpanderPlugin';

    /**
     * @var string
     */
    public const PROJECT_ZED_PLUGIN_OMS_COMMAND_BY_ORDER_PLUGIN = 'Pyz\Zed\FooBar\Communication\Plugin\Oms\Command\TestPaymentCommandByOrder';

    /**
     * @var string
     */
    public const PROJECT_YVES_PLUGIN_SUB_FORM_PLUGIN = 'Pyz\Yves\FooBar\Plugin\TestPaymentSubFormPlugin';

    /**
     * @var string
     */
    public const PROJECT_GLUE_BACKEND_API_RESOURCE_PLUGIN = 'Pyz\Glue\FooBarsBackendApi\Plugin\FooBarsBackendApiResource';

    /**
     * @var string
     */
    public const ZED_BUSINESS_VALIDATOR = 'Spryker\Zed\FooBar\Business\ZipZap\Validator\ZipZapValidator';

    /**
     * @var string
     */
    public const ZED_BUSINESS_VALIDATOR_INTERFACE = 'Spryker\Zed\FooBar\Business\ZipZap\Validator\ZipZapValidatorInterface';

    /**
     * @var string
     */
    public const GLUE_RESOURCE = 'Spryker\Glue\FooBar\BarResource';

    /**
     * @var string
     */
    public const PROJECT_GLUE_RESOURCE = 'Pyz\Glue\FooBar\BarResource';

    /**
     * @var string
     */
    public const YVES_WIDGET = 'SprykerShop\Yves\FooBar\Widget\ZipZapWidget';

    /**
     * @var string
     */
    public const PROJECT_YVES_WIDGET = 'Pyz\Yves\FooBar\Widget\ZipZapWidget';

    /**
     * @var string
     */
    public const ZED_GATEWAY_CONTROLLER = 'Spryker\Zed\FooBar\Communication\Controller\GatewayController';

    /**
     * @var string
     */
    public const PROJECT_ZED_GATEWAY_CONTROLLER = 'Pyz\Zed\FooBar\Communication\Controller\GatewayController';

    /**
     * @var string
     */
    public const ZED_COMMUNICATION_FACTORY = 'Spryker\Zed\FooBar\Communication\FooBarCommunicationFactory';

    /**
     * @var string
     */
    public const PROJECT_ZED_COMMUNICATION_FACTORY = 'Pyz\Zed\FooBar\Communication\FooBarCommunicationFactory';

    /**
     * @var string
     */
    public const SERVICE_DEPENDENCY_PROVIDER = 'Spryker\Service\FooBar\FooBarDependencyProvider';

    /**
     * @var string
     */
    public const PROJECT_SERVICE_DEPENDENCY_PROVIDER = 'Pyz\Service\FooBar\FooBarDependencyProvider';

    /**
     * @var string
     */
    public const SERVICE_FACTORY = 'Spryker\Service\FooBar\FooBarFactory';

    /**
     * @var string
     */
    public const PROJECT_SERVICE_FACTORY = 'Pyz\Service\FooBar\FooBarFactory';

    /**
     * @var string
     */
    public const SERVICE = 'Spryker\Service\FooBar\FooBarService';

    /**
     * @var string
     */
    public const PROJECT_SERVICE = 'Pyz\Service\FooBar\FooBarService';

    /**
     * @var string
     */
    public const PROJECT_ZED_PERSISTENCE_FACTORY = 'Pyz\Zed\FooBar\Persistence\FooBarPersistenceFactory';

    /**
     * @var string
     */
    public const ZED_ABSTRACT_COMMUNICATION_FACTORY = 'Spryker\Zed\Kernel\Communication\AbstractCommunicationFactory';

    /**
     * @var string
     */
    public const ZED_ABSTRACT_CONTROLLER = 'Spryker\Zed\Kernel\Communication\Controller\AbstractController';

    /**
     * @var string
     */
    public const ZED_ABSTRACT_ENTITY_MANAGER = 'Spryker\Zed\Kernel\Persistence\AbstractEntityManager';

    /**
     * @var string
     */
    public const ZED_ABSTRACT_PERSISTENCE_FACTORY = 'Spryker\Zed\Kernel\Persistence\AbstractPersistenceFactory';

    /**
     * @var string
     */
    public const ZED_ABSTRACT_REPOSITORY = 'Spryker\Zed\Kernel\Persistence\AbstractRepository';

    /**
     * @var string
     */
    public const CLIENT_ABSTRACT_DEPENDENCY_PROVIDER = 'Spryker\Client\Kernel\AbstractDependencyProvider';

    /**
     * @var string
     */
    public const CLIENT_ABSTRACT_FACTORY = 'Spryker\Client\Kernel\AbstractFactory';

    /**
     * @var string
     */
    public const ABSTRACT_CLIENT = 'Spryker\Client\Kernel\AbstractClient';

    /**
     * @var string
     */
    public const GLUE_ABSTRACT_DEPENDENCY_PROVIDER = 'Spryker\Glue\Kernel\Backend\AbstractBundleDependencyProvider';

    /**
     * @var string
     */
    public const GLUE_ABSTRACT_FACTORY = 'Spryker\Glue\Kernel\Backend\AbstractFactory';

    /**
     * @var string
     */
    public const GLUE_ABSTRACT_REST_RESOURCE = 'Spryker\Glue\Kernel\AbstractRestResource';

    /**
     * @var string
     */
    public const GLUE_ABSTRACT_CONTROLLER = 'Spryker\Glue\Kernel\Controller\AbstractController';

    /**
     * @var string
     */
    public const SERVICE_ABSTRACT_DEPENDENCY_PROVIDER = 'Spryker\Service\Kernel\AbstractBundleDependencyProvider';

    /**
     * @var string
     */
    public const SERVICE_ABSTRACT_FACTORY = 'Spryker\Service\Kernel\AbstractServiceFactory';

    /**
     * @var string
     */
    public const ABSTRACT_SERVICE = 'Spryker\Service\Kernel\AbstractService';

    /**
     * @var string
     */
    public const YVES_ABSTRACT_DEPENDENCY_PROVIDER = 'Spryker\Yves\Kernel\AbstractBundleDependencyProvider';

    /**
     * @var string
     */
    public const YVES_ABSTRACT_FACTORY = 'Spryker\Yves\Kernel\AbstractFactory';

    /**
     * @var string
     */
    public const YVES_ABSTRACT_WIDGET = 'Spryker\Yves\Kernel\Widget\AbstractWidget';

    /**
     * @var string
     */
    public const ZED_ABSTRACT_DEPENDENCY_PROVIDER = 'Spryker\Zed\Kernel\AbstractBundleDependencyProvider';

    /**
     * @var string
     */
    public const ZED_ABSTRACT_FACADE = 'Spryker\Zed\Kernel\Business\AbstractFacade';

    /**
     * @var string
     */
    public const ZED_ABSTRACT_FACTORY = 'Spryker\Zed\Kernel\Business\AbstractBusinessFactory';

    /**
     * @var string
     */
    public const YVES_ABSTRACT_CONTROLLER = 'Spryker\Yves\Kernel\Controller\AbstractController';
}
