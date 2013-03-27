<?php
/**
 * ehough_mockery_Mockery
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://github.com/padraic/mutateme/master/LICENSE
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to padraic@php.net so we can send you a copy immediately.
 *
 *
 *
 * @subpackage UnitTests
 * @copyright  Copyright (c) 2012 Pádraic Brady (http://blog.astrumfutura.com)
 * @license    http://github.com/padraic/mockery/blob/master/LICENSE New BSD License
 */

/**
 * Ad-hoc unit tests for various scenarios reported by users
 */
class Mockery_AdhocTest extends PHPUnit_Framework_TestCase
{

    public function setup ()
    {
        $this->container = new ehough_mockery_mockery_Container;
    }
    
    public function teardown()
    {
        $this->container->mockery_close();
    }

    public function testSimplestMockCreation()
    {
        $m = $this->container->mock('MockeryTest_NameOfExistingClass');
        $this->assertTrue($m instanceof MockeryTest_NameOfExistingClass);
    }

    public function testMockeryInterfaceForClass()
    {
        $m = $this->container->mock('SplFileInfo');
        $this->assertTrue($m instanceof ehough_mockery_mockery_MockInterface);
    }

    public function testMockeryInterfaceForNonExistingClass()
    {
        $m = $this->container->mock('ABC_IDontExist');
        $this->assertTrue($m instanceof ehough_mockery_mockery_MockInterface);
    }

    public function testMockeryInterfaceForInterface()
    {
        $m = $this->container->mock('MockeryTest_NameOfInterface');
        $this->assertTrue($m instanceof ehough_mockery_mockery_MockInterface);
    }

    public function testMockeryInterfaceForAbstract()
    {
        $m = $this->container->mock('MockeryTest_NameOfAbstract');
        $this->assertTrue($m instanceof ehough_mockery_mockery_MockInterface);
    }


}

class MockeryTest_NameOfExistingClass {}

interface MockeryTest_NameOfInterface {
    public function foo();
}

abstract class MockeryTest_NameOfAbstract {
    abstract public function foo();
}