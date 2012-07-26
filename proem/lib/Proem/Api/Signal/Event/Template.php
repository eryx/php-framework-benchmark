<?php

/**
 * The MIT License
 *
 * Copyright (c) 2010 - 2012 Tony R Quilkey <trq@proemframework.org>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */


/**
 * @namespace Proem\Api\Signal\Event
 */
namespace Proem\Api\Signal\Event;

use Proem\Util\Opt\Options,
    Proem\Util\Opt\Option;

/**
 * Interface that all events must implement.
 */
interface Template
{
    /**
     * Set params
     *
     * @param array $params
     * @return Proem\Api\Signal\Event\Template
     */
    public function setParams(array $params);

    /**
     * Retrieve any parameters passed to this Event
     *
     * @return array
     */
    public function getParams();

    /**
     * Set the name
     *
     * The name of the event that was triggered.
     *
     * @param string $name
     * @return Proem\Api\Signal\Event\Template
     */
    public function setName($name);

    /**
     * Retrieve the event name
     *
     * @return string The name of the triggered event.
     */
    public function getName();

    /**
     * Set the target.
     *
     * The target should be an instance of whatever object
     * this event was triggered from.
     *
     * @param object $target
     * @return Proem\Api\Signal\Event\Template
     */
    public function setTarget($target);

    /**
     * Retrieve target.
     *
     * @return object
     */
    public function getTarget();

    /**
     * Set the method.
     *
     * The method should be a string representing the name of
     * the method which has triggered this event.
     *
     * @param string $method
     * @return Proem\Api\Signal\Event\Template
     */
    public function setMethod($method);

    /**
     * Retrieve method
     *
     * @return object
     */
    public function getMethod();

}
