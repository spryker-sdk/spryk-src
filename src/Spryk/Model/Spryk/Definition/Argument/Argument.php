<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Model\Spryk\Definition\Argument;

use Stringable;

class Argument implements ArgumentInterface, Stringable
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var mixed
     */
    protected $value;

    /**
     * @var bool
     */
    protected $allowOverride = false;

    /**
     * @var array<string>
     */
    protected $callbacks = [];

    /**
     * @var array<string, mixed>
     */
    protected array $meta = [];

    /**
     * @return $this
     */
    public function setName(string $name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     *
     * @return $this
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * @return bool
     */
    public function getAllowOverride(): bool
    {
        return $this->allowOverride;
    }

    /**
     * @return $this
     */
    public function setAllowOverride(bool $allowOverride)
    {
        $this->allowOverride = $allowOverride;

        return $this;
    }

    /**
     * @return array<string>
     */
    public function getCallbacks(): array
    {
        return $this->callbacks;
    }

    /**
     * @return $this
     */
    public function setCallbacks(array $callbacks)
    {
        $this->callbacks = $callbacks;

        return $this;
    }

    /**
     * @param string $key
     * @param mixed $value
     *
     * @return void
     */
    public function addMeta(string $key, mixed $value): void
    {
        $this->meta[$key] = $value;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        $value = $this->getValue();

        if (is_array($value)) {
            return implode(PHP_EOL, $value);
        }

        return (string)$value;
    }
}
