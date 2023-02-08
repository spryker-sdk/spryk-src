<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Module;

use Codeception\Module;
use SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\Resolved\ResolvedClassInterface;
use SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\Resolved\ResolvedYmlInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;

class YmlModule extends Module
{
    /**
     * @param string $content
     * @param string $organization
     * @param string $application
     * @param string $moduleName
     *
     * @return string
     */
    public function haveCodeceptionSuiteConfiguration(string $content, string $organization, string $application, string $moduleName): string
    {
        $filePath = $this->getSprykHelper()->getVirtualDirectory() . sprintf('/vendor/spryker/spryker/Bundles/%s/tests/%sTest/%s/%s/codeception.yml', $moduleName, $organization, $application, $moduleName);
        $directory = dirname($filePath);

        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        file_put_contents($filePath, $content);

        return $filePath;
    }

    /**
     * @param string $propertyPath
     * @param string $expectedInArray
     * @param string $fileName
     *
     * @return void
     */
    public function assertYmlArrayContainsElement(string $propertyPath, string $expectedInArray, string $fileName): void
    {
        $yml = $this->getResolvedYml($fileName);

        $propertyAccessor = PropertyAccess::createPropertyAccessor();
        $ymlElement = $propertyAccessor->getValue($yml->getDecodedYml(), $propertyPath);

        $this->assertNotNull($ymlElement);

        $this->assertTrue(in_array($expectedInArray, $ymlElement), sprintf('Could not find the element "%s" in the resolved file with property accesor path "%s"', $expectedInArray, $propertyPath));

        $ymlElement = array_filter($ymlElement, function ($ymlElement) {
            return !is_array($ymlElement);
        });

        $countedValues = array_count_values($ymlElement);
        $this->assertTrue($countedValues[$expectedInArray] === 1, sprintf('Expected to find only one of the element "%s" in the resolved file with property accesor path "%s" but found "%s"', $expectedInArray, $propertyPath, $countedValues[$expectedInArray]));
    }

    /**
     * @param string $fileName
     *
     * @return \SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\Resolved\ResolvedYmlInterface
     */
    protected function getResolvedYml(string $fileName): ResolvedYmlInterface
    {
        /** @var \SprykerSdk\Spryk\Model\Spryk\Builder\Resolver\Resolved\ResolvedYmlInterface $resolved */
        $resolved = $this->getSprykHelper()->getFileResolver()->resolve($fileName);

        $this->assertInstanceOf(ResolvedYmlInterface::class, $resolved, sprintf('Expected file "%s" not found.', $fileName));

        return $resolved;
    }

    /**
     * @return \SprykerSdkTest\Module\SprykHelper
     */
    protected function getSprykHelper(): SprykHelper
    {
        /** @var \SprykerSdkTest\Module\SprykHelper $sprykHelper */
        $sprykHelper = $this->getModule(SprykHelper::class);

        return $sprykHelper;
    }
}
