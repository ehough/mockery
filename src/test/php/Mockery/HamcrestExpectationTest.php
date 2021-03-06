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
 * @copyright  Copyright (c) 2011 Pádraic Brady (http://blog.astrumfutura.com)
 * @license    http://github.com/padraic/mockery/blob/master/LICENSE New BSD License
 */

class HamcrestExpectationTest extends PHPUnit_Framework_TestCase
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
    
    /** Just a quickie roundup of a few Hamcrest matchers to check nothing obvious out of place **/
    
    public function testAnythingConstraintMatchesArgument()
    {
        $this->mock->shouldReceive('foo')->with(anything())->once();
        $this->mock->foo(2);
        $this->container->mockery_verify();
    }
    
    public function testGreaterThanConstraintMatchesArgument()
    {
        $this->mock->shouldReceive('foo')->with(greaterThan(1))->once();
        $this->mock->foo(2);
        $this->container->mockery_verify();
    }
    
    /**
     * @expectedException ehough_mockery_mockery_Exception
     */
    public function testGreaterThanConstraintNotMatchesArgument()
    {
        $this->mock->shouldReceive('foo')->with(greaterThan(1))->once();
        $this->mock->foo(1);
        $this->container->mockery_verify();
    }
    
}
