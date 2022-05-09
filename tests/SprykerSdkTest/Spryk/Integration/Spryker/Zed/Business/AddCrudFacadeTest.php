<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Spryk\Integration\Spryker\Zed\Business;

use Codeception\Test\Unit;
use SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\Resolved\ResolvedXmlInterface;
use SprykerSdkTest\Module\ClassName;
use SprykerSdkTest\SprykIntegrationTester;

/**
 * @group SprykerSdkTest
 * @group Spryk
 * @group Integration
 * @group Spryker
 * @group Zed
 * @group Business
 * @group AddCrudFacadeTest
 */
class AddCrudFacadeTest extends Unit
{
    protected SprykIntegrationTester $tester;

    /**
     * @return void
     */
    public function testCrudFacadeCodeIsGenerated(): void
    {
        $this->tester->run($this, [
            '--module' => 'FooBar',
            '--domainEntity' => 'ZipZap',
        ]);

        $this->tester->assertClassDoesNotExist(ClassName::ZED_FACADE_TEST);

        // DependencyProvider constants
        $this->tester->assertClassHasConstant(ClassName::ZED_DEPENDENCY_PROVIDER, 'PLUGINS_ZIP_ZAP_PRE_CREATE', 'PLUGINS_ZIP_ZAP_PRE_CREATE', 'public');
        $this->tester->assertClassHasConstant(ClassName::ZED_DEPENDENCY_PROVIDER, 'PLUGINS_ZIP_ZAP_POST_CREATE', 'PLUGINS_ZIP_ZAP_POST_CREATE', 'public');
        $this->tester->assertClassHasConstant(ClassName::ZED_DEPENDENCY_PROVIDER, 'PLUGINS_ZIP_ZAP_PRE_UPDATE', 'PLUGINS_ZIP_ZAP_PRE_UPDATE', 'public');
        $this->tester->assertClassHasConstant(ClassName::ZED_DEPENDENCY_PROVIDER, 'PLUGINS_ZIP_ZAP_POST_UPDATE', 'PLUGINS_ZIP_ZAP_POST_UPDATE', 'public');
        $this->tester->assertClassHasConstant(ClassName::ZED_DEPENDENCY_PROVIDER, 'PLUGINS_ZIP_ZAP_EXPANDER', 'PLUGINS_ZIP_ZAP_EXPANDER', 'public');
        // DependencyProvider provide method
        $this->tester->assertClassOrInterfaceHasMethod(ClassName::ZED_DEPENDENCY_PROVIDER, 'provideBusinessLayerDependencies');

        // DependencyProvider add/get methods
        $this->tester->assertClassOrInterfaceHasMethod(ClassName::ZED_DEPENDENCY_PROVIDER, 'addZipZapPreCreatePlugins');
        $this->tester->assertClassOrInterfaceHasMethod(ClassName::ZED_DEPENDENCY_PROVIDER, 'getZipZapPreCreatePlugins');
        $this->tester->assertClassOrInterfaceHasMethod(ClassName::ZED_DEPENDENCY_PROVIDER, 'addZipZapPostCreatePlugins');
        $this->tester->assertClassOrInterfaceHasMethod(ClassName::ZED_DEPENDENCY_PROVIDER, 'getZipZapPostCreatePlugins');

        $this->tester->assertClassOrInterfaceHasMethod(ClassName::ZED_DEPENDENCY_PROVIDER, 'addZipZapPreUpdatePlugins');
        $this->tester->assertClassOrInterfaceHasMethod(ClassName::ZED_DEPENDENCY_PROVIDER, 'getZipZapPreUpdatePlugins');
        $this->tester->assertClassOrInterfaceHasMethod(ClassName::ZED_DEPENDENCY_PROVIDER, 'addZipZapPostUpdatePlugins');
        $this->tester->assertClassOrInterfaceHasMethod(ClassName::ZED_DEPENDENCY_PROVIDER, 'getZipZapPostUpdatePlugins');

        $this->tester->assertClassOrInterfaceHasMethod(ClassName::ZED_DEPENDENCY_PROVIDER, 'addZipZapExpanderPlugins');
        $this->tester->assertClassOrInterfaceHasMethod(ClassName::ZED_DEPENDENCY_PROVIDER, 'getZipZapExpanderPlugins');

        // BusinessFactory create/get methods
        $this->tester->assertClassOrInterfaceHasMethod(ClassName::ZED_BUSINESS_FACTORY, 'createZipZapIdentifierBuilder');
        $this->tester->assertClassOrInterfaceHasMethod(ClassName::ZED_BUSINESS_FACTORY, 'createZipZapCreateValidator');
        $this->tester->assertClassOrInterfaceHasMethod(ClassName::ZED_BUSINESS_FACTORY, 'getZipZapCreateValidatorRules');
        $this->tester->assertClassOrInterfaceHasMethod(ClassName::ZED_BUSINESS_FACTORY, 'createZipZapUpdateValidator');
        $this->tester->assertClassOrInterfaceHasMethod(ClassName::ZED_BUSINESS_FACTORY, 'getZipZapUpdateValidatorRules');
        $this->tester->assertClassOrInterfaceHasMethod(ClassName::ZED_BUSINESS_FACTORY, 'getZipZapUpdateValidatorRulePlugins');
        $this->tester->assertClassOrInterfaceHasMethod(ClassName::ZED_BUSINESS_FACTORY, 'getZipZapCreateValidatorRulePlugins');

        // Identifier
        $this->assertFileExists(
            $this->tester->getSprykerModuleDirectory()
            . 'src/Spryker/Zed/FooBar/Business/ZipZap/IdentifierBuilder/ZipZapIdentifierBuilder.php',
        );
        $this->assertFileExists(
            $this->tester->getSprykerModuleDirectory()
            . 'src/Spryker/Zed/FooBar/Business/ZipZap/IdentifierBuilder/ZipZapIdentifierBuilderInterface.php',
        );

        // Writer
        $this->assertFileExists(
            $this->tester->getSprykerModuleDirectory()
            . 'src/Spryker/Zed/FooBar/Business/ZipZap/Writer/ZipZapCreator.php',
        );
        $this->assertFileExists(
            $this->tester->getSprykerModuleDirectory()
            . 'src/Spryker/Zed/FooBar/Business/ZipZap/Writer/ZipZapCreatorInterface.php',
        );

        // Deleter
        $this->assertFileExists(
            $this->tester->getSprykerModuleDirectory()
            . 'src/Spryker/Zed/FooBar/Business/ZipZap/Deleter/ZipZapDeleter.php',
        );

        // Reader
        $this->assertFileExists(
            $this->tester->getSprykerModuleDirectory()
            . 'src/Spryker/Zed/FooBar/Business/ZipZap/Reader/ZipZapReader.php',
        );
        $this->assertFileExists(
            $this->tester->getSprykerModuleDirectory()
            . 'src/Spryker/Zed/FooBar/Business/ZipZap/Reader/ZipZapReaderInterface.php',
        );

        # Updater Model
        $this->assertFileExists(
            $this->tester->getSprykerModuleDirectory()
            . 'src/Spryker/Zed/FooBar/Business/ZipZap/Writer/ZipZapUpdater.php',
        );
        $this->assertFileExists(
            $this->tester->getSprykerModuleDirectory()
            . 'src/Spryker/Zed/FooBar/Business/ZipZap/Writer/ZipZapUpdaterInterface.php',
        );

        $this->tester->assertClassOrInterfaceHasMethod('Spryker\Zed\FooBar\Business\ZipZap\Writer\ZipZapUpdater', 'updateZipZapCollection');
        $this->tester->assertClassOrInterfaceHasMethod('Spryker\Zed\FooBar\Business\ZipZap\Writer\ZipZapUpdaterInterface', 'updateZipZapCollection');
        $this->tester->assertClassOrInterfaceHasMethod('Spryker\Zed\FooBar\Business\FooBarBusinessFactory', 'createZipZapUpdater');

        // Transfers
        $transferXml = $this->tester->getFileResolver()->resolve(
            $this->tester->getSprykerModuleDirectory() . 'src/Spryker/Shared/FooBar/Transfer/foo_bar.transfer.xml',
        );
        $this->assertInstanceOf(ResolvedXmlInterface::class, $transferXml);

        $this->tester->assertResolvedXmlHasTransfer($transferXml, 'ZipZapCriteria');
        $this->tester->assertResolvedXmlHasTransfer($transferXml, 'ZipZapConditions');
        $this->tester->assertResolvedXmlHasTransfer($transferXml, 'ZipZapCollection');
        $this->tester->assertResolvedXmlHasTransfer($transferXml, 'Sort');
        $this->tester->assertResolvedXmlHasTransfer($transferXml, 'Pagination');
        $this->tester->assertResolvedXmlHasTransfer($transferXml, 'ZipZapCollectionRequest');
        $this->tester->assertResolvedXmlHasTransfer($transferXml, 'ZipZapCollectionResponse');
        $this->tester->assertResolvedXmlHasTransfer($transferXml, 'ZipZapCollectionDeleteCriteria');
        $this->tester->assertResolvedXmlHasTransfer($transferXml, 'Error');

        // Test helper
        $this->assertFileExists(
            $this->tester->getSprykerModuleDirectory() . 'tests/SprykerTest/Zed/FooBar/_support/Helper/ZipZapCrudHelper.php',
        );

        $this->assertPluginsExist();
        $this->assertFacadeContainsCrudMethods();
        $this->assertRepositoryContainsCrudMethods();
        $this->assertValidatorExists();
        $this->assertPersistenceFactoryContainsMethods();
        $this->assertPersistenceEntityManagerExists();
        $this->assertPersistenceEntityManagerFactoryExists();
    }

    /**
     * @return void
     */
    protected function assertPluginsExist(): void
    {
        $this->assertFileExists(
            $this->tester->getSprykerModuleDirectory()
            . 'src/Spryker/Zed/FooBarExtension/Dependency/Plugin/ZipZap/Expander/ZipZapExpanderPluginInterface.php',
        );

        $this->assertFileExists(
            $this->tester->getSprykerModuleDirectory()
            . 'src/Spryker/Zed/FooBarExtension/Dependency/Plugin/ZipZap/Validator/ZipZapValidatorRulePluginInterface.php',
        );
    }

    /**
     * @return void
     */
    protected function assertFacadeContainsCrudMethods(): void
    {
        $this->tester->assertClassOrInterfaceHasMethod(ClassName::ZED_FACADE, 'getZipZapCollection');
        $this->tester->assertClassOrInterfaceHasMethod(ClassName::ZED_FACADE, 'createZipZapCollection');
        $this->tester->assertClassOrInterfaceHasMethod(ClassName::ZED_FACADE, 'updateZipZapCollection');
        $this->tester->assertClassOrInterfaceHasMethod(ClassName::ZED_FACADE, 'deleteZipZapCollection');
    }

    /**
     * @return void
     */
    protected function assertRepositoryContainsCrudMethods(): void
    {
        $this->tester->assertClassOrInterfaceHasMethod(ClassName::ZED_REPOSITORY, 'getZipZapCollection');
        $this->tester->assertClassOrInterfaceHasMethod(ClassName::ZED_REPOSITORY, 'applyZipZapFilters');
        $this->tester->assertClassOrInterfaceHasMethod(ClassName::ZED_REPOSITORY, 'getZipZapDeleteCollection');
        $this->tester->assertClassOrInterfaceHasMethod(ClassName::ZED_REPOSITORY, 'applyZipZapDeleteFilters');
    }

    /**
     * @return void
     */
    protected function assertValidatorExists(): void
    {
        $this->assertFileExists(
            $this->tester->getSprykerModuleDirectory()
            . 'src/Spryker/Zed/FooBar/Business/ZipZap/Validator/ZipZapValidator.php',
        );

        $this->tester->assertClassOrInterfaceHasMethod('Spryker\Zed\FooBar\Business\ZipZap\Validator\ZipZapValidator', 'validate');
        $this->tester->assertClassOrInterfaceHasMethod('Spryker\Zed\FooBar\Business\ZipZap\Validator\ZipZapValidator', 'validateCollection');
    }

    /**
     * @return void
     */
    protected function assertPersistenceFactoryContainsMethods(): void
    {
        $this->tester->assertClassOrInterfaceHasMethod(ClassName::ZED_PERSISTENCE_FACTORY, 'createZipZapQuery');
        $this->tester->assertClassOrInterfaceHasMethod(ClassName::ZED_PERSISTENCE_FACTORY, 'createZipZapMapper');
    }

    /**
     * @return void
     */
    protected function assertPersistenceMapperExists(): void
    {
        $this->assertFileExists(
            $this->tester->getSprykerModuleDirectory()
            . 'src/Spryker/Zed/FooBar/Persistence/ZipZap/Mapper/ZipZapMapper.php',
        );

        $this->tester->assertClassOrInterfaceHasMethod('Spryker\Zed\FooBar\Persistence\ZipZap\Mapper\ZipZapMapper', 'mapZipZapTransferToZipZapEntity');
        $this->tester->assertClassOrInterfaceHasMethod('Spryker\Zed\FooBar\Persistence\ZipZap\Mapper\ZipZapMapper', 'mapZipZapEntityToZipZapTransfer');
        $this->tester->assertClassOrInterfaceHasMethod('Spryker\Zed\FooBar\Persistence\ZipZap\Mapper\ZipZapMapper', 'mapZipZapEntityCollectionToZipZapCollectionResponseTransfer');

        $this->assertFileExists(
            $this->tester->getSprykerModuleDirectory()
            . 'src/Spryker/Zed/FooBar/Persistence/ZipZap/Mapper/ZipZapMapperInterface.php',
        );

        $this->tester->assertClassOrInterfaceHasMethod('Spryker\Zed\FooBar\Persistence\ZipZap\Mapper\ZipZapMapperInterface', 'mapZipZapTransferToZipZapEntity');
        $this->tester->assertClassOrInterfaceHasMethod('Spryker\Zed\FooBar\Persistence\ZipZap\Mapper\ZipZapMapperInterface', 'mapZipZapEntityToZipZapTransfer');
        $this->tester->assertClassOrInterfaceHasMethod('Spryker\Zed\FooBar\Persistence\ZipZap\Mapper\ZipZapMapperInterface', 'mapZipZapEntityCollectionToZipZapCollectionResponseTransfer');
    }

    /**
     * @return void
     */
    protected function assertPersistenceEntityManagerExists(): void
    {
        $this->assertFileExists(
            $this->tester->getSprykerModuleDirectory()
            . 'src/Spryker/Zed/FooBar/Persistence/FooBarEntityManager.php',
        );

        $this->tester->assertClassOrInterfaceHasMethod('Spryker\Zed\FooBar\Persistence\FooBarEntityManager', 'createZipZap');
        $this->tester->assertClassOrInterfaceHasMethod('Spryker\Zed\FooBar\Persistence\FooBarEntityManager', 'updateZipZap');
        $this->tester->assertClassOrInterfaceHasMethod('Spryker\Zed\FooBar\Persistence\FooBarEntityManager', 'deleteZipZap');

        $this->assertFileExists(
            $this->tester->getSprykerModuleDirectory()
            . 'src/Spryker/Zed/FooBar/Persistence/FooBarEntityManagerInterface.php',
        );

        $this->tester->assertClassOrInterfaceHasMethod('Spryker\Zed\FooBar\Persistence\FooBarEntityManagerInterface', 'createZipZap');
        $this->tester->assertClassOrInterfaceHasMethod('Spryker\Zed\FooBar\Persistence\FooBarEntityManagerInterface', 'updateZipZap');
        $this->tester->assertClassOrInterfaceHasMethod('Spryker\Zed\FooBar\Persistence\FooBarEntityManagerInterface', 'deleteZipZap');
    }

    /**
     * @return void
     */
    protected function assertPersistenceEntityManagerFactoryExists(): void
    {
        $this->assertFileExists(
            $this->tester->getSprykerModuleDirectory()
            . 'src/Spryker/Zed/FooBar/Persistence/FooBarPersistenceFactory.php',
        );
    }
}
