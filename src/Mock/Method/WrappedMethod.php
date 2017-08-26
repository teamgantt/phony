<?php

declare(strict_types=1);

/*
 * This file is part of the Phony package.
 *
 * Copyright © 2017 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Eloquent\Phony\Mock\Method;

use Eloquent\Phony\Mock\Handle\Handle;
use Eloquent\Phony\Mock\Mock;

/**
 * The interface implemented by wrapped methods.
 */
interface WrappedMethod
{
    /**
     * Get the name.
     *
     * @return string The name.
     */
    public function name(): string;

    /**
     * Get the handle.
     *
     * @return Handle The handle.
     */
    public function handle(): Handle;

    /**
     * Get the mock.
     *
     * @return Mock|null The mock.
     */
    public function mock();
}
