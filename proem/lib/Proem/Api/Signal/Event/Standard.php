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
    Proem\Util\Opt\Option,
    Proem\Signal\Event\Template;

/**
 * The standard event implementation
 */
class Standard implements Template
{
    /**
     * TODO: This can likely be removed.
     *
     * @use Proem\Api\Util\Opt\Options
     */
    use Options;

    /**
     * Store params
     *
     * @var array
     */
    protected $params;

    /**
     * Store the name of the event
     *
     * @var string $name
     */
    protected $name = null;

    /**
     * Instantiate the event and set it's name.
     */
    public function __construct($name)
    {
        $this->name = $name;
    }

    /**
     * Set params
     *
     * @param array $params
     * @return Proem\Api\Signal\Event\Template
     */
    public function setParams(array $params)
    {
        $this->params = $params;
        return $this;
    }

    /**
     * Retrieve any parameters passed to this Event
     *
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Set the name
     *
     * Set the name of the event being triggered.
     *
     * @param string $name
     * @return Proem\Api\Signal\Event\Template
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Retrieve the event name.
     *
     * @return string The name of the event triggered.
     */
    public function getName()
    {
        return $this->name;
    }

}
