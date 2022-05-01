<?php

namespace SprykerSdk\Spryk\Model\Spryk\Definition\Argument\Callback;

use SprykerSdk\Spryk\Model\Spryk\Definition\Argument\Collection\ArgumentCollectionInterface;

class ResolveOrganizationInFqcn implements CallbackInterface
{
    protected const CALLBACK_NAME = 'ResolveOrganizationInFqcn';

    protected const ARGUMENT_ORGANIZATION = 'organization';

    /**
     * @return string
     */
    public function getName(): string
    {
        return static::CALLBACK_NAME;
    }

    /**
     * @param ArgumentCollectionInterface $argumentCollection
     * @param mixed $value
     *
     * @return mixed|void
     */
    public function getValue(ArgumentCollectionInterface $argumentCollection, $value)
    {
        if (!$argumentCollection->hasArgument(static::ARGUMENT_ORGANIZATION)) {
            return $value;
        }

        $organization = $argumentCollection->getArgument(static::ARGUMENT_ORGANIZATION)->getValue();

        return (string)preg_replace('/(\w+)/', $organization, $value, 1);
    }
}
