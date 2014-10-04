<?php

/*
 * This file is part of the Phony package.
 *
 * Copyright © 2014 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Eloquent\Phony\Call\Factory;

use Eloquent\Phony\Call\CallInterface;
use Eloquent\Phony\Call\Event\CalledEventInterface;
use Eloquent\Phony\Call\Event\GeneratorEventInterface;
use Eloquent\Phony\Call\Event\ResponseEventInterface;
use Eloquent\Phony\Call\Event\ReturnedEventInterface;
use Eloquent\Phony\Call\Event\SentValueEventInterface;
use Eloquent\Phony\Call\Event\ThrewEventInterface;
use Exception;

/**
 * The interface implemented by call factories.
 */
interface CallFactoryInterface
{
    /**
     * Record call details by invoking a callback.
     *
     * @param callable|null             $callback  The callback.
     * @param array<integer,mixed>|null $arguments The arguments.
     *
     * @return CallInterface The newly created call.
     */
    public function record(
        $callback = null,
        array $arguments = null
    );

    /**
     * Create a new call.
     *
     * @param CalledEventInterface|null                   $calledEvent     The 'called' event.
     * @param ResponseEventInterface|null                 $responseEvent   The response event, or null if the call has not yet completed.
     * @param array<integer,GeneratorEventInterface>|null $generatorEvents The generator events.
     *
     * @return CallInterface The newly created call.
     */
    public function create(
        CalledEventInterface $calledEvent = null,
        ResponseEventInterface $responseEvent = null,
        array $generatorEvents = null
    );

    /**
     * Create a new 'called' event.
     *
     * @param callable|null             $callback  The callback.
     * @param array<integer,mixed>|null $arguments The arguments.
     *
     * @return CalledEventInterface The newly created event.
     */
    public function createCalledEvent(
        $callback = null,
        array $arguments = null
    );

    /**
     * Create a new response event.
     *
     * @param mixed          $returnValue The return value.
     * @param Exception|null $exception   The thrown exception, or null if no exception was thrown.
     *
     * @return ResponseEventInterface The newly created event.
     */
    public function createResponseEvent(
        $returnValue = null,
        Exception $exception = null
    );

    /**
     * Create a new 'returned' event.
     *
     * @param mixed $returnValue The return value.
     *
     * @return ReturnedEventInterface The newly created event.
     */
    public function createReturnedEvent($returnValue = null);

    /**
     * Create a new 'thrown' event.
     *
     * @param Exception|null $exception The thrown exception.
     *
     * @return ThrewEventInterface The newly created event.
     */
    public function createThrewEvent(Exception $exception = null);

    /**
     * Create a new 'sent value' event.
     *
     * @param mixed $sentValue The sent value.
     *
     * @return SentValueEventInterface The newly created event.
     */
    public function createSentValueEvent($sentValue = null);
}
