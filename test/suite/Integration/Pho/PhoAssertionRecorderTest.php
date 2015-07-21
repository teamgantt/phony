<?php

/*
 * This file is part of the Phony package.
 *
 * Copyright © 2015 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Eloquent\Phony\Integration\Pho;

use PHPUnit_Framework_TestCase;
use ReflectionClass;

class PhoAssertionRecorderTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        $this->subject = new PhoAssertionRecorder();
    }

    /**
     * @requires PHP 5.3.4-dev
     */
    public function testCreateFailure()
    {
        $description = 'description';

        $this->setExpectedException('Eloquent\Phony\Integration\Pho\PhoAssertionException', $description);
        $this->subject->createFailure($description);
    }

    public function testInstance()
    {
        $class = get_class($this->subject);
        $reflector = new ReflectionClass($class);
        $property = $reflector->getProperty('instance');
        $property->setAccessible(true);
        $property->setValue(null, null);
        $instance = $class::instance();

        $this->assertInstanceOf($class, $instance);
        $this->assertSame($instance, $class::instance());
    }
}