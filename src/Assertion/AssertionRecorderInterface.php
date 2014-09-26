<?php

/*
 * This file is part of the Phony package.
 *
 * Copyright © 2014 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Eloquent\Phony\Assertion;

use Eloquent\Phony\Assertion\Exception\AssertionExceptionInterface;
use Exception;

/**
 * The interface implemented by assertion recorders.
 */
interface AssertionRecorderInterface
{
    /**
     * Record that a successful assertion occurred.
     */
    public function recordSuccess();

    /**
     * Record that an assertion failure occurred.
     *
     * @param AssertionExceptionInterface $failure The failure.
     *
     * @throws Exception The appropriate assertion exception.
     */
    public function recordFailure(AssertionExceptionInterface $failure);
}