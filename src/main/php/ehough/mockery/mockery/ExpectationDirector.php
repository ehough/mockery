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
 
class ehough_mockery_mockery_ExpectationDirector
{

    /**
     * Method name the director is directing
     *
     * @var string
     */
    protected $_name = null;
    
    /**
     * Mock object the director is attached to
     *
     * @var ehough_mockery_mockery_MockInterface
     */
    protected $_mock = null;
    
    /**
     * Stores an array of all expectations for this mock
     *
     * @var array
     */
    protected $_expectations = array();
    
    /**
     * The expected order of next call
     *
     * @var int
     */
    protected $_expectedOrder = null;
    
    /**
     * Stores an array of all default expectations for this mock
     *
     * @var array
     */
    protected $_defaults = array();
    
    /**
     * Constructor
     *
     * @param string $name
     * @param ehough_mockery_mockery_MockInterface $mock
     */
    public function __construct($name, ehough_mockery_mockery_MockInterface $mock)
    {
        $this->_name = $name;
        $this->_mock = $mock;
    }
    
    /**
     * Add a new expectation to the director
     *
     * @param ehough_mockery_mockery_Expectation $expectation
     */
    public function addExpectation(ehough_mockery_mockery_Expectation $expectation)
    {
        $this->_expectations[] = $expectation;
    }
    
    /**
     * Handle a method call being directed by this instance
     *
     * @param array $args
     * @return mixed
     */
    public function call(array $args)
    {
        $expectation = $this->findExpectation($args);
        if (is_null($expectation)) {
            $exception = new ehough_mockery_mockery_exception_NoMatchingExpectationException(
                'No matching handler found for '
                . $this->_mock->mockery_getName() . '::'
                . ehough_mockery_Mockery::formatArgs($this->_name, $args)
                . '. Either the method was unexpected or its arguments matched'
                . ' no expected argument list for this method'
                . PHP_EOL . PHP_EOL
                . ehough_mockery_Mockery::formatObjects($args)
            );
            $exception->setMock($this->_mock)
                ->setMethodName($this->_name)
                ->setActualArguments($args);
            throw $exception;
        }
        return $expectation->verifyCall($args);
    }
    
    /**
     * Verify all expectations of the director
     *
     * @throws ehough_mockery_mockery_countvalidator_Exception
     * @return void
     */
    public function verify()
    {
        if (!empty($this->_expectations)) {
            foreach ($this->_expectations as $exp) {
                $exp->verify();
            }
        } else {
            foreach ($this->_defaults as $exp) {
                $exp->verify();
            }
        }
    }
    
    /**
     * Attempt to locate an expecatation matching the provided args
     *
     * @param array $args
     * @return mixed
     */
    public function findExpectation(array $args)
    {
        if (!empty($this->_expectations)) {
            return $this->_findExpectationIn($this->_expectations, $args);
        } else {
            return $this->_findExpectationIn($this->_defaults, $args);
        }
    }
    
    /**
     * Make the given expectation a default for all others assuming it was
     * correctly created last
     *
     * @param ehough_mockery_mockery_Expectation
     */
    public function makeExpectationDefault(ehough_mockery_mockery_Expectation $expectation)
    {
        $last = end($this->_expectations);
        if ($last === $expectation) {
            array_pop($this->_expectations);
            array_unshift($this->_defaults, $expectation);
        } else {
            throw new ehough_mockery_mockery_Exception(
                'Cannot turn a previously defined expectation into a default'
            );
        }
    }
    
    /**
     * Search current array of expectations for a match
     *
     * @param array $expectations
     * @param array $args
     * @return mixed
     */
    protected function _findExpectationIn(array $expectations, array $args)
    {
        foreach ($expectations as $exp) {
            if ($exp->matchArgs($args) && $exp->isEligible()) {
                return $exp;
            }
        }
        foreach ($expectations as $exp) {
            if ($exp->matchArgs($args)) {
                return $exp;
            }
        }
    }
    
    /**
     * Return all expectations assigned to this director
     *
     * @return array
     */
    public function getExpectations()
    {
        return $this->_expectations;
    }
    
    /**
     * Return the number of expectations assigned to this director.
     *
     * @return int
     */
    public function getExpectationCount()
    {
        return count($this->getExpectations());    
    }

}
