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

namespace Eloquent\Phony\Spy;

use ArrayAccess;
use Countable;
use Iterator;

/**
 * The interface implemented by iterable spies.
 */
interface IterableSpy extends ArrayAccess, Countable, Iterator
{
    /**
     * Get the original iterable value.
     *
     * @return iterable The original value.
     */
    public function iterable();
}
