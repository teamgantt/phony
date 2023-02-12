<?php

declare(strict_types=1);

namespace Eloquent\Phony\Hamcrest;

use Eloquent\Phony\Test\WithDynamicProperties;
use Hamcrest\Matcher;
use Hamcrest\Util;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

class HamcrestMatcherDriverTest extends TestCase
{
    use WithDynamicProperties;

    protected function setUp(): void
    {
        Util::registerGlobalFunctions();

        $this->subject = new HamcrestMatcherDriver();

        $this->matcher = equalTo('x');
    }

    public function testIsAvailable()
    {
        $this->assertTrue($this->subject->isAvailable());
    }

    public function testMatcherClassNames()
    {
        $this->assertSame([Matcher::class], $this->subject->matcherClassNames());
    }

    public function testWrapMatcher()
    {
        $this->assertEquals(new HamcrestMatcher($this->matcher), $this->subject->wrapMatcher($this->matcher));
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
