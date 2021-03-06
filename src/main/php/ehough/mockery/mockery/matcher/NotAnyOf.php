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

class ehough_mockery_mockery_matcher_NotAnyOf extends ehough_mockery_mockery_matcher_MatcherAbstract
{
    
    /**
     * Set the expected value
     *
     * @param mixed $expected
     */
    public function __construct($expected = null)
    {
        $this->_expected = $expected;
    }
    
    /**
     * Check if the actual value does not match the expected (in this
     * case it's specifically NOT expected).
     *
     * @param mixed $actual
     * @return bool
     */
    public function match(&$actual)
    {
        foreach ($this->_expected as $exp) {
            if ($actual === $exp || $actual == $exp) {
                return false;
            }
        }
        return true;
    }
    
    /**
     * Return a string representation of this Matcher
     *
     * @return string
     */
    public function __toString()
    {
        return '<AnyOf>';
    }
    
}
