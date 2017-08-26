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

namespace Eloquent\Phony\Mock\Builder\Method;

use ReflectionFunctionAbstract;

/**
 * Represents a custom method definition.
 */
class CustomMethodDefinition implements MethodDefinition
{
    /**
     * Construct a new custom method definition.
     *
     * @param bool                       $isStatic True if this method is static.
     * @param string                     $name     The name.
     * @param callable                   $callback The callback.
     * @param ReflectionFunctionAbstract $method   The function implementation.
     */
    public function __construct(
        bool $isStatic,
        string $name,
        callable $callback,
        ReflectionFunctionAbstract $method
    ) {
        $this->isStatic = $isStatic;
        $this->name = $name;
        $this->callback = $callback;
        $this->method = $method;
    }

    /**
     * Returns true if this method is callable.
     *
     * @return bool True if this method is callable.
     */
    public function isCallable(): bool
    {
        return true;
    }

    /**
     * Returns true if this method is static.
     *
     * @return bool True if this method is static.
     */
    public function isStatic(): bool
    {
        return $this->isStatic;
    }

    /**
     * Returns true if this method is custom.
     *
     * @return bool True if this method is custom.
     */
    public function isCustom(): bool
    {
        return true;
    }

    /**
     * Get the access level.
     *
     * @return string The access level.
     */
    public function accessLevel(): string
    {
        return 'public';
    }

    /**
     * Get the name.
     *
     * @return string The name.
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * Get the method.
     *
     * @return ReflectionFunctionAbstract The method.
     */
    public function method(): ReflectionFunctionAbstract
    {
        return $this->method;
    }

    /**
     * Get the callback.
     *
     * @return callable|null The callback, or null if this is a real method.
     */
    public function callback()
    {
        return $this->callback;
    }

    private $isStatic;
    private $name;
    private $callback;
    private $method;
}
