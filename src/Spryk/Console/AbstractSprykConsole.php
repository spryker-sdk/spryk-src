<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerSdk\Spryk\Console;

use SprykerSdk\Spryk\SprykFacadeInterface;
use Symfony\Component\Console\Command\Command;

abstract class AbstractSprykConsole extends Command
{
    /**
     * @var int
     */
    protected const CODE_SUCCESS = 0;

    /**
     * @var int
     */
    protected const CODE_ERROR = 1;

    /**
     * @var int
     */
    protected const CODE_WARNING = 2;

    /**
     * @param \SprykerSdk\Spryk\SprykFacadeInterface $facade
     * @param string|null $name
     */
    public function __construct(protected SprykFacadeInterface $facade, ?string $name = null)
    {
        parent::__construct($name);
    }

    /**
     * @return \SprykerSdk\Spryk\SprykFacadeInterface
     */
    public function getFacade(): SprykFacadeInterface
    {
        return $this->facade;
    }
}
