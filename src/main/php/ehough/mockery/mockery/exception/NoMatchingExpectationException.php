<?php
/**
 * ehough_mockery_Mockery
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://github.com/padraic/mockery/blob/master/LICENSE
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to padraic@php.net so we can send you a copy immediately.
 *
 * @copyright  Copyright (c) 2010 Pádraic Brady (http://blog.astrumfutura.com)
 * @license    http://github.com/padraic/mockery/blob/master/LICENSE New BSD License
 */
 
class ehough_mockery_mockery_exception_NoMatchingExpectationException extends ehough_mockery_mockery_Exception
{

    protected $method = null;

    protected $actual = array();

    protected $mockObject = null;

    public function setMock(ehough_mockery_mockery_MockInterface $mock)
    {
        $this->mockObject = $mock;
        return $this;
    }

    public function setMethodName($name)
    {
        $this->method = $name;
        return $this;
    }

    public function setActualArguments($count)
    {
        $this->actual = $count;
        return $this;
    }

    public function getMock()
    {
        return $this->mockObject;
    }

    public function getMethodName()
    {
        return $this->method;
    }

    public function getActualArguments()
    {
        return $this->actual;
    }

    public function getMockName()
    {
        return $this->getMock()->mockery_getName();
    }

}