<?php

/*
 * This file is part of the Phony package.
 *
 * Copyright © 2014 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Eloquent\Phony\Stub\Answer;

/**
 * Represents instructions on how to call an argument callback.
 *
 * @internal
 */
class ArgumentCallInstructions extends AbstractCallInstructions
{
    /**
     * Construct a new set of argument call instructions.
     *
     * @param integer                   $index                The argument index.
     * @param array<integer,mixed>|null $arguments            The arguments.
     * @param boolean|null              $prefixSelf           True if the self value should be prefixed.
     * @param boolean|null              $suffixArgumentsArray True if arguments should be appended as an array.
     * @param boolean|null              $suffixArguments      True if arguments should be appended.
     */
    public function __construct(
        $index,
        array $arguments = null,
        $prefixSelf = null,
        $suffixArgumentsArray = null,
        $suffixArguments = null
    ) {
        parent::__construct(
            $arguments,
            $prefixSelf,
            $suffixArgumentsArray,
            $suffixArguments
        );

        $this->index = $index;
    }

    /**
     * Get the argument index.
     *
     * @return integer The argument index.
     */
    public function index()
    {
        return $this->index;
    }

    /**
     * Get the callback.
     *
     * @param array<integer,mixed>|null $arguments The incoming arguments.
     *
     * @return callable|null The callback, or null if no callback is available.
     */
    public function callback(array $arguments = null)
    {
        if (null === $arguments) {
            return null;
        }

        $argumentCount = count($arguments);
        $index = $this->index;

        if ($index < 0) {
            $index = $argumentCount + $index;
        }

        if ($index < 0 || $index >= $argumentCount) {
            return null;
        }

        return $arguments[$index];
    }

    private $index;
}
