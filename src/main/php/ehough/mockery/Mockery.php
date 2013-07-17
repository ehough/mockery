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

class ehough_mockery_Mockery
{
    const BLOCKS = 'Mockery_Forward_Blocks';

    /**
     * Global container to hold all mocks for the current unit test running
     *
     * @var ehough_mockery_mockery_Container
     */
    protected static $_container = null;

    /**
     * Global configuration handler containing configuration options
     *
     * @var ehough_mockery_mockery_Configuration
     */
    protected static $_config = null;

    /**
     * Static shortcut to ehough_mockery_mockery_Container::mock()
     *
     * @return ehough_mockery_mockery_MockInterface
     */
    public static function mock()
    {
        if (is_null(self::$_container)) {
            self::$_container = new ehough_mockery_mockery_Container;
        }
        $args = func_get_args();
        return call_user_func_array(array(self::$_container, 'mock'), $args);
    }

    public static function instanceMock()
    {
        if (is_null(self::$_container)) {
            self::$_container = new ehough_mockery_mockery_Container;
        }
        $args = func_get_args();
        return call_user_func_array(array(self::$_container, 'instanceMock'), $args);
    }

    /**
     * Static shortcut to ehough_mockery_mockery_Container::self()
     *
     * @return ehough_mockery_mockery_MockInterface
     */
    public static function self()
    {
        if (is_null(self::$_container)) {
            throw new LogicException("You have not declared any mocks yet");
        }

        return self::$_container->self();
    }
    
    /**
     * Static shortcut to closing up and verifying all mocks in the global
     * container, and resetting the container static variable to null
     *
     * @return void
     */
    public static function close()
    {
        if (is_null(self::$_container)) return;
        self::$_container->mockery_teardown();
        self::$_container->mockery_close();
        self::$_container = null;
    }

    /**
     * Static fetching of a mock associated with a name or explicit class poser
     */
    public static function fetchMock($name)
    {
        return self::$_container->fetchMock($name);
    }

    /**
     * Get the container
     */
    public static function getContainer()
    {
        return self::$_container;
    }

    /**
     * Set the container
     */
    public static function setContainer(ehough_mockery_mockery_Container $container)
    {
        return self::$_container = $container;
    }

    /**
     * Reset the container to NULL
     */
    public static function resetContainer()
    {
        self::$_container = null;
    }

    /**
     * Return instance of ANY matcher
     *
     * @return
     */
    public static function any()
    {
        $return = new ehough_mockery_mockery_matcher_Any();
        return $return;
    }

    /**
     * Return instance of TYPE matcher
     *
     * @return
     */
    public static function type($expected)
    {
        $return = new ehough_mockery_mockery_matcher_Type($expected);
        return $return;
    }

    /**
     * Return instance of DUCKTYPE matcher
     *
     * @return
     */
    public static function ducktype()
    {
        //http://stackoverflow.com/questions/4979507/difference-in-behaviour-of-func-num-args-func-get-arg-and-func-get-args-from-php
        $args = func_get_args();
        $return = new ehough_mockery_mockery_matcher_Ducktype($args);
        return $return;
    }

    /**
     * Return instance of SUBSET matcher
     *
     * @return
     */
    public static function subset(array $part)
    {
        $return = new ehough_mockery_mockery_matcher_Subset($part);
        return $return;
    }

    /**
     * Return instance of CONTAINS matcher
     *
     * @return
     */
    public static function contains()
    {
        //http://stackoverflow.com/questions/4979507/difference-in-behaviour-of-func-num-args-func-get-arg-and-func-get-args-from-php
        $args = func_get_args();
        $return = new ehough_mockery_mockery_matcher_Contains($args);
        return $return;
    }

    /**
     * Return instance of HASKEY matcher
     *
     * @return
     */
    public static function hasKey($key)
    {
        $return = new ehough_mockery_mockery_matcher_HasKey($key);
        return $return;
    }

    /**
     * Return instance of HASVALUE matcher
     *
     * @return
     */
    public static function hasValue($val)
    {
        $return = new ehough_mockery_mockery_matcher_HasValue($val);
        return $return;
    }

    /**
     * Return instance of CLOSURE matcher
     *
     * @return
     */
    public static function on($callback)
    {
        $return = new ehough_mockery_mockery_matcher_Closure($callback);
        return $return;
    }

    /**
     * Return instance of MUSTBE matcher
     *
     * @return
     */
    public static function mustBe($expected)
    {
        $return = new ehough_mockery_mockery_matcher_MustBe($expected);
        return $return;
    }

    /**
     * Return instance of NOT matcher
     *
     * @return
     */
    public static function not($expected)
    {
        $return = new ehough_mockery_mockery_matcher_Not($expected);
        return $return;
    }

    /**
     * Return instance of ANYOF matcher
     *
     * @return
     */
    public static function anyOf()
    {
        //http://stackoverflow.com/questions/4979507/difference-in-behaviour-of-func-num-args-func-get-arg-and-func-get-args-from-php
        $args = func_get_args();
        $return = new ehough_mockery_mockery_matcher_AnyOf($args);
        return $return;
    }

    /**
     * Return instance of NOTANYOF matcher
     *
     * @return
     */
    public static function notAnyOf()
    {
        //http://stackoverflow.com/questions/4979507/difference-in-behaviour-of-func-num-args-func-get-arg-and-func-get-args-from-php
        $args = func_get_args();
        $return = new ehough_mockery_mockery_matcher_NotAnyOf($args);
        return $return;
    }

    /**
     * Get the global configuration container
     */
    public static function getConfiguration()
    {
        if (is_null(self::$_config)) {
            self::$_config = new ehough_mockery_mockery_Configuration;
        }
        return self::$_config;
    }

    /**
     * Utility method to format method name and args into a string
     *
     * @param string $method
     * @param array $args
     * @return string
     */
    public static function formatArgs($method, array $args = null)
    {
        $return = $method . '(';
        if ($args && !empty($args)) {
            $parts = array();
            foreach($args as $arg) {
                if (is_object($arg)) {
                    $parts[] = get_class($arg);
                } elseif (is_int($arg) || is_float($arg)) {
                    $parts[] = $arg;
                } elseif (is_array($arg)) {
                    $parts[] = 'Array';
                } else {
                    $parts[] = '"' . (string) $arg . '"';
                }
            }
            $return .= implode(', ', $parts); // TODO: improve format

        }
        $return .= ')';
        return $return;
    }

    /**
     * Utility function to format objects to printable arrays
     *
     * @param array $args
     * @return string
     */
    public static function formatObjects(array $args = null)
    {
        $hasObjects = false;
        $parts = array();
        $return = 'Objects: (';
        if ($args && !empty($args)) {
            foreach($args as $arg) {
                if (is_object($arg)) {
                    $hasObjects = true;
                    $parts[get_class($arg)] = self::_objectToArray($arg);
                }
            }
        }
        $return .= var_export($parts, true);
        $return .= ')';
        $return = $hasObjects ? $return : '';
        return $return;
    }

    /**
     * Utility function to turn public properties
     * and public get* and is* method values into an array
     *
     * @param object $object
     * @return string
     */
    private static function _objectToArray($object, $nesting = 3)
    {
        if ($nesting == 0) {
            return array('...');
        }
        $reflection = new ReflectionClass($object);
        $properties = array();
        foreach ($reflection->getProperties(ReflectionProperty::IS_PUBLIC) as $publicProperty)
        {
            if ($publicProperty->isStatic()) continue;
            $name = $publicProperty->getName();
            $properties[$name] = self::_cleanupNesting($object->$name, $nesting);
        }

        $getters = array();
        foreach ($reflection->getMethods(ReflectionProperty::IS_PUBLIC) as $publicMethod)
        {
            if ($publicMethod->isStatic()) continue;
            $name = $publicMethod->getName();
            $numberOfParameters = $publicMethod->getNumberOfParameters();
            if ((substr($name, 0, 3) === 'get' || substr($name, 0, 2) === 'is') && $numberOfParameters == 0) {
                try {
                    $getters[$name] = self::_cleanupNesting($object->$name(), $nesting);
                } catch(Exception $e) {
                    $getters[$name] = '!! ' . get_class($e) . ': ' . $e->getMessage() . ' !!';
                }
            }
        }
        return array('class' => get_class($object), 'properties' => $properties, 'getters' => $getters);
    }

    private static function _cleanupNesting($arg, $nesting) {
        if (is_object($arg)) {
            $object = self::_objectToArray($arg, $nesting - 1);
            $object['class'] = get_class($arg);
            return $object;
        } elseif (is_array($arg)) {
            return self::_cleanupArray($arg, $nesting -1 );
        }
        return $arg;
    }

    private static function _cleanupArray($arg, $nesting = 3) {
        if ($nesting == 0) {
            return '...';
        }
        foreach ($arg as $key => $value) {
            if (is_array($value)) {
                $arg[$key] = self::_cleanupArray($value, $nesting -1);
            } elseif (is_object($value)) {
                $arg[$key] = self::_objectToArray($value, $nesting - 1);
            }
        }
        return $arg;
    }

    /**
     * Utility function to parse shouldReceive() arguments and generate
     * expectations from such as needed.
     *
     * @param ehough_mockery_mockery_MockInterface
     * @param array $args
     * @return ehough_mockery_mockery_CompositeExpectation
     */
    public static function parseShouldReturnArgs(ehough_mockery_mockery_MockInterface $mock, $args, $add)
    {
        $composite = new ehough_mockery_mockery_CompositeExpectation;
        foreach ($args as $arg) {
            if (is_array($arg)) {
                foreach($arg as $k=>$v) {
                    $expectation = self::_buildDemeterChain($mock, $k, $add)->andReturn($v);
                    $composite->add($expectation);
                }
            } elseif (is_string($arg)) {
                $expectation = self::_buildDemeterChain($mock, $arg, $add);
                $composite->add($expectation);
            }
        }
        return $composite;
    }

    /**
     * Sets up expectations on the members of the CompositeExpectation and
     * builds up any demeter chain that was passed to shouldReceive
     *
     * @param ehough_mockery_mockery_MockInterface $mock
     * @param string $arg
     * @param Closure $add
     * @return ehough_mockery_mockery_ExpectationDirector
     */
    protected static function _buildDemeterChain(ehough_mockery_mockery_MockInterface $mock, $arg, $add)
    {
        $container = $mock->mockery_getContainer();
        $names = explode('->', $arg);
        reset($names);
        if (!ehough_mockery_Mockery::getConfiguration()->mockingNonExistentMethodsAllowed()
        && method_exists($mock, "mockery_getMockableMethods")
        && !in_array(current($names), $mock->mockery_getMockableMethods())) {
            throw new ehough_mockery_mockery_Exception(
                'Mockery\'s configuration currently forbids mocking the method '
                . current($names) . ' as it does not exist on the class or object '
                . 'being mocked'
            );
        }
        $exp = null;
        $nextExp = array('ehough_mockery_Mockery', '_callbackNextExp1');//function ($n) use ($add) {return $add($n);};
        $useArg = $add;
        while (true) {
            $method = array_shift($names);
            $exp = $mock->mockery_getExpectationsFor($method);
            $needNew = false;
            if (is_null($exp) || empty($names)) {
                $needNew = true;
            }
            if ($needNew) $exp = call_user_func($nextExp, $method, $useArg);  //$nextExp($method);
            if (empty($names)) break;
            if ($needNew) {
                $mock = $container->mock('demeter_' . $method);
                $exp->andReturn($mock);
            }
            $nextExp = array('ehough_mockery_Mockery', '_callbackNextExp2');  //function ($n) use ($mock) {return $mock->shouldReceive($n);};
            $useArg = $mock;
        }
        return $exp;
    }

    public static function _callbackNextExp2($method, $mock)
    {
        return $mock->shouldReceive($method);
    }

    public static function _callbackNextExp1($method, $add)
    {
        return call_user_func($add, $method);
    }
}
