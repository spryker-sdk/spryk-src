<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Model\Spryk\Executor\ConditionMatcher;

use RuntimeException;
use SprykerSdk\Spryk\Model\Spryk\Definition\Argument\Collection\ArgumentCollectionInterface;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

class ConditionMatcher implements ConditionMatcherInterface
{
    /**
     * @var array<string>
     */
    protected const RESERVED_KEYWORDS = ['true', 'false'];

    /**
     * @var \Symfony\Component\ExpressionLanguage\ExpressionLanguage
     */
    protected $expressionLanguage;

    /**
     * @param \Symfony\Component\ExpressionLanguage\ExpressionLanguage $expressionLanguage
     */
    public function __construct(ExpressionLanguage $expressionLanguage)
    {
        $this->expressionLanguage = $expressionLanguage;
    }

    /**
     * @param string $conditionString
     * @param \SprykerSdk\Spryk\Model\Spryk\Definition\Argument\Collection\ArgumentCollectionInterface $argumentCollection
     *
     * @throws \RuntimeException
     *
     * @return bool
     */
    public function match(string $conditionString, ArgumentCollectionInterface $argumentCollection): bool
    {
        $extractedArguments = [];

        //get all the words that not surrounded by ' or "
        preg_match_all('/(?<![\'"])(\b[\w]+\b)(?![\'"])/', $conditionString, $extractedArguments);

        $arguments = [];

        $argumentCandidates = array_unique($extractedArguments[0]);

        foreach ($argumentCandidates as $argumentCandidate) {
            if (
                in_array($argumentCandidate, static::RESERVED_KEYWORDS, true)
                || !$argumentCollection->hasArgument($argumentCandidate, true)
            ) {
                continue;
            }

            $arguments[$argumentCandidate] = $argumentCollection->getArgument($argumentCandidate, true)->getValue();
        }

        $result = $this->expressionLanguage->evaluate($conditionString, $arguments);

        if (!is_bool($result)) {
            throw new RuntimeException(sprintf('Condition %s should return a bool result', $conditionString));
        }

        return $result;
    }
}
