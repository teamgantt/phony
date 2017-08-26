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

namespace Eloquent\Phony\Invocation;

/**
 * An abstract base class for implementing wrapped invocables.
 */
abstract class AbstractWrappedInvocable extends AbstractInvocable implements
    WrappedInvocable
{
    /**
     * Construct a new wrapped invocable.
     *
     * @param callable|null $callback The callback.
     * @param string        $label    The label.
     */
    public function __construct($callback = null, string $label = '')
    {
        if (!$callback) {
            $this->isAnonymous = true;
            $this->callback = function () {};
        } else {
            $this->isAnonymous = false;
            $this->callback = $callback;
        }

        $this->label = $label;
    }

    /**
     * Returns true if anonymous.
     *
     * @return bool True if anonymous.
     */
    public function isAnonymous(): bool
    {
        return $this->isAnonymous;
    }

    /**
     * Get the callback.
     *
     * @return callable The callback.
     */
    public function callback()
    {
        return $this->callback;
    }

    /**
     * Set the label.
     *
     * @param string $label The label.
     *
     * @return $this This invocable.
     */
    public function setLabel(string $label): WrappedInvocable
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get the label.
     *
     * @return string The label.
     */
    public function label(): string
    {
        return $this->label;
    }

    protected $isAnonymous;
    protected $callback;
    protected $label;
}
