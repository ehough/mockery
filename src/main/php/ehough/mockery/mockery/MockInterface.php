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
 *
 *
 * @copyright  Copyright (c) 2010 Pádraic Brady (http://blog.astrumfutura.com)
 * @license    http://github.com/padraic/mockery/blob/master/LICENSE New BSD License
 */
 
interface ehough_mockery_mockery_MockInterface
{
    
    /**
     * Alternative setup method to constructor
     *
     * @param string $name
     * @param ehough_mockery_mockery_Container $container
     * @param object $partialObject
     * @return void
     */
    public function mockery_init($name, ehough_mockery_mockery_Container $container = null, $partialObject = null);
    
    /**
     * Set expected method calls
     *
     * @param mixed
     * @return ehough_mockery_mockery_Expectation
     */
    public function shouldReceive();
    
    /**
     * Set mock to ignore unexpected methods and return Undefined class
     *
     * @return ehough_mockery_mockery_Mock
     */
    public function shouldIgnoreMissing();
    
    /**
     * Set mock to defer unexpected methods to it's parent if possible
     *
     * @return ehough_mockery_mockery_Mock
     */
    public function shouldDeferMissing();

    /**
     * In the event shouldReceive() accepting an array of methods/returns
     * this method will switch them from normal expectations to default
     * expectations
     *
     * @return self
     */
    public function byDefault();
    
    /**
     * Capture calls to this mock and check against expectations
     *
     * @param string $method
     * @param array $args
     * @return mixed
     */
        /**
         * Unfortunately we need to allow type hinting agnostic __call()
         * definitions since any interface/class being mocked can go either
         * way.
         */
    //public function __call($method, array $args);
    
    /**
     * Iterate across all expectation directors and validate each
     *
     * @throws ehough_mockery_mockery_countvalidator_Exception
     * @return void
     */
    public function mockery_verify();
    
    /**
     * Tear down tasks for this mock
     *
     * @return void
     */
    public function mockery_teardown();
    
    /**
     * Fetch the next available allocation order number
     *
     * @return int
     */
    public function mockery_allocateOrder();
    
    /**
     * Set ordering for a group
     *
     * @param mixed $group
     * @param int $order
     */
    public function mockery_setGroup($group, $order);
    
    /**
     * Fetch array of ordered groups
     *
     * @return array
     */
    public function mockery_getGroups();
    
    /**
     * Set current ordered number
     *
     * @param int $order
     */
    public function mockery_setCurrentOrder($order);
    
    /**
     * Get current ordered number
     *
     * @return int
     */
    public function mockery_getCurrentOrder();
    
    /**
     * Validate the current mock's ordering
     *
     * @param string $method
     * @param int $order
     * @throws ehough_mockery_mockery_Exception
     * @return void
     */
    public function mockery_validateOrder($method, $order);
    
    /**
     * Gets the count of expectations for this mock
     *
     * @return int
     */
    public function mockery_getExpectationCount();
    
    /**
     * Return the expectations director for the given method
     *
     * @var string $method
     * @return ehough_mockery_mockery_ExpectationDirector|null
     */
    public function mockery_setExpectationsFor($method, ehough_mockery_mockery_ExpectationDirector $director);
    
    /**
     * Return the expectations director for the given method
     *
     * @var string $method
     * @return ehough_mockery_mockery_ExpectationDirector|null
     */
    public function mockery_getExpectationsFor($method);
    
    /**
     * Find an expectation matching the given method and arguments
     *
     * @var string $method
     * @var array $args
     * @return ehough_mockery_mockery_Expectation|null
     */
    public function mockery_findExpectation($method, array $args);
    
    /**
     * Return the container for this mock
     *
     * @return ehough_mockery_mockery_Container
     */
    public function mockery_getContainer();
    
    /**
     * Return the name for this mock
     *
     * @return string
     */
    public function mockery_getName();

}
