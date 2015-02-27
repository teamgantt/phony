<?php

/*
 * This file is part of the Phony package.
 *
 * Copyright © 2014 Erin Millard
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Eloquent\Phony\Simpletest;

use Eloquent\Phony\Call\Argument\Arguments;
use Eloquent\Phony\Call\Event\CallEventCollection;
use Eloquent\Phony\Call\Factory\CallVerifierFactory;
use Eloquent\Phony\Integration\Simpletest\SimpletestAssertionRecorder;
use Eloquent\Phony\Matcher\AnyMatcher;
use Eloquent\Phony\Matcher\EqualToMatcher;
use Eloquent\Phony\Matcher\WildcardMatcher;
use Eloquent\Phony\Mock\Proxy\Factory\ProxyFactory;
use Eloquent\Phony\Stub\Factory\StubVerifierFactory;
use Eloquent\Phony\Test\TestEvent;
use PHPUnit_Framework_TestCase;
use ReflectionObject;
use SimpleReporter;
use SimpleTestContext;

class PhonyTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        $this->simpletestContext = new SimpleTestContext();
        $this->simpletestReporter = new SimpleReporter();
        $this->simpletestContext->setReporter($this->simpletestReporter);
        $this->assertionRecorder = new SimpletestAssertionRecorder($this->simpletestContext);

        $reflector = new ReflectionObject($this->assertionRecorder);
        $property = $reflector->getProperty('instance');
        $property->setAccessible(true);
        $property->setValue(null, $this->assertionRecorder);

        $this->callVerifierFactory = new CallVerifierFactory(null, null, $this->assertionRecorder);
        $this->stubVerifierFactory = new StubVerifierFactory(
            null,
            null,
            null,
            null,
            $this->callVerifierFactory,
            $this->assertionRecorder
        );
        $this->proxyFactory = new ProxyFactory(null, $this->stubVerifierFactory);

        $this->eventA = new TestEvent(0, 0.0);
        $this->eventB = new TestEvent(1, 1.0);
    }

    public function testMockBuilder()
    {
        $actual = Phony::mockBuilder('Eloquent\Phony\Test\TestClassA');

        $this->assertInstanceOf('Eloquent\Phony\Mock\Builder\MockBuilder', $actual);
        $this->assertInstanceOf('Eloquent\Phony\Mock\MockInterface', $actual->get());
        $this->assertInstanceOf('Eloquent\Phony\Test\TestClassA', $actual->get());
    }

    public function testMockBuilderFunction()
    {
        $actual = mockBuilder('Eloquent\Phony\Test\TestClassA');

        $this->assertInstanceOf('Eloquent\Phony\Mock\Builder\MockBuilder', $actual);
        $this->assertInstanceOf('Eloquent\Phony\Mock\MockInterface', $actual->get());
        $this->assertInstanceOf('Eloquent\Phony\Test\TestClassA', $actual->get());
    }

    public function testMock()
    {
        $types = array('Eloquent\Phony\Test\TestClassB', 'Countable');
        $arguments = new Arguments(array('a', 'b'));
        $definition = array('propertyA' => 'valueA', 'propertyB' => 'valueB');
        $className = 'PhonyMockSimpletestFacadeTestCreateMock';
        $actual = Phony::mock($types, $arguments, $definition, $className);

        $this->assertInstanceOf('Eloquent\Phony\Mock\Proxy\Stubbing\StubbingProxy', $actual);
        $this->assertInstanceOf($className, $actual->mock());
        $this->assertInstanceOf('Eloquent\Phony\Test\TestClassB', $actual->mock());
        $this->assertInstanceOf('Countable', $actual->mock());
        $this->assertSame(array('a', 'b'), $actual->mock()->constructorArguments);
        $this->assertSame('ab', $actual->mock()->testClassAMethodA('a', 'b'));
    }

    public function testMockWithNullArguments()
    {
        $types = array('Eloquent\Phony\Test\TestClassB', 'Countable');
        $arguments = null;
        $definition = array('propertyA' => 'valueA', 'propertyB' => 'valueB');
        $className = 'PhonyMockSimpletestFacadeTestCreateMockWithNullArguments';
        $actual = Phony::mock($types, $arguments, $definition, $className);

        $this->assertInstanceOf('Eloquent\Phony\Mock\Proxy\Stubbing\StubbingProxy', $actual);
        $this->assertInstanceOf($className, $actual->mock());
        $this->assertInstanceOf('Eloquent\Phony\Mock\MockInterface', $actual->mock());
        $this->assertInstanceOf('Eloquent\Phony\Test\TestClassB', $actual->mock());
        $this->assertInstanceOf('Countable', $actual->mock());
        $this->assertNull($actual->mock()->constructorArguments);
        $this->assertSame('ab', $actual->mock()->testClassAMethodA('a', 'b'));
    }

    public function testMockWithNoArguments()
    {
        $types = array('Eloquent\Phony\Test\TestClassB', 'Countable');
        $actual = Phony::mock($types);

        $this->assertInstanceOf('Eloquent\Phony\Mock\Proxy\Stubbing\StubbingProxy', $actual);
        $this->assertInstanceOf('Eloquent\Phony\Mock\MockInterface', $actual->mock());
        $this->assertInstanceOf('Eloquent\Phony\Test\TestClassB', $actual->mock());
        $this->assertInstanceOf('Countable', $actual->mock());
        $this->assertEquals(array(), $actual->mock()->constructorArguments);
        $this->assertSame('ab', $actual->mock()->testClassAMethodA('a', 'b'));
    }

    public function testMockDefaults()
    {
        $actual = Phony::mock();

        $this->assertInstanceOf('Eloquent\Phony\Mock\Proxy\Stubbing\StubbingProxy', $actual);
        $this->assertInstanceOf('Eloquent\Phony\Mock\MockInterface', $actual->mock());
    }

    public function testMockFunction()
    {
        $types = array('Eloquent\Phony\Test\TestClassB', 'Countable');
        $arguments = new Arguments(array('a', 'b'));
        $definition = array('propertyA' => 'valueA', 'propertyB' => 'valueB');
        $className = 'PhonyMockSimpletestFacadeTestCreateMockFunction';
        $actual = mock($types, $arguments, $definition, $className);

        $this->assertInstanceOf('Eloquent\Phony\Mock\Proxy\Stubbing\StubbingProxy', $actual);
        $this->assertInstanceOf($className, $actual->mock());
        $this->assertInstanceOf('Eloquent\Phony\Test\TestClassB', $actual->mock());
        $this->assertInstanceOf('Countable', $actual->mock());
        $this->assertSame(array('a', 'b'), $actual->mock()->constructorArguments);
        $this->assertSame('ab', $actual->mock()->testClassAMethodA('a', 'b'));
    }

    public function testMockFunctionWithNullArguments()
    {
        $types = array('Eloquent\Phony\Test\TestClassB', 'Countable');
        $arguments = null;
        $definition = array('propertyA' => 'valueA', 'propertyB' => 'valueB');
        $className = 'PhonyMockSimpletestFacadeTestCreateMockFunctionWithNullArguments';
        $actual = mock($types, $arguments, $definition, $className);

        $this->assertInstanceOf('Eloquent\Phony\Mock\Proxy\Stubbing\StubbingProxy', $actual);
        $this->assertInstanceOf($className, $actual->mock());
        $this->assertInstanceOf('Eloquent\Phony\Mock\MockInterface', $actual->mock());
        $this->assertInstanceOf('Eloquent\Phony\Test\TestClassB', $actual->mock());
        $this->assertInstanceOf('Countable', $actual->mock());
        $this->assertNull($actual->mock()->constructorArguments);
        $this->assertSame('ab', $actual->mock()->testClassAMethodA('a', 'b'));
    }

    public function testMockFunctionWithNoArguments()
    {
        $types = array('Eloquent\Phony\Test\TestClassB', 'Countable');
        $actual = mock($types);

        $this->assertInstanceOf('Eloquent\Phony\Mock\Proxy\Stubbing\StubbingProxy', $actual);
        $this->assertInstanceOf('Eloquent\Phony\Mock\MockInterface', $actual->mock());
        $this->assertInstanceOf('Eloquent\Phony\Test\TestClassB', $actual->mock());
        $this->assertInstanceOf('Countable', $actual->mock());
        $this->assertEquals(array(), $actual->mock()->constructorArguments);
        $this->assertSame('ab', $actual->mock()->testClassAMethodA('a', 'b'));
    }

    public function testMockFunctionDefaults()
    {
        $actual = mock();

        $this->assertInstanceOf('Eloquent\Phony\Mock\Proxy\Stubbing\StubbingProxy', $actual);
        $this->assertInstanceOf('Eloquent\Phony\Mock\MockInterface', $actual->mock());
    }

    public function testFullMock()
    {
        $types = array('Eloquent\Phony\Test\TestClassB', 'Countable');
        $definition = array('propertyA' => 'valueA', 'propertyB' => 'valueB');
        $className = 'PhonyMockSimpletestFacadeTestCreateFullMock';
        $actual = Phony::fullMock($types, $definition, $className);

        $this->assertInstanceOf('Eloquent\Phony\Mock\Proxy\Stubbing\StubbingProxy', $actual);
        $this->assertInstanceOf($className, $actual->mock());
        $this->assertInstanceOf('Eloquent\Phony\Mock\MockInterface', $actual->mock());
        $this->assertInstanceOf('Eloquent\Phony\Test\TestClassB', $actual->mock());
        $this->assertInstanceOf('Countable', $actual->mock());
        $this->assertNull($actual->mock()->constructorArguments);
        $this->assertNull($actual->mock()->testClassAMethodA('a', 'b'));
    }

    public function testFullMockFunction()
    {
        $types = array('Eloquent\Phony\Test\TestClassB', 'Countable');
        $definition = array('propertyA' => 'valueA', 'propertyB' => 'valueB');
        $className = 'PhonyMockSimpletestFacadeTestCreateFullMockFunction';
        $actual = fullMock($types, $definition, $className);

        $this->assertInstanceOf('Eloquent\Phony\Mock\Proxy\Stubbing\StubbingProxy', $actual);
        $this->assertInstanceOf($className, $actual->mock());
        $this->assertInstanceOf('Eloquent\Phony\Mock\MockInterface', $actual->mock());
        $this->assertInstanceOf('Eloquent\Phony\Test\TestClassB', $actual->mock());
        $this->assertInstanceOf('Countable', $actual->mock());
        $this->assertNull($actual->mock()->constructorArguments);
        $this->assertNull($actual->mock()->testClassAMethodA('a', 'b'));
    }

    public function testOnStatic()
    {
        $class = Phony::mockBuilder()->build();
        $actual = Phony::onStatic($class);

        $this->assertInstanceOf('Eloquent\Phony\Mock\Proxy\Stubbing\StaticStubbingProxy', $actual);
        $this->assertSame($class, $actual->clazz());
    }

    public function testOnStaticFunction()
    {
        $class = mockBuilder()->build();
        $actual = onStatic($class);

        $this->assertInstanceOf('Eloquent\Phony\Mock\Proxy\Stubbing\StaticStubbingProxy', $actual);
        $this->assertSame($class, $actual->clazz());
    }

    public function testOn()
    {
        $mock = Phony::mockBuilder()->create();
        $actual = Phony::on($mock);

        $this->assertInstanceOf('Eloquent\Phony\Mock\Proxy\Stubbing\StubbingProxy', $actual);
        $this->assertSame($mock, $actual->mock());
    }

    public function testOnFunction()
    {
        $mock = mockBuilder()->create();
        $actual = on($mock);
        $expected = $this->proxyFactory->createStubbing($mock);

        $this->assertInstanceOf('Eloquent\Phony\Mock\Proxy\Stubbing\StubbingProxy', $actual);
        $this->assertSame($mock, $actual->mock());
    }

    public function testVerifyStatic()
    {
        $class = Phony::mockBuilder()->build();
        $actual = Phony::verifyStatic($class);
        $expected = $this->proxyFactory->createVerificationStatic($class);

        $this->assertInstanceOf('Eloquent\Phony\Mock\Proxy\Verification\StaticVerificationProxy', $actual);
        $this->assertSame($class, $actual->clazz());
    }

    public function testVerifyStaticFunction()
    {
        $class = mockBuilder()->build();
        $actual = verifyStatic($class);
        $expected = $this->proxyFactory->createVerificationStatic($class);

        $this->assertInstanceOf('Eloquent\Phony\Mock\Proxy\Verification\StaticVerificationProxy', $actual);
        $this->assertSame($class, $actual->clazz());
    }

    public function testVerify()
    {
        $mock = Phony::mockBuilder()->create();
        $actual = Phony::verify($mock);
        $expected = $this->proxyFactory->createVerification($mock);

        $this->assertInstanceOf('Eloquent\Phony\Mock\Proxy\Verification\VerificationProxy', $actual);
        $this->assertSame($mock, $actual->mock());
    }

    public function testVerifyFunction()
    {
        $mock = mockBuilder()->create();
        $actual = verify($mock);
        $expected = $this->proxyFactory->createVerification($mock);

        $this->assertInstanceOf('Eloquent\Phony\Mock\Proxy\Verification\VerificationProxy', $actual);
        $this->assertSame($mock, $actual->mock());
    }

    public function testSpy()
    {
        $callback = function () {};
        $actual = Phony::spy($callback);

        $this->assertInstanceOf('Eloquent\Phony\Spy\SpyVerifier', $actual);
        $this->assertSame($callback, $actual->callback());
        $this->assertInstanceOf(
            'Eloquent\Phony\Integration\Simpletest\SimpletestAssertionRecorder',
            $actual->callVerifierFactory()->assertionRecorder()
        );
    }

    public function testSpyFunction()
    {
        $callback = function () {};
        $actual = spy($callback);

        $this->assertInstanceOf('Eloquent\Phony\Spy\SpyVerifier', $actual);
        $this->assertSame($callback, $actual->callback());
        $this->assertInstanceOf(
            'Eloquent\Phony\Integration\Simpletest\SimpletestAssertionRecorder',
            $actual->callVerifierFactory()->assertionRecorder()
        );
    }

    public function testStub()
    {
        $callback = function () { return 'a'; };
        $actual = Phony::stub($callback);

        $this->assertInstanceOf('Eloquent\Phony\Stub\StubVerifier', $actual);
        $this->assertSame('a', call_user_func($actual->stub()->callback()));
        $this->assertSame($actual->stub(), $actual->spy()->callback());
        $this->assertInstanceOf(
            'Eloquent\Phony\Integration\Simpletest\SimpletestAssertionRecorder',
            $actual->callVerifierFactory()->assertionRecorder()
        );
    }

    public function testStubFunction()
    {
        $callback = function () { return 'a'; };
        $actual = stub($callback);

        $this->assertInstanceOf('Eloquent\Phony\Stub\StubVerifier', $actual);
        $this->assertSame('a', call_user_func($actual->stub()->callback()));
        $this->assertSame($actual->stub(), $actual->spy()->callback());
        $this->assertInstanceOf(
            'Eloquent\Phony\Integration\Simpletest\SimpletestAssertionRecorder',
            $actual->callVerifierFactory()->assertionRecorder()
        );
    }

    public function testEventOrderMethods()
    {
        $this->assertTrue((boolean) Phony::checkInOrder($this->eventA, $this->eventB));
        $this->assertFalse((boolean) Phony::checkInOrder($this->eventB, $this->eventA));
        $this->assertEquals(
            new CallEventCollection(array($this->eventA, $this->eventB)),
            Phony::inOrder($this->eventA, $this->eventB)
        );
        $this->assertTrue((boolean) Phony::checkInOrderSequence(array($this->eventA, $this->eventB)));
        $this->assertFalse((boolean) Phony::checkInOrderSequence(array($this->eventB, $this->eventA)));
        $this->assertEquals(
            new CallEventCollection(array($this->eventA, $this->eventB)),
            Phony::inOrderSequence(array($this->eventA, $this->eventB))
        );
    }

    public function testEventOrderFunctions()
    {
        $this->assertTrue((boolean) checkInOrder($this->eventA, $this->eventB));
        $this->assertFalse((boolean) checkInOrder($this->eventB, $this->eventA));
        $this->assertEquals(
            new CallEventCollection(array($this->eventA, $this->eventB)),
            inOrder($this->eventA, $this->eventB)
        );
        $this->assertTrue((boolean) checkInOrderSequence(array($this->eventA, $this->eventB)));
        $this->assertFalse((boolean) checkInOrderSequence(array($this->eventB, $this->eventA)));
        $this->assertEquals(
            new CallEventCollection(array($this->eventA, $this->eventB)),
            inOrderSequence(array($this->eventA, $this->eventB))
        );
    }

    public function testAny()
    {
        $expected = new AnyMatcher();
        $actual = Phony::any();

        $this->assertEquals($expected, $actual);
    }

    public function testAnyFunction()
    {
        $expected = new AnyMatcher();
        $actual = any();

        $this->assertEquals($expected, $actual);
    }

    public function testEqualTo()
    {
        $expected = new EqualToMatcher('a');
        $actual = Phony::equalTo('a');

        $this->assertEquals($expected, $actual);
    }

    public function testEqualToFunction()
    {
        $expected = new EqualToMatcher('a');
        $actual = equalTo('a');

        $this->assertEquals($expected, $actual);
    }

    public function testWildcard()
    {
        $expected = new WildcardMatcher(new EqualToMatcher('a'), 1, 2);
        $actual = Phony::wildcard('a', 1, 2);

        $this->assertEquals($expected, $actual);
    }

    public function testWildcardFunction()
    {
        $expected = new WildcardMatcher(new EqualToMatcher('a'), 1, 2);
        $actual = wildcard('a', 1, 2);

        $this->assertEquals($expected, $actual);
    }
}