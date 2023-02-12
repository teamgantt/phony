<?php

declare(strict_types=1);

namespace Eloquent\Phony\Mock\Builder\Method;

use Eloquent\Phony\Test\TestClassA;
use Eloquent\Phony\Test\TestInterfaceA;
use Eloquent\Phony\Test\WithDynamicProperties;
use PHPUnit\Framework\TestCase;
use ReflectionMethod;

class RealMethodDefinitionTest extends TestCase
{
    use WithDynamicProperties;

    public function testConstructorWithPublicStatic()
    {
        $this->method = new ReflectionMethod(TestClassA::class . '::testClassAStaticMethodA');
        $this->name = 'name';
        $this->subject = new RealMethodDefinition($this->method, $this->name);

        $this->assertTrue($this->subject->isCallable());
        $this->assertTrue($this->subject->isStatic());
        $this->assertFalse($this->subject->isCustom());
        $this->assertSame('public', $this->subject->accessLevel());
        $this->assertSame($this->name, $this->subject->name());
        $this->assertSame($this->method, $this->subject->method());
        $this->assertNull($this->subject->callback());
    }

    public function testConstructorWithPublicNonStatic()
    {
        $this->method = new ReflectionMethod(TestClassA::class . '::testClassAMethodA');
        $this->name = 'name';
        $this->subject = new RealMethodDefinition($this->method, $this->name);

        $this->assertTrue($this->subject->isCallable());
        $this->assertFalse($this->subject->isStatic());
        $this->assertFalse($this->subject->isCustom());
        $this->assertSame('public', $this->subject->accessLevel());
        $this->assertSame($this->name, $this->subject->name());
        $this->assertSame($this->method, $this->subject->method());
        $this->assertNull($this->subject->callback());
    }

    public function testConstructorWithProtectedStatic()
    {
        $this->method = new ReflectionMethod(TestClassA::class . '::testClassAStaticMethodC');
        $this->name = 'name';
        $this->subject = new RealMethodDefinition($this->method, $this->name);

        $this->assertTrue($this->subject->isCallable());
        $this->assertTrue($this->subject->isStatic());
        $this->assertFalse($this->subject->isCustom());
        $this->assertSame('protected', $this->subject->accessLevel());
        $this->assertSame($this->name, $this->subject->name());
        $this->assertSame($this->method, $this->subject->method());
        $this->assertNull($this->subject->callback());
    }

    public function testConstructorWithProtectedNonStatic()
    {
        $this->method = new ReflectionMethod(TestClassA::class . '::testClassAMethodC');
        $this->name = 'name';
        $this->subject = new RealMethodDefinition($this->method, $this->name);

        $this->assertTrue($this->subject->isCallable());
        $this->assertFalse($this->subject->isStatic());
        $this->assertFalse($this->subject->isCustom());
        $this->assertSame('protected', $this->subject->accessLevel());
        $this->assertSame($this->name, $this->subject->name());
        $this->assertSame($this->method, $this->subject->method());
        $this->assertNull($this->subject->callback());
    }

    public function testConstructorWithUncallable()
    {
        $this->method = new ReflectionMethod(TestInterfaceA::class . '::testClassAMethodA');
        $this->name = 'name';
        $this->subject = new RealMethodDefinition($this->method, $this->name);

        $this->assertFalse($this->subject->isCallable());
    }
}
