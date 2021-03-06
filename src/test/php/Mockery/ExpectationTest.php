<?php
/**
 * ehough_mockery_Mockery
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://github.com/padraic/mockery/master/LICENSE
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to padraic@php.net so we can send you a copy immediately.
 *
 *
 *
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2010 Pádraic Brady (http://blog.astrumfutura.com)
 * @license    http://github.com/padraic/mockery/blob/master/LICENSE New BSD License
 */

class ExpectationTest extends PHPUnit_Framework_TestCase
{

    public function setup ()
    {
        $this->container = new ehough_mockery_mockery_Container;
        $this->mock = $this->container->mock('foo');
    }
    
    public function teardown()
    {
        ehough_mockery_Mockery::getConfiguration()->allowMockingNonExistentMethods(true);
        $this->container->mockery_close();
    }

    public function testReturnsNullWhenNoArgs()
    {
        $this->mock->shouldReceive('foo');
        $this->assertNull($this->mock->foo());
    }
    
    public function testReturnsNullWhenSingleArg()
    {
        $this->mock->shouldReceive('foo');
        $this->assertNull($this->mock->foo(1));
    }
    
    public function testReturnsNullWhenManyArgs()
    {
        $this->mock->shouldReceive('foo');
        $this->assertNull($this->mock->foo('foo', array(), new stdClass));
    }

    public function testReturnsNullIfNullIsReturnValue()
    {
        $this->mock->shouldReceive('foo')->andReturn(null);
        $this->assertNull($this->mock->foo());
    }

    public function testReturnsNullForMockedExistingClassIfAndreturnnullCalled()
    {
        $mock = $this->container->mock('MockeryTest_Foo');
        $mock->shouldReceive('foo')->andReturn(null);
        $this->assertNull($mock->foo());
    }

    public function testReturnsNullForMockedExistingClassIfNullIsReturnValue()
    {
        $mock = $this->container->mock('MockeryTest_Foo');
        $mock->shouldReceive('foo')->andReturnNull();
        $this->assertNull($mock->foo());
    }
    
    public function testReturnsSameValueForAllIfNoArgsExpectationAndNoneGiven()
    {
        $this->mock->shouldReceive('foo')->andReturn(1);
        $this->assertEquals(1, $this->mock->foo());
    }
    
    public function testSetsPublicPropertyWhenRequested()
    {
        $this->mock->shouldReceive('foo')->andSet('bar', 'baz');
        $this->mock->foo();
        $this->assertEquals('baz', $this->mock->bar);
    }
    
    public function testSetsPublicPropertyWhenRequestedUsingAlias()
    {
        $this->mock->shouldReceive('foo')->set('bar', 'baz');
        $this->mock->foo();
        $this->assertEquals('baz', $this->mock->bar);
    }
    
    public function testReturnsSameValueForAllIfNoArgsExpectationAndSomeGiven()
    {
        $this->mock->shouldReceive('foo')->andReturn(1);
        $this->assertEquals(1, $this->mock->foo('foo'));
    }
    
    public function testReturnsValueFromSequenceSequentially()
    {
        $this->mock->shouldReceive('foo')->andReturn(1, 2, 3);
        $this->mock->foo('foo');
        $this->assertEquals(2, $this->mock->foo('foo'));
    }
    
    public function testReturnsValueFromSequenceSequentiallyAndRepeatedlyReturnsFinalValueOnExtraCalls()
    {
        $this->mock->shouldReceive('foo')->andReturn(1, 2, 3);
        $this->mock->foo('foo');
        $this->mock->foo('foo');
        $this->assertEquals(3, $this->mock->foo('foo'));
        $this->assertEquals(3, $this->mock->foo('foo'));
    }
    
    public function testReturnsValueFromSequenceSequentiallyAndRepeatedlyReturnsFinalValueOnExtraCallsWithManyAndReturnCalls()
    {
        $this->mock->shouldReceive('foo')->andReturn(1)->andReturn(2, 3);
        $this->mock->foo('foo');
        $this->mock->foo('foo');
        $this->assertEquals(3, $this->mock->foo('foo'));
        $this->assertEquals(3, $this->mock->foo('foo'));
    }

    public function testReturnsValueOfClosure()
    {
        $this->mock->shouldReceive('foo')->with(5)->andReturnUsing(function($v){return $v+1;});
        $this->assertEquals(6, $this->mock->foo(5));
    }
    
    public function testReturnsUndefined()
    {
        $this->mock->shouldReceive('foo')->andReturnUndefined();
        $this->assertTrue($this->mock->foo() instanceof ehough_mockery_mockery_Undefined);
    }

    public function testReturnsValuesSetAsArray()
    {
        $this->mock->shouldReceive('foo')->andReturnValues(array(1,2,3));
        $this->assertEquals(1, $this->mock->foo());
        $this->assertEquals(2, $this->mock->foo());
        $this->assertEquals(3, $this->mock->foo());
    }

    /**
     * @expectedException OutOfBoundsException
     */
    public function testThrowsException()
    {
        $this->mock->shouldReceive('foo')->andThrow(new OutOfBoundsException);
        $this->mock->foo();
    }
    
    /**
     * @expectedException OutOfBoundsException
     */
    public function testThrowsExceptionBasedOnArgs()
    {
        $this->mock->shouldReceive('foo')->andThrow('OutOfBoundsException');
        $this->mock->foo();
    }
    
    public function testThrowsExceptionBasedOnArgsWithMessage()
    {
        $this->mock->shouldReceive('foo')->andThrow('OutOfBoundsException', 'foo');
        try {
            $this->mock->foo();
        } catch (OutOfBoundsException $e) {
            $this->assertEquals('foo', $e->getMessage());
        }
    }
    
    /**
     * @expectedException OutOfBoundsException
     */
    public function testThrowsExceptionSequentially()
    {
        $this->mock->shouldReceive('foo')->andThrow(new Exception)->andThrow(new OutOfBoundsException);
        try {
            $this->mock->foo();
        } catch (Exception $e) {}
        $this->mock->foo();
    }
    
    public function testMultipleExpectationsWithReturns()
    {
        $this->mock->shouldReceive('foo')->with(1)->andReturn(10);
        $this->mock->shouldReceive('bar')->with(2)->andReturn(20);
        $this->assertEquals(10, $this->mock->foo(1));
        $this->assertEquals(20, $this->mock->bar(2));
    }
    
    public function testExpectsNoArguments()
    {
        $this->mock->shouldReceive('foo')->withNoArgs();
        $this->mock->foo();
    }
    
    /**
     * @expectedException ehough_mockery_mockery_Exception
     */
    public function testExpectsNoArgumentsThrowsExceptionIfAnyPassed()
    {
        $this->mock->shouldReceive('foo')->withNoArgs();
        $this->mock->foo(1);
    }
    
    public function testExpectsAnyArguments()
    {
        $this->mock->shouldReceive('foo')->withAnyArgs();
        $this->mock->foo();
        $this->mock->foo(1);
        $this->mock->foo(1, 'k', new stdClass);
    }
    
    public function testExpectsArgumentMatchingRegularExpression()
    {
        $this->mock->shouldReceive('foo')->with('/bar/i');
        $this->mock->foo('xxBARxx');
    }
    
    public function testExpectsArgumentMatchingObjectType()
    {
        $this->mock->shouldReceive('foo')->with('\stdClass');
        $this->mock->foo(new stdClass);
    }
    
    /**
     * @expectedException ehough_mockery_mockery_Exception
     */
    public function testThrowsExceptionOnNoArgumentMatch()
    {
        $this->mock->shouldReceive('foo')->with(1);
        $this->mock->foo(2);
    }
    
    public function testNeverCalled()
    {
        $this->mock->shouldReceive('foo')->never();
    }
    
    /**
     * @expectedException ehough_mockery_mockery_countvalidator_Exception
     */
    public function testNeverCalledThrowsExceptionOnCall()
    {
        $this->mock->shouldReceive('foo')->never();
        $this->mock->foo();
        $this->container->mockery_verify();
    }
    
    public function testCalledOnce()
    {
        $this->mock->shouldReceive('foo')->once();
        $this->mock->foo();
        $this->container->mockery_verify();
    }
    
    /**
     * @expectedException ehough_mockery_mockery_countvalidator_Exception
     */
    public function testCalledOnceThrowsExceptionIfNotCalled()
    {
        $this->mock->shouldReceive('foo')->once();
        $this->container->mockery_verify();
    }
    
    /**
     * @expectedException ehough_mockery_mockery_countvalidator_Exception
     */
    public function testCalledOnceThrowsExceptionIfCalledTwice()
    {
        $this->mock->shouldReceive('foo')->once();
        $this->mock->foo();
        $this->mock->foo();
        $this->container->mockery_verify();
    }
    
    public function testCalledTwice()
    {
        $this->mock->shouldReceive('foo')->twice();
        $this->mock->foo();
        $this->mock->foo();
        $this->container->mockery_verify();
    }
    
    /**
     * @expectedException ehough_mockery_mockery_countvalidator_Exception
     */
    public function testCalledTwiceThrowsExceptionIfNotCalled()
    {
        $this->mock->shouldReceive('foo')->twice();
        $this->container->mockery_verify();
    }
    
    /**
     * @expectedException ehough_mockery_mockery_countvalidator_Exception
     */
    public function testCalledOnceThrowsExceptionIfCalledThreeTimes()
    {
        $this->mock->shouldReceive('foo')->twice();
        $this->mock->foo();
        $this->mock->foo();
        $this->mock->foo();
        $this->container->mockery_verify();
    }
    
    public function testCalledZeroOrMoreTimesAtZeroCalls()
    {
        $this->mock->shouldReceive('foo')->zeroOrMoreTimes();
        $this->container->mockery_verify();
    }
    
    public function testCalledZeroOrMoreTimesAtThreeCalls()
    {
        $this->mock->shouldReceive('foo')->zeroOrMoreTimes();
        $this->mock->foo();
        $this->mock->foo();
        $this->mock->foo();
        $this->container->mockery_verify();
    }
    
    public function testTimesCountCalls()
    {
        $this->mock->shouldReceive('foo')->times(4);
        $this->mock->foo();
        $this->mock->foo();
        $this->mock->foo();
        $this->mock->foo();
        $this->container->mockery_verify();
    }
    
    /**
     * @expectedException ehough_mockery_mockery_countvalidator_Exception
     */
    public function testTimesCountCallThrowsExceptionOnTooFewCalls()
    {
        $this->mock->shouldReceive('foo')->times(2);
        $this->mock->foo();
        $this->container->mockery_verify();
    }
    
    /**
     * @expectedException ehough_mockery_mockery_countvalidator_Exception
     */
    public function testTimesCountCallThrowsExceptionOnTooManyCalls()
    {
        $this->mock->shouldReceive('foo')->times(2);
        $this->mock->foo();
        $this->mock->foo();
        $this->mock->foo();
        $this->container->mockery_verify();
    }
    
    public function testCalledAtLeastOnceAtExactlyOneCall()
    {
        $this->mock->shouldReceive('foo')->atLeast()->once();
        $this->mock->foo();
        $this->container->mockery_verify();
    }
    
    public function testCalledAtLeastOnceAtExactlyThreeCalls()
    {
        $this->mock->shouldReceive('foo')->atLeast()->times(3);
        $this->mock->foo();
        $this->mock->foo();
        $this->mock->foo();
        $this->container->mockery_verify();
    }
    
    /**
     * @expectedException ehough_mockery_mockery_countvalidator_Exception
     */
    public function testCalledAtLeastThrowsExceptionOnTooFewCalls()
    {
        $this->mock->shouldReceive('foo')->atLeast()->twice();
        $this->mock->foo();
        $this->container->mockery_verify();
    }
    
    public function testCalledAtMostOnceAtExactlyOneCall()
    {
        $this->mock->shouldReceive('foo')->atMost()->once();
        $this->mock->foo();
        $this->container->mockery_verify();
    }
    
    public function testCalledAtMostAtExactlyThreeCalls()
    {
        $this->mock->shouldReceive('foo')->atMost()->times(3);
        $this->mock->foo();
        $this->mock->foo();
        $this->mock->foo();
        $this->container->mockery_verify();
    }
    
    /**
     * @expectedException ehough_mockery_mockery_countvalidator_Exception
     */
    public function testCalledAtLeastThrowsExceptionOnTooManyCalls()
    {
        $this->mock->shouldReceive('foo')->atMost()->twice();
        $this->mock->foo();
        $this->mock->foo();
        $this->mock->foo();
        $this->container->mockery_verify();
    }
    
    /**
     * @expectedException ehough_mockery_mockery_countvalidator_Exception
     */
    public function testExactCountersOverrideAnyPriorSetNonExactCounters()
    {
        $this->mock->shouldReceive('foo')->atLeast()->once()->once();
        $this->mock->foo();
        $this->mock->foo();
        $this->container->mockery_verify();
    }
    
    public function testComboOfLeastAndMostCallsWithOneCall()
    {
        $this->mock->shouldReceive('foo')->atleast()->once()->atMost()->twice();
        $this->mock->foo();
        $this->container->mockery_verify(); 
    }
    
    public function testComboOfLeastAndMostCallsWithTwoCalls()
    {
        $this->mock->shouldReceive('foo')->atleast()->once()->atMost()->twice();
        $this->mock->foo();
        $this->mock->foo();
        $this->container->mockery_verify(); 
    }
    
    /**
     * @expectedException ehough_mockery_mockery_countvalidator_Exception
     */
    public function testComboOfLeastAndMostCallsThrowsExceptionAtTooFewCalls()
    {
        $this->mock->shouldReceive('foo')->atleast()->once()->atMost()->twice();
        $this->container->mockery_verify(); 
    }
    
    /**
     * @expectedException ehough_mockery_mockery_countvalidator_Exception
     */
    public function testComboOfLeastAndMostCallsThrowsExceptionAtTooManyCalls()
    {
        $this->mock->shouldReceive('foo')->atleast()->once()->atMost()->twice();
        $this->mock->foo();
        $this->mock->foo();
        $this->mock->foo();
        $this->container->mockery_verify(); 
    }
    
    public function testCallCountingOnlyAppliesToMatchedExpectations()
    {
        $this->mock->shouldReceive('foo')->with(1)->once();
        $this->mock->shouldReceive('foo')->with(2)->twice();
        $this->mock->shouldReceive('foo')->with(3);
        $this->mock->foo(1);
        $this->mock->foo(2);
        $this->mock->foo(2);
        $this->mock->foo(3);
        $this->container->mockery_verify();
    }
    
    /**
     * @expectedException ehough_mockery_mockery_countvalidator_Exception
     */
    public function testCallCountingThrowsExceptionOnAnyMismatch()
    {
        $this->mock->shouldReceive('foo')->with(1)->once();
        $this->mock->shouldReceive('foo')->with(2)->twice();
        $this->mock->shouldReceive('foo')->with(3);
        $this->mock->shouldReceive('bar');
        $this->mock->foo(1);
        $this->mock->foo(2);
        $this->mock->foo(3);
        $this->mock->bar();
        $this->container->mockery_verify();
    }
    
    public function testOrderedCallsWithoutError()
    {
        $this->mock->shouldReceive('foo')->ordered();
        $this->mock->shouldReceive('bar')->ordered();
        $this->mock->foo();
        $this->mock->bar();
        $this->container->mockery_verify();
    }
    
    /**
     * @expectedException ehough_mockery_mockery_Exception
     */
    public function testOrderedCallsWithOutOfOrderError()
    {
        $this->mock->shouldReceive('foo')->ordered();
        $this->mock->shouldReceive('bar')->ordered();
        $this->mock->bar();
        $this->mock->foo();
        $this->container->mockery_verify();
    }
    
    public function testDifferentArgumentsAndOrderingsPassWithoutException()
    {
        $this->mock->shouldReceive('foo')->with(1)->ordered();
        $this->mock->shouldReceive('foo')->with(2)->ordered();
        $this->mock->foo(1);
        $this->mock->foo(2);
        $this->container->mockery_verify();
    }
    
    /**
     * @expectedException ehough_mockery_mockery_Exception
     */
    public function testDifferentArgumentsAndOrderingsThrowExceptionWhenInWrongOrder()
    {
        $this->mock->shouldReceive('foo')->with(1)->ordered();
        $this->mock->shouldReceive('foo')->with(2)->ordered();
        $this->mock->foo(2);
        $this->mock->foo(1);
        $this->container->mockery_verify();
    }
    
    public function testUnorderedCallsIgnoredForOrdering()
    {
        $this->mock->shouldReceive('foo')->with(1)->ordered();
        $this->mock->shouldReceive('foo')->with(2);
        $this->mock->shouldReceive('foo')->with(3)->ordered();
        $this->mock->foo(2);
        $this->mock->foo(1);
        $this->mock->foo(2);
        $this->mock->foo(3);
        $this->mock->foo(2);
        $this->container->mockery_verify();
    }
    
    public function testOrderingOfDefaultGrouping()
    {
        $this->mock->shouldReceive('foo')->ordered();
        $this->mock->shouldReceive('bar')->ordered();
        $this->mock->foo();
        $this->mock->bar();
        $this->container->mockery_verify();
    }
    
    /**
     * @expectedException ehough_mockery_mockery_Exception
     */
    public function testOrderingOfDefaultGroupingThrowsExceptionOnWrongOrder()
    {
        $this->mock->shouldReceive('foo')->ordered();
        $this->mock->shouldReceive('bar')->ordered();
        $this->mock->bar();
        $this->mock->foo();
        $this->container->mockery_verify();
    }
    
    public function testOrderingUsingNumberedGroups()
    {
        $this->mock->shouldReceive('start')->ordered(1);
        $this->mock->shouldReceive('foo')->ordered(2);
        $this->mock->shouldReceive('bar')->ordered(2);
        $this->mock->shouldReceive('final')->ordered();
        $this->mock->start();
        $this->mock->bar();
        $this->mock->foo();
        $this->mock->bar();
        $this->mock->final();
        $this->container->mockery_verify();
    }
    
    public function testOrderingUsingNamedGroups()
    {
        $this->mock->shouldReceive('start')->ordered('start');
        $this->mock->shouldReceive('foo')->ordered('foobar');
        $this->mock->shouldReceive('bar')->ordered('foobar');
        $this->mock->shouldReceive('final')->ordered();
        $this->mock->start();
        $this->mock->bar();
        $this->mock->foo();
        $this->mock->bar();
        $this->mock->final();
        $this->container->mockery_verify();
    }
    
    /**
     * @group 2A
     */
    public function testGroupedUngroupedOrderingDoNotOverlap()
    {
        $s = $this->mock->shouldReceive('start')->ordered();
        $m = $this->mock->shouldReceive('mid')->ordered('foobar');
        $e = $this->mock->shouldReceive('end')->ordered();
        $this->assertTrue($s->getOrderNumber() < $m->getOrderNumber());
        $this->assertTrue($m->getOrderNumber() < $e->getOrderNumber());
    }
    
    /**
     * @expectedException ehough_mockery_mockery_Exception
     */
    public function testGroupedOrderingThrowsExceptionWhenCallsDisordered()
    {
        $this->mock->shouldReceive('foo')->ordered('first');
        $this->mock->shouldReceive('bar')->ordered('second');
        $this->mock->bar();
        $this->mock->foo();
        $this->container->mockery_verify();
    }
    
    public function testExpectationMatchingWithNoArgsOrderings()
    {
        $this->mock->shouldReceive('foo')->withNoArgs()->once()->ordered();
        $this->mock->shouldReceive('bar')->withNoArgs()->once()->ordered();
        $this->mock->shouldReceive('foo')->withNoArgs()->once()->ordered();
        $this->mock->foo();
        $this->mock->bar();
        $this->mock->foo();
        $this->container->mockery_verify();
    }
    
    public function testExpectationMatchingWithAnyArgsOrderings()
    {
        $this->mock->shouldReceive('foo')->withAnyArgs()->once()->ordered();
        $this->mock->shouldReceive('bar')->withAnyArgs()->once()->ordered();
        $this->mock->shouldReceive('foo')->withAnyArgs()->once()->ordered();
        $this->mock->foo();
        $this->mock->bar();
        $this->mock->foo();
        $this->container->mockery_verify();
    }
    
    public function testEnsuresOrderingIsNotCrossMockByDefault()
    {
        $this->mock->shouldReceive('foo')->ordered();
        $mock2 = $this->container->mock('bar');
        $mock2->shouldReceive('bar')->ordered();
        $mock2->bar();
        $this->mock->foo();
    }
    
    /**
     * @expectedException ehough_mockery_mockery_Exception
     */
    public function testEnsuresOrderingIsCrossMockWhenGloballyFlagSet()
    {
        $this->mock->shouldReceive('foo')->globally()->ordered();
        $mock2 = $this->container->mock('bar');
        $mock2->shouldReceive('bar')->globally()->ordered();
        $mock2->bar();
        $this->mock->foo();
    }
    
    public function testExpectationCastToStringFormatting()
    {
        $exp = $this->mock->shouldReceive('foo')->with(1, 'bar', new stdClass, array());
        $this->assertEquals('[foo(1, "bar", stdClass, Array)]', (string) $exp);
    }
    
    public function testMultipleExpectationCastToStringFormatting()
    {
        $exp = $this->mock->shouldReceive('foo', 'bar')->with(1);
        $this->assertEquals('[foo(1), bar(1)]', (string) $exp);
    }
    
    public function testGroupedOrderingWithLimitsAllowsMultipleReturnValues()
    {
        $this->mock->shouldReceive('foo')->with(2)->once()->andReturn('first');
        $this->mock->shouldReceive('foo')->with(2)->twice()->andReturn('second/third');
        $this->mock->shouldReceive('foo')->with(2)->andReturn('infinity');
        $this->assertEquals('first', $this->mock->foo(2));
        $this->assertEquals('second/third', $this->mock->foo(2));
        $this->assertEquals('second/third', $this->mock->foo(2));
        $this->assertEquals('infinity', $this->mock->foo(2));
        $this->assertEquals('infinity', $this->mock->foo(2));
        $this->assertEquals('infinity', $this->mock->foo(2));
        $this->container->mockery_verify();
    }
    
    public function testExpectationsCanBeMarkedAsDefaults()
    {
        $this->mock->shouldReceive('foo')->andReturn('bar')->byDefault();
        $this->assertEquals('bar', $this->mock->foo());
        $this->container->mockery_verify();
    }
    
    public function testDefaultExpectationsValidatedInCorrectOrder()
    {
        $this->mock->shouldReceive('foo')->with(1)->once()->andReturn('first')->byDefault();
        $this->mock->shouldReceive('foo')->with(2)->once()->andReturn('second')->byDefault();
        $this->assertEquals('first', $this->mock->foo(1));
        $this->assertEquals('second', $this->mock->foo(2));
        $this->container->mockery_verify();
    }
    
    public function testDefaultExpectationsAreReplacedByLaterConcreteExpectations()
    {
        $this->mock->shouldReceive('foo')->andReturn('bar')->once()->byDefault();
        $this->mock->shouldReceive('foo')->andReturn('bar')->twice();
        $this->mock->foo();
        $this->mock->foo();
        $this->container->mockery_verify();
    }
    
    public function testDefaultExpectationsCanBeChangedByLaterExpectations()
    {
        $this->mock->shouldReceive('foo')->with(1)->andReturn('bar')->once()->byDefault();
        $this->mock->shouldReceive('foo')->with(2)->andReturn('baz')->once();
        try {
            $this->mock->foo(1);
            $this->fail('Expected exception not thrown');
        } catch (ehough_mockery_mockery_Exception $e) {}
        $this->mock->foo(2);
        $this->container->mockery_verify();
    }
    
    /**
     * @expectedException ehough_mockery_mockery_Exception
     */
    public function testDefaultExpectationsCanBeOrdered()
    {
        $this->mock->shouldReceive('foo')->ordered()->byDefault();
        $this->mock->shouldReceive('bar')->ordered()->byDefault();
        $this->mock->bar();
        $this->mock->foo();
        $this->container->mockery_verify();
    }
    
    public function testDefaultExpectationsCanBeOrderedAndReplaced()
    {
        $this->mock->shouldReceive('foo')->ordered()->byDefault();
        $this->mock->shouldReceive('bar')->ordered()->byDefault();
        $this->mock->shouldReceive('bar')->ordered();
        $this->mock->shouldReceive('foo')->ordered();
        $this->mock->bar();
        $this->mock->foo();
        $this->container->mockery_verify();
    }
    
    public function testByDefaultOperatesFromMockConstruction()
    {
        $container = new ehough_mockery_mockery_Container;
        $mock = $container->mock('f', array('foo'=>'rfoo','bar'=>'rbar','baz'=>'rbaz'))->byDefault();
        $mock->shouldReceive('foo')->andReturn('foobar');
        $this->assertEquals('foobar', $mock->foo());
        $this->assertEquals('rbar', $mock->bar());
        $this->assertEquals('rbaz', $mock->baz());
        $mock->mockery_verify();
    }
    
    public function testByDefaultOnAMockDoesSquatWithoutExpectations()
    {
        $container = new ehough_mockery_mockery_Container;
        $mock = $container->mock('f')->byDefault();
    }

    public function testDefaultExpectationsCanBeOverridden()
    {
        $this->mock->shouldReceive('foo')->with('test')->andReturn('bar')->byDefault();
        $this->mock->shouldReceive('foo')->with('test')->andReturn('newbar')->byDefault();
        $this->mock->foo('test');
        $this->assertEquals('newbar', $this->mock->foo('test'));
    }
    
    /**
     * @expectedException ehough_mockery_mockery_Exception
     */
    public function testByDefaultPreventedFromSettingDefaultWhenDefaultingExpectationWasReplaced()
    {
        $exp = $this->mock->shouldReceive('foo')->andReturn(1);
        $this->mock->shouldReceive('foo')->andReturn(2);
        $exp->byDefault();
    }
    
    /**
     * Argument Constraint Tests
     */
    
    public function testAnyConstraintMatchesAnyArg()
    {
        $this->mock->shouldReceive('foo')->with(1, ehough_mockery_Mockery::any())->twice();
        $this->mock->foo(1, 2);
        $this->mock->foo(1, 'str');
        $this->container->mockery_verify();
    }
    
    public function testAnyConstraintNonMatchingCase()
    {
        $this->mock->shouldReceive('foo')->times(3);
        $this->mock->shouldReceive('foo')->with(1, ehough_mockery_Mockery::any())->never();
        $this->mock->foo();
        $this->mock->foo(1);
        $this->mock->foo(1, 2, 3);
        $this->container->mockery_verify();
    }
    
    public function testArrayConstraintMatchesArgument()
    {
        $this->mock->shouldReceive('foo')->with(ehough_mockery_Mockery::type('array'))->once();
        $this->mock->foo(array());
        $this->container->mockery_verify();
    }
    
    public function testArrayConstraintNonMatchingCase()
    {
        $this->mock->shouldReceive('foo')->times(3);
        $this->mock->shouldReceive('foo')->with(1, ehough_mockery_Mockery::type('array'))->never();
        $this->mock->foo();
        $this->mock->foo(1);
        $this->mock->foo(1, 2, 3);
        $this->container->mockery_verify();
    }
    
    /**
     * @expectedException ehough_mockery_mockery_Exception
     */
    public function testArrayConstraintThrowsExceptionWhenConstraintUnmatched()
    {
        $this->mock->shouldReceive('foo')->with(ehough_mockery_Mockery::type('array'))->once();
        $this->mock->foo(1);
        $this->container->mockery_verify();
    }
    
    public function testBoolConstraintMatchesArgument()
    {
        $this->mock->shouldReceive('foo')->with(ehough_mockery_Mockery::type('bool'))->once();
        $this->mock->foo(true);
        $this->container->mockery_verify();
    }
    
    public function testBoolConstraintNonMatchingCase()
    {
        $this->mock->shouldReceive('foo')->times(3);
        $this->mock->shouldReceive('foo')->with(1, ehough_mockery_Mockery::type('bool'))->never();
        $this->mock->foo();
        $this->mock->foo(1);
        $this->mock->foo(1, 2, 3);
        $this->container->mockery_verify();
    }
    
    /**
     * @expectedException ehough_mockery_mockery_Exception
     */
    public function testBoolConstraintThrowsExceptionWhenConstraintUnmatched()
    {
        $this->mock->shouldReceive('foo')->with(ehough_mockery_Mockery::type('bool'))->once();
        $this->mock->foo(1);
        $this->container->mockery_verify();
    }
    
    public function testCallableConstraintMatchesArgument()
    {
        $this->mock->shouldReceive('foo')->with(ehough_mockery_Mockery::type('callable'))->once();
        $this->mock->foo(function(){return 'f';});
        $this->container->mockery_verify();
    }
    
    public function testCallableConstraintNonMatchingCase()
    {
        $this->mock->shouldReceive('foo')->times(3);
        $this->mock->shouldReceive('foo')->with(1, ehough_mockery_Mockery::type('callable'))->never();
        $this->mock->foo();
        $this->mock->foo(1);
        $this->mock->foo(1, 2, 3);
        $this->container->mockery_verify();
    }
    
    /**
     * @expectedException ehough_mockery_mockery_Exception
     */
    public function testCallableConstraintThrowsExceptionWhenConstraintUnmatched()
    {
        $this->mock->shouldReceive('foo')->with(ehough_mockery_Mockery::type('callable'))->once();
        $this->mock->foo(1);
        $this->container->mockery_verify();
    }
    
    public function testDoubleConstraintMatchesArgument()
    {
        $this->mock->shouldReceive('foo')->with(ehough_mockery_Mockery::type('double'))->once();
        $this->mock->foo(2.25);
        $this->container->mockery_verify();
    }
    
    public function testDoubleConstraintNonMatchingCase()
    {
        $this->mock->shouldReceive('foo')->times(3);
        $this->mock->shouldReceive('foo')->with(1, ehough_mockery_Mockery::type('double'))->never();
        $this->mock->foo();
        $this->mock->foo(1);
        $this->mock->foo(1, 2, 3);
        $this->container->mockery_verify();
    }
    
    /**
     * @expectedException ehough_mockery_mockery_Exception
     */
    public function testDoubleConstraintThrowsExceptionWhenConstraintUnmatched()
    {
        $this->mock->shouldReceive('foo')->with(ehough_mockery_Mockery::type('double'))->once();
        $this->mock->foo(1);
        $this->container->mockery_verify();
    }
    
    public function testFloatConstraintMatchesArgument()
    {
        $this->mock->shouldReceive('foo')->with(ehough_mockery_Mockery::type('float'))->once();
        $this->mock->foo(2.25);
        $this->container->mockery_verify();
    }
    
    public function testFloatConstraintNonMatchingCase()
    {
        $this->mock->shouldReceive('foo')->times(3);
        $this->mock->shouldReceive('foo')->with(1, ehough_mockery_Mockery::type('float'))->never();
        $this->mock->foo();
        $this->mock->foo(1);
        $this->mock->foo(1, 2, 3);
        $this->container->mockery_verify();
    }
    
    /**
     * @expectedException ehough_mockery_mockery_Exception
     */
    public function testFloatConstraintThrowsExceptionWhenConstraintUnmatched()
    {
        $this->mock->shouldReceive('foo')->with(ehough_mockery_Mockery::type('float'))->once();
        $this->mock->foo(1);
        $this->container->mockery_verify();
    }
    
    public function testIntConstraintMatchesArgument()
    {
        $this->mock->shouldReceive('foo')->with(ehough_mockery_Mockery::type('int'))->once();
        $this->mock->foo(2);
        $this->container->mockery_verify();
    }
    
    public function testIntConstraintNonMatchingCase()
    {
        $this->mock->shouldReceive('foo')->times(3);
        $this->mock->shouldReceive('foo')->with(1, ehough_mockery_Mockery::type('int'))->never();
        $this->mock->foo();
        $this->mock->foo(1);
        $this->mock->foo(1, 2, 3);
        $this->container->mockery_verify();
    }
    
    /**
     * @expectedException ehough_mockery_mockery_Exception
     */
    public function testIntConstraintThrowsExceptionWhenConstraintUnmatched()
    {
        $this->mock->shouldReceive('foo')->with(ehough_mockery_Mockery::type('int'))->once();
        $this->mock->foo('f');
        $this->container->mockery_verify();
    }
    
    public function testLongConstraintMatchesArgument()
    {
        $this->mock->shouldReceive('foo')->with(ehough_mockery_Mockery::type('long'))->once();
        $this->mock->foo(2);
        $this->container->mockery_verify();
    }
    
    public function testLongConstraintNonMatchingCase()
    {
        $this->mock->shouldReceive('foo')->times(3);
        $this->mock->shouldReceive('foo')->with(1, ehough_mockery_Mockery::type('long'))->never();
        $this->mock->foo();
        $this->mock->foo(1);
        $this->mock->foo(1, 2, 3);
        $this->container->mockery_verify();
    }
    
    /**
     * @expectedException ehough_mockery_mockery_Exception
     */
    public function testLongConstraintThrowsExceptionWhenConstraintUnmatched()
    {
        $this->mock->shouldReceive('foo')->with(ehough_mockery_Mockery::type('long'))->once();
        $this->mock->foo('f');
        $this->container->mockery_verify();
    }
    
    public function testNullConstraintMatchesArgument()
    {
        $this->mock->shouldReceive('foo')->with(ehough_mockery_Mockery::type('null'))->once();
        $this->mock->foo(null);
        $this->container->mockery_verify();
    }
    
    public function testNullConstraintNonMatchingCase()
    {
        $this->mock->shouldReceive('foo')->times(3);
        $this->mock->shouldReceive('foo')->with(1, ehough_mockery_Mockery::type('null'))->never();
        $this->mock->foo();
        $this->mock->foo(1);
        $this->mock->foo(1, 2, 3);
        $this->container->mockery_verify();
    }
    
    /**
     * @expectedException ehough_mockery_mockery_Exception
     */
    public function testNullConstraintThrowsExceptionWhenConstraintUnmatched()
    {
        $this->mock->shouldReceive('foo')->with(ehough_mockery_Mockery::type('null'))->once();
        $this->mock->foo('f');
        $this->container->mockery_verify();
    }
    
    public function testNumericConstraintMatchesArgument()
    {
        $this->mock->shouldReceive('foo')->with(ehough_mockery_Mockery::type('numeric'))->once();
        $this->mock->foo('2');
        $this->container->mockery_verify();
    }
    
    public function testNumericConstraintNonMatchingCase()
    {
        $this->mock->shouldReceive('foo')->times(3);
        $this->mock->shouldReceive('foo')->with(1, ehough_mockery_Mockery::type('numeric'))->never();
        $this->mock->foo();
        $this->mock->foo(1);
        $this->mock->foo(1, 2, 3);
        $this->container->mockery_verify();
    }
    
    /**
     * @expectedException ehough_mockery_mockery_Exception
     */
    public function testNumericConstraintThrowsExceptionWhenConstraintUnmatched()
    {
        $this->mock->shouldReceive('foo')->with(ehough_mockery_Mockery::type('numeric'))->once();
        $this->mock->foo('f');
        $this->container->mockery_verify();
    }
    
    public function testObjectConstraintMatchesArgument()
    {
        $this->mock->shouldReceive('foo')->with(ehough_mockery_Mockery::type('object'))->once();
        $this->mock->foo(new stdClass);
        $this->container->mockery_verify();
    }
    
    public function testObjectConstraintNonMatchingCase()
    {
        $this->mock->shouldReceive('foo')->times(3);
        $this->mock->shouldReceive('foo')->with(1, ehough_mockery_Mockery::type('object`'))->never();
        $this->mock->foo();
        $this->mock->foo(1);
        $this->mock->foo(1, 2, 3);
        $this->container->mockery_verify();
    }
    
    /**
     * @expectedException ehough_mockery_mockery_Exception
     */
    public function testObjectConstraintThrowsExceptionWhenConstraintUnmatched()
    {
        $this->mock->shouldReceive('foo')->with(ehough_mockery_Mockery::type('object'))->once();
        $this->mock->foo('f');
        $this->container->mockery_verify();
    }
    
    public function testRealConstraintMatchesArgument()
    {
        $this->mock->shouldReceive('foo')->with(ehough_mockery_Mockery::type('real'))->once();
        $this->mock->foo(2.25);
        $this->container->mockery_verify();
    }
    
    public function testRealConstraintNonMatchingCase()
    {
        $this->mock->shouldReceive('foo')->times(3);
        $this->mock->shouldReceive('foo')->with(1, ehough_mockery_Mockery::type('real'))->never();
        $this->mock->foo();
        $this->mock->foo(1);
        $this->mock->foo(1, 2, 3);
        $this->container->mockery_verify();
    }
    
    /**
     * @expectedException ehough_mockery_mockery_Exception
     */
    public function testRealConstraintThrowsExceptionWhenConstraintUnmatched()
    {
        $this->mock->shouldReceive('foo')->with(ehough_mockery_Mockery::type('real'))->once();
        $this->mock->foo('f');
        $this->container->mockery_verify();
    }
    
    public function testResourceConstraintMatchesArgument()
    {
        $this->mock->shouldReceive('foo')->with(ehough_mockery_Mockery::type('resource'))->once();
        $r = fopen(dirname(__FILE__) . '/_files/file.txt', 'r');
        $this->mock->foo($r);
        $this->container->mockery_verify();
    }
    
    public function testResourceConstraintNonMatchingCase()
    {
        $this->mock->shouldReceive('foo')->times(3);
        $this->mock->shouldReceive('foo')->with(1, ehough_mockery_Mockery::type('resource'))->never();
        $this->mock->foo();
        $this->mock->foo(1);
        $this->mock->foo(1, 2, 3);
        $this->container->mockery_verify();
    }
    
    /**
     * @expectedException ehough_mockery_mockery_Exception
     */
    public function testResourceConstraintThrowsExceptionWhenConstraintUnmatched()
    {
        $this->mock->shouldReceive('foo')->with(ehough_mockery_Mockery::type('resource'))->once();
        $this->mock->foo('f');
        $this->container->mockery_verify();
    }
    
    public function testScalarConstraintMatchesArgument()
    {
        $this->mock->shouldReceive('foo')->with(ehough_mockery_Mockery::type('scalar'))->once();
        $this->mock->foo(2);
        $this->container->mockery_verify();
    }
    
    public function testScalarConstraintNonMatchingCase()
    {
        $this->mock->shouldReceive('foo')->times(3);
        $this->mock->shouldReceive('foo')->with(1, ehough_mockery_Mockery::type('scalar'))->never();
        $this->mock->foo();
        $this->mock->foo(1);
        $this->mock->foo(1, 2, 3);
        $this->container->mockery_verify();
    }
    
    /**
     * @expectedException ehough_mockery_mockery_Exception
     */
    public function testScalarConstraintThrowsExceptionWhenConstraintUnmatched()
    {
        $this->mock->shouldReceive('foo')->with(ehough_mockery_Mockery::type('scalar'))->once();
        $this->mock->foo(array());
        $this->container->mockery_verify();
    }
    
    public function testStringConstraintMatchesArgument()
    {
        $this->mock->shouldReceive('foo')->with(ehough_mockery_Mockery::type('string'))->once();
        $this->mock->foo('2');
        $this->container->mockery_verify();
    }
    
    public function testStringConstraintNonMatchingCase()
    {
        $this->mock->shouldReceive('foo')->times(3);
        $this->mock->shouldReceive('foo')->with(1, ehough_mockery_Mockery::type('string'))->never();
        $this->mock->foo();
        $this->mock->foo(1);
        $this->mock->foo(1, 2, 3);
        $this->container->mockery_verify();
    }
    
    /**
     * @expectedException ehough_mockery_mockery_Exception
     */
    public function testStringConstraintThrowsExceptionWhenConstraintUnmatched()
    {
        $this->mock->shouldReceive('foo')->with(ehough_mockery_Mockery::type('string'))->once();
        $this->mock->foo(1);
        $this->container->mockery_verify();
    }
    
    public function testClassConstraintMatchesArgument()
    {
        $this->mock->shouldReceive('foo')->with(ehough_mockery_Mockery::type('stdClass'))->once();
        $this->mock->foo(new stdClass);
        $this->container->mockery_verify();
    }
    
    public function testClassConstraintNonMatchingCase()
    {
        $this->mock->shouldReceive('foo')->times(3);
        $this->mock->shouldReceive('foo')->with(1, ehough_mockery_Mockery::type('stdClass'))->never();
        $this->mock->foo();
        $this->mock->foo(1);
        $this->mock->foo(1, 2, 3);
        $this->container->mockery_verify();
    }
    
    /**
     * @expectedException ehough_mockery_mockery_Exception
     */
    public function testClassConstraintThrowsExceptionWhenConstraintUnmatched()
    {
        $this->mock->shouldReceive('foo')->with(ehough_mockery_Mockery::type('stdClass'))->once();
        $this->mock->foo(new Exception);
        $this->container->mockery_verify();
    }
    
    public function testDucktypeConstraintMatchesArgument()
    {
        $this->mock->shouldReceive('foo')->with(ehough_mockery_Mockery::ducktype('quack', 'swim'))->once();
        $this->mock->foo(new Mockery_Duck);
        $this->container->mockery_verify();
    }
    
    public function testDucktypeConstraintNonMatchingCase()
    {
        $this->mock->shouldReceive('foo')->times(3);
        $this->mock->shouldReceive('foo')->with(1, ehough_mockery_Mockery::ducktype('quack', 'swim'))->never();
        $this->mock->foo();
        $this->mock->foo(1);
        $this->mock->foo(1, 2, 3);
        $this->container->mockery_verify();
    }
    
    /**
     * @expectedException ehough_mockery_mockery_Exception
     */
    public function testDucktypeConstraintThrowsExceptionWhenConstraintUnmatched()
    {
        $this->mock->shouldReceive('foo')->with(ehough_mockery_Mockery::ducktype('quack', 'swim'))->once();
        $this->mock->foo(new Mockery_Duck_Nonswimmer);
        $this->container->mockery_verify();
    }
    
    public function testArrayContentConstraintMatchesArgument()
    {
        $this->mock->shouldReceive('foo')->with(ehough_mockery_Mockery::subset(array('a'=>1,'b'=>2)))->once();
        $this->mock->foo(array('a'=>1,'b'=>2,'c'=>3));
        $this->container->mockery_verify();
    }
    
    public function testArrayContentConstraintNonMatchingCase()
    {
        $this->mock->shouldReceive('foo')->times(3);
        $this->mock->shouldReceive('foo')->with(1, ehough_mockery_Mockery::subset(array('a'=>1,'b'=>2)))->never();
        $this->mock->foo();
        $this->mock->foo(1);
        $this->mock->foo(1, 2, 3);
        $this->container->mockery_verify();
    }
    
    /**
     * @expectedException ehough_mockery_mockery_Exception
     */
    public function testArrayContentConstraintThrowsExceptionWhenConstraintUnmatched()
    {
        $this->mock->shouldReceive('foo')->with(ehough_mockery_Mockery::subset(array('a'=>1,'b'=>2)))->once();
        $this->mock->foo(array('a'=>1,'c'=>3));
        $this->container->mockery_verify();
    }
    
    public function testContainsConstraintMatchesArgument()
    {
        $this->mock->shouldReceive('foo')->with(ehough_mockery_Mockery::contains(1, 2))->once();
        $this->mock->foo(array('a'=>1,'b'=>2,'c'=>3));
        $this->container->mockery_verify();
    }
    
    public function testContainsConstraintNonMatchingCase()
    {
        $this->mock->shouldReceive('foo')->times(3);
        $this->mock->shouldReceive('foo')->with(1, ehough_mockery_Mockery::contains(1, 2))->never();
        $this->mock->foo();
        $this->mock->foo(1);
        $this->mock->foo(1, 2, 3);
        $this->container->mockery_verify();
    }
    
    /**
     * @expectedException ehough_mockery_mockery_Exception
     */
    public function testContainsConstraintThrowsExceptionWhenConstraintUnmatched()
    {
        $this->mock->shouldReceive('foo')->with(ehough_mockery_Mockery::contains(1, 2))->once();
        $this->mock->foo(array('a'=>1,'c'=>3));
        $this->container->mockery_verify();
    }
    
    public function testHasKeyConstraintMatchesArgument()
    {
        $this->mock->shouldReceive('foo')->with(ehough_mockery_Mockery::hasKey('c'))->once();
        $this->mock->foo(array('a'=>1,'b'=>2,'c'=>3));
        $this->container->mockery_verify();
    }
    
    public function testHasKeyConstraintNonMatchingCase()
    {
        $this->mock->shouldReceive('foo')->times(3);
        $this->mock->shouldReceive('foo')->with(1, ehough_mockery_Mockery::hasKey('a'))->never();
        $this->mock->foo();
        $this->mock->foo(1);
        $this->mock->foo(1, array('a'=>1), 3);
        $this->container->mockery_verify();
    }
    
    /**
     * @expectedException ehough_mockery_mockery_Exception
     */
    public function testHasKeyConstraintThrowsExceptionWhenConstraintUnmatched()
    {
        $this->mock->shouldReceive('foo')->with(ehough_mockery_Mockery::hasKey('c'))->once();
        $this->mock->foo(array('a'=>1,'b'=>3));
        $this->container->mockery_verify();
    }
    
    public function testHasValueConstraintMatchesArgument()
    {
        $this->mock->shouldReceive('foo')->with(ehough_mockery_Mockery::hasValue(1))->once();
        $this->mock->foo(array('a'=>1,'b'=>2,'c'=>3));
        $this->container->mockery_verify();
    }
    
    public function testHasValueConstraintNonMatchingCase()
    {
        $this->mock->shouldReceive('foo')->times(3);
        $this->mock->shouldReceive('foo')->with(1, ehough_mockery_Mockery::hasValue(1))->never();
        $this->mock->foo();
        $this->mock->foo(1);
        $this->mock->foo(1, array('a'=>1), 3);
        $this->container->mockery_verify();
    }
    
    /**
     * @expectedException ehough_mockery_mockery_Exception
     */
    public function testHasValueConstraintThrowsExceptionWhenConstraintUnmatched()
    {
        $this->mock->shouldReceive('foo')->with(ehough_mockery_Mockery::hasValue(2))->once();
        $this->mock->foo(array('a'=>1,'b'=>3));
        $this->container->mockery_verify();
    }
    
    public function testOnConstraintMatchesArgument_ClosureEvaluatesToTrue()
    {
        $function = function($arg){return $arg % 2 == 0;};
        $this->mock->shouldReceive('foo')->with(ehough_mockery_Mockery::on($function))->once();
        $this->mock->foo(4);
        $this->container->mockery_verify();
    }
    
    /**
     * @expectedException ehough_mockery_mockery_Exception
     */
    public function testOnConstraintThrowsExceptionWhenConstraintUnmatched_ClosureEvaluatesToFalse()
    {
        $function = function($arg){return $arg % 2 == 0;};
        $this->mock->shouldReceive('foo')->with(ehough_mockery_Mockery::on($function))->once();
        $this->mock->foo(5);
        $this->container->mockery_verify();
    }
    
    public function testMustBeConstraintMatchesArgument()
    {
        $this->mock->shouldReceive('foo')->with(ehough_mockery_Mockery::mustBe(2))->once();
        $this->mock->foo(2);
        $this->container->mockery_verify();
    }
    
    public function testMustBeConstraintNonMatchingCase()
    {
        $this->mock->shouldReceive('foo')->times(3);
        $this->mock->shouldReceive('foo')->with(1, ehough_mockery_Mockery::mustBe(2))->never();
        $this->mock->foo();
        $this->mock->foo(1);
        $this->mock->foo(1, 2, 3);
        $this->container->mockery_verify();
    }
    
    /**
     * @expectedException ehough_mockery_mockery_Exception
     */
    public function testMustBeConstraintThrowsExceptionWhenConstraintUnmatched()
    {
        $this->mock->shouldReceive('foo')->with(ehough_mockery_Mockery::mustBe(2))->once();
        $this->mock->foo('2');
        $this->container->mockery_verify();
    }
    
    public function testMustBeConstraintMatchesObjectArgumentWithEqualsComparisonNotIdentical()
    {
        $a = new stdClass; $a->foo = 1;
        $b = new stdClass; $b->foo = 1;
        $this->mock->shouldReceive('foo')->with(ehough_mockery_Mockery::mustBe($a))->once();
        $this->mock->foo($b);
        $this->container->mockery_verify();
    }
    
    public function testMustBeConstraintNonMatchingCaseWithObject()
    {
        $a = new stdClass; $a->foo = 1;
        $this->mock->shouldReceive('foo')->times(3);
        $this->mock->shouldReceive('foo')->with(1, ehough_mockery_Mockery::mustBe($a))->never();
        $this->mock->foo();
        $this->mock->foo(1);
        $this->mock->foo(1, $a, 3);
        $this->container->mockery_verify();
    }
    
    /**
     * @expectedException ehough_mockery_mockery_Exception
     */
    public function testMustBeConstraintThrowsExceptionWhenConstraintUnmatchedWithObject()
    {
        $a = new stdClass; $a->foo = 1;
        $b = new stdClass; $b->foo = 2;
        $this->mock->shouldReceive('foo')->with(ehough_mockery_Mockery::mustBe($a))->once();
        $this->mock->foo($b);
        $this->container->mockery_verify();
    }
    
    public function testMatchPrecedenceBasedOnExpectedCallsFavouringExplicitMatch()
    {
        $this->mock->shouldReceive('foo')->with(1)->once();
        $this->mock->shouldReceive('foo')->with(ehough_mockery_Mockery::any())->never();
        $this->mock->foo(1);
        $this->container->mockery_verify();
    }
    
    public function testMatchPrecedenceBasedOnExpectedCallsFavouringAnyMatch()
    {
        $this->mock->shouldReceive('foo')->with(ehough_mockery_Mockery::any())->once();
        $this->mock->shouldReceive('foo')->with(1)->never();
        $this->mock->foo(1);
        $this->container->mockery_verify();
    }
    
    public function testReturnNullIfIgnoreMissingMethodsSet()
    {
        $this->mock->shouldIgnoreMissing();
        $this->assertNull($this->mock->g(1,2));
    }

    public function testReturnUndefinedIfIgnoreMissingMethodsSet()
    {
        $this->mock->shouldIgnoreMissing()->asUndefined();
        $this->assertTrue($this->mock->g(1,2) instanceof ehough_mockery_mockery_Undefined);
    }
    
    public function testReturnAsUndefinedAllowsForInfiniteSelfReturningChain()
    {
        $this->mock->shouldIgnoreMissing()->asUndefined();
        $this->assertTrue($this->mock->g(1,2)->a()->b()->c() instanceof ehough_mockery_mockery_Undefined);
    }

    public function testShouldIgnoreMissingFluentInterface()
    {
        $this->assertTrue($this->mock->shouldIgnoreMissing() instanceof ehough_mockery_mockery_MockInterface);
    }

    public function testShouldIgnoreMissingAsUndefinedFluentInterface()
    {
        $this->assertTrue($this->mock->shouldIgnoreMissing()->asUndefined() instanceof ehough_mockery_mockery_MockInterface);
    }

    public function testShouldIgnoreMissingAsDefinedProxiesToUndefinedAllowingToString()
    {
        $this->mock->shouldIgnoreMissing()->asUndefined();
        $string = "Method call: {$this->mock->g()}";
        $string = "Mock: {$this->mock}";
    }

    public function testToStringMagicMethodCanBeMocked()
    {
        $this->mock->shouldReceive("__toString")->andReturn('dave');
        $this->assertEquals("{$this->mock}", "dave");
    }

    public function testOptionalMockRetrieval()
    {
        $m = $this->container->mock('f')->shouldReceive('foo')->with(1)->andReturn(3)->mock();
        $this->assertTrue($m instanceof ehough_mockery_mockery_MockInterface);
    }
    
    public function testNotConstraintMatchesArgument()
    {
        $this->mock->shouldReceive('foo')->with(ehough_mockery_Mockery::not(1))->once();
        $this->mock->foo(2);
        $this->container->mockery_verify();
    }
    
    public function testNotConstraintNonMatchingCase()
    {
        $this->mock->shouldReceive('foo')->times(3);
        $this->mock->shouldReceive('foo')->with(1, ehough_mockery_Mockery::not(2))->never();
        $this->mock->foo();
        $this->mock->foo(1);
        $this->mock->foo(1, 2, 3);
        $this->container->mockery_verify();
    }
    
    /**
     * @expectedException ehough_mockery_mockery_Exception
     */
    public function testNotConstraintThrowsExceptionWhenConstraintUnmatched()
    {
        $this->mock->shouldReceive('foo')->with(ehough_mockery_Mockery::not(2))->once();
        $this->mock->foo(2);
        $this->container->mockery_verify();
    }

    public function testAnyOfConstraintMatchesArgument()
    {
        $this->mock->shouldReceive('foo')->with(ehough_mockery_Mockery::anyOf(1, 2))->twice();
        $this->mock->foo(2);
        $this->mock->foo(1);
        $this->container->mockery_verify();
    }
    
    public function testAnyOfConstraintNonMatchingCase()
    {
        $this->mock->shouldReceive('foo')->times(3);
        $this->mock->shouldReceive('foo')->with(1, ehough_mockery_Mockery::anyOf(1, 2))->never();
        $this->mock->foo();
        $this->mock->foo(1);
        $this->mock->foo(1, 2, 3);
        $this->container->mockery_verify();
    }
    
    /**
     * @expectedException ehough_mockery_mockery_Exception
     */
    public function testAnyOfConstraintThrowsExceptionWhenConstraintUnmatched()
    {
        $this->mock->shouldReceive('foo')->with(ehough_mockery_Mockery::anyOf(1, 2))->once();
        $this->mock->foo(3);
        $this->container->mockery_verify();
    }
    
    public function testNotAnyOfConstraintMatchesArgument()
    {
        $this->mock->shouldReceive('foo')->with(ehough_mockery_Mockery::notAnyOf(1, 2))->once();
        $this->mock->foo(3);
        $this->container->mockery_verify();
    }
    
    public function testNotAnyOfConstraintNonMatchingCase()
    {
        $this->mock->shouldReceive('foo')->times(3);
        $this->mock->shouldReceive('foo')->with(1, ehough_mockery_Mockery::notAnyOf(1, 2))->never();
        $this->mock->foo();
        $this->mock->foo(1);
        $this->mock->foo(1, 4, 3);
        $this->container->mockery_verify();
    }
    
    /**
     * @expectedException ehough_mockery_mockery_Exception
     */
    public function testNotAnyOfConstraintThrowsExceptionWhenConstraintUnmatched()
    {
        $this->mock->shouldReceive('foo')->with(ehough_mockery_Mockery::notAnyOf(1, 2))->once();
        $this->mock->foo(2);
        $this->container->mockery_verify();
    }
    
    /**
     * @expectedException ehough_mockery_mockery_Exception
     */
    public function testGlobalConfigMayForbidMockingNonExistentMethodsOnClasses()
    {
        ehough_mockery_Mockery::getConfiguration()->allowMockingNonExistentMethods(false);
        $mock = $this->container->mock('stdClass');
        $mock->shouldReceive('foo');
    }
    
    /**
     * @expectedException ehough_mockery_mockery_Exception
     * @expectedExceptionMessage Mockery's configuration currently forbids mocking 
     */
    public function testGlobalConfigMayForbidMockingNonExistentMethodsOnAutoDeclaredClasses()
    {
        ehough_mockery_Mockery::getConfiguration()->allowMockingNonExistentMethods(false);
        $mock = $this->container->mock('SomeMadeUpClass');
        $mock->shouldReceive('foo');
    }

    /**
     * @expectedException ehough_mockery_mockery_Exception
     */
    public function testGlobalConfigMayForbidMockingNonExistentMethodsOnObjects()
    {
        ehough_mockery_Mockery::getConfiguration()->allowMockingNonExistentMethods(false);
        $mock = $this->container->mock(new stdClass);
        $mock->shouldReceive('foo');
    }
    
    public function testAnExampleWithSomeExpectationAmends()
    {
        $service = $this->container->mock('MyService');
        $service->shouldReceive('login')->with('user', 'pass')->once()->andReturn(true);
        $service->shouldReceive('hasBookmarksTagged')->with('php')->once()->andReturn(false);
        $service->shouldReceive('addBookmark')->with('/^http:/', ehough_mockery_Mockery::type('string'))->times(3)->andReturn(true);
        $service->shouldReceive('hasBookmarksTagged')->with('php')->once()->andReturn(true);
        
        $this->assertTrue($service->login('user', 'pass'));
        $this->assertFalse($service->hasBookmarksTagged('php'));
        $this->assertTrue($service->addBookmark('http://example.com/1', 'some_tag1'));
        $this->assertTrue($service->addBookmark('http://example.com/2', 'some_tag2'));
        $this->assertTrue($service->addBookmark('http://example.com/3', 'some_tag3'));
        $this->assertTrue($service->hasBookmarksTagged('php'));
        
        $this->container->mockery_verify();
    }
    
    public function testAnExampleWithSomeExpectationAmendsOnCallCounts()
    {
        $service = $this->container->mock('MyService');
        $service->shouldReceive('login')->with('user', 'pass')->once()->andReturn(true);
        $service->shouldReceive('hasBookmarksTagged')->with('php')->once()->andReturn(false);
        $service->shouldReceive('addBookmark')->with('/^http:/', ehough_mockery_Mockery::type('string'))->times(3)->andReturn(true);
        $service->shouldReceive('hasBookmarksTagged')->with('php')->twice()->andReturn(true);
        
        $this->assertTrue($service->login('user', 'pass'));
        $this->assertFalse($service->hasBookmarksTagged('php'));
        $this->assertTrue($service->addBookmark('http://example.com/1', 'some_tag1'));
        $this->assertTrue($service->addBookmark('http://example.com/2', 'some_tag2'));
        $this->assertTrue($service->addBookmark('http://example.com/3', 'some_tag3'));
        $this->assertTrue($service->hasBookmarksTagged('php'));
        $this->assertTrue($service->hasBookmarksTagged('php'));
        
        $this->container->mockery_verify();
    }
    
    public function testAnExampleWithSomeExpectationAmendsOnCallCounts_PHPUnitTest()
    {
        $service = $this->getMock('MyService2');
        $service->expects($this->once())->method('login')->with('user', 'pass')->will($this->returnValue(true));
        $service->expects($this->exactly(3))->method('hasBookmarksTagged')->with('php')
            ->will($this->onConsecutiveCalls(false, true, true));
        $service->expects($this->exactly(3))->method('addBookmark')
            ->with($this->matchesRegularExpression('/^http:/'), $this->isType('string'))
            ->will($this->returnValue(true));
        
        $this->assertTrue($service->login('user', 'pass'));
        $this->assertFalse($service->hasBookmarksTagged('php'));
        $this->assertTrue($service->addBookmark('http://example.com/1', 'some_tag1'));
        $this->assertTrue($service->addBookmark('http://example.com/2', 'some_tag2'));
        $this->assertTrue($service->addBookmark('http://example.com/3', 'some_tag3'));
        $this->assertTrue($service->hasBookmarksTagged('php'));
        $this->assertTrue($service->hasBookmarksTagged('php'));
    }

    public function testMockedMethodsCallableFromWithinOriginalClass()
    {
        $mock = $this->container->mock('MockeryTest_InterMethod1[doThird]');
        $mock->shouldReceive('doThird')->andReturn(true);
        $this->assertTrue($mock->doFirst());
    }

    /**
     * @group issue #20
     */
    public function testMockingDemeterChainsPassesMockeryExpectationToCompositeExpectation()
    {
        $mock = $this->container->mock('Mockery_Demeterowski');
        $mock->shouldReceive('foo->bar->baz')->andReturn('Spam!');
        $demeter = new Mockery_UseDemeter($mock);
        $this->assertSame('Spam!', $demeter->doit());
    }

    /**
     * @group issue #20 - with args in demeter chain
     */
    public function testMockingDemeterChainsPassesMockeryExpectationToCompositeExpectationWithArgs()
    {
        $mock = $this->container->mock('Mockery_Demeterowski');
        $mock->shouldReceive('foo->bar->baz')->andReturn('Spam!');
        $demeter = new Mockery_UseDemeter($mock);
        $this->assertSame('Spam!', $demeter->doitWithArgs());
    }

    /**
    * @expectedException PHPUnit_Framework_Error_Warning
    */
    public function testPregMatchThrowsDelimiterWarningWithXdebugScreamTurnedOn()
    {
        if (!extension_loaded('xdebug')) {
            $this->markTestSkipped('ext/xdebug not installed');
        }
        
        if (ini_get('xdebug.scream') == 0) {
            $this->markTestSkipped('xdebug.scream turned off');
        }
        
        $mock = $this->container->mock('foo');
        $mock->shouldReceive('foo')->with('bar', 'baz');
        
        $mock->foo('spam', 'ham');
    }

    public function testPassthruEnsuresRealMethodCalledForReturnValues()
    {
        $mock = $this->container->mock('MockeryTest_SubjectCall1');
        $mock->shouldReceive('foo')->once()->passthru();
        $this->assertEquals('bar', $mock->foo());
        $this->container->mockery_verify();
    }


    public function testShouldIgnoreMissingExpectationBasedOnArgs()
    {
        $mock = $this->container->mock("MyService2")->shouldIgnoreMissing();
        $mock->shouldReceive("hasBookmarksTagged")->with("dave")->once();
        $mock->hasBookmarksTagged("dave");
        $mock->hasBookmarksTagged("padraic");
        $this->container->mockery_verify();
    }

}

class MockeryTest_SubjectCall1 {
    function foo() {return 'bar';}
}

class MockeryTest_InterMethod1
{
    public function doFirst() {
        return $this->doSecond();
    }

    private function doSecond() {
        return $this->doThird();
    }

    public function doThird() {
        return false;
    }
}

class MyService2
{
    public function login($user, $pass){}
    public function hasBookmarksTagged($tag){}
    public function addBookmark($uri, $tag){}
}

class Mockery_Duck {
    function quack(){}
    function swim(){}
}

class Mockery_Duck_Nonswimmer {
    function quack(){}
}

class Mockery_Demeterowski {
    public function foo() {
        return $this;
    }
    public function bar() {
        return $this;
    }
    public function baz() {
        return 'Ham!';
    }
}

class Mockery_UseDemeter {
    public function __construct($demeter) {
        $this->demeter = $demeter;
    }
    public function doit() {
        return $this->demeter->foo()->bar()->baz();
    }
    public function doitWithArgs() {
        return $this->demeter->foo("foo")->bar("bar")->baz("baz");
    }
}

class MockeryTest_Foo {
    public function foo() {}
}
