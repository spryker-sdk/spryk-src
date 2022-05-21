<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdkTest\Spryk\Model\Spryk\Executor;

use Codeception\Test\Unit;
use SprykerSdk\Spryk\Model\Spryk\Definition\Argument\Argument;
use SprykerSdk\Spryk\Model\Spryk\Definition\Argument\Collection\ArgumentCollection;
use SprykerSdk\Spryk\Model\Spryk\Executor\ConditionMatcher\ConditionMatcher;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;
use Symfony\Component\ExpressionLanguage\SyntaxError;

class ConditionMatcherTest extends Unit
{
    /**
     * @var \SprykerSdk\Spryk\Model\Spryk\Executor\ConditionMatcher\ConditionMatcher
     */
    protected $conditionMatcher;

    /**
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();

        if ($this->conditionMatcher === null) {
            $this->conditionMatcher = new ConditionMatcher(new ExpressionLanguage());
        }
    }

    /**
     * @dataProvider getConditionMatcherTestData
     *
     * @param string $condition
     * @param array $arguments
     * @param bool $expectedResult
     *
     * @return void
     */
    public function testContiditionMatcherParseAndExecuteContitionString(string $condition, array $arguments, bool $expectedResult): void
    {
        $this->assertSame($expectedResult, $this->conditionMatcher->match($condition, $this->createArgumentCollection($arguments)));
    }

    /**
     * @return array<array<mixed>>
     */
    public function getConditionMatcherTestData(): array
    {
        return [
            // condition | arguments | expected result
            ['argA === true && argB === true', ['argA' => true, 'argB' => true], true],
            ['argA === "true" && argB === "true"', ['argA' => true, 'argB' => true], false],
            ['argA == "true" && argB == "true"', ['argA' => true, 'argB' => true], true],
            ['(argA === true || argB === true) && argC === true', ['argA' => true, 'argB' => false, 'argC' => true], true],
            ['(argA === true || argB === true) && argC === true', ['argA' => false, 'argB' => true, 'argC' => true], true],
            ['(argA === true || argB === true) && argC === true', ['argA' => true, 'argB' => true, 'argC' => false], false],
            ['(argA || argB) && argC', ['argA' => true, 'argB' => false, 'argC' => true], true],
            ['argA === "aaa" && argB === "bbb"', ['argA' => 'aaa', 'argB' => 'bbb'], true],
            ['argA === 1 && argB === 2', ['argA' => 1, 'argB' => 2], true],
            ['a === 1', ['a' => 1], true],
            ['3 === ((2 + 3) - 2)', [], true],
            ['1 === 5 - 2 * 2', [], true],
        ];
    }

    /**
     * @dataProvider getInvalidConditionMatcherTestData
     *
     * @param string $condition
     * @param array $arguments
     *
     * @return void
     */
    public function testConditionMatcherThrowsExceptionWhenInvalidConditionString(string $condition, array $arguments): void
    {
        $this->expectException(SyntaxError::class);

        $this->conditionMatcher->match($condition, $this->createArgumentCollection($arguments));
    }

    /**
     * @return array<array<mixed>>
     */
    public function getInvalidConditionMatcherTestData(): array
    {
        return [
            // condition | arguments
            ['argA === true && argB === true', ['argA' => true]],
            ['argA === \'true', ['argA' => false]],
            ['argA === "true\'', ['argA' => false]],
        ];
    }

    /**
     * @param array<string, mixed> $arguments
     *
     * @return \SprykerSdk\Spryk\Model\Spryk\Definition\Argument\Collection\ArgumentCollection
     */
    protected function createArgumentCollection(array $arguments): ArgumentCollection
    {
        $argumentCollection = new ArgumentCollection();

        foreach ($arguments as $name => $value) {
            $argumentCollection->addArgument((new Argument())->setName($name)->setValue($value));
        }

        return $argumentCollection;
    }
}
