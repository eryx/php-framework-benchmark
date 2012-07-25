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
 * @namespace Proem\Api\Routing\Route
 */
namespace Proem\Api\Routing\Route;

use Proem\Routing\Route\Payload,
    Proem\Util\Opt\Options,
    Proem\Util\Opt\Option,
    Proem\Util\ArrayHelper,
    Proem\Util\Process\Callback,
    Proem\IO\Request\Template as Request,
    Proem\Routing\Route\Template;

/**
 * Generic route abstract.
 */
abstract class Generic implements Template
{
    /**
     * @uses Proem\Api\Util\Opt\Options
     */
    use Options;

    /**
     * @uses Proem\Api\Util\ArrayHelper
     */
    use ArrayHelper;

    /**
     * Store the options for this route
     *
     * @var array
     */
    protected $options = [];

    /**
     * Store a flag indicating a route match
     *
     * @var bool
     */
    protected $matched = false;

    /**
     * Store a flag indicating the presence of a callback.
     *
     * @var bool
     */
    protected $hasCallback = false;

    /**
     * Store matched parameters within a Dispatch\Payload object.
     *
     * @var Proem\Api\Routing\Route\Payload
     */
    protected $payload = null;

    /**
     * Instantiate this route
     *
     * @param array $options An array of Proem\Api\Util\Opt\Option objects
     */
    public function __construct(array $options)
    {
        $this->options = $this->setOptions([
            'rule'      => (new Option)->type('string')->required(),
            'targets'   => (new Option([]))->type('array'),
            'filters'   => (new Option([]))->type('array'),
            'method'    => (new Option(null))->type('string'),
            'callback'  => (new Option)->type('callable')
        ], $options);

        if (is_callable($this->options->callback)) {
            $this->hasCallback = true;
        }

    }

    /**
     * Do we have a callback?
     *
     * @return bool
     */
    public function hasCallback()
    {
        return $this->hasCallback;
    }

    /**
     * Was a match found?
     *
     * @return bool
     */
    public function isMatch()
    {
        return $this->matched;
    }

    /**
     * Set matched flag.
     *
     * @return Proem\Api\Routing\Route\Template
     */
    public function setMatch()
    {
        $this->matched = true;
        return $this;
    }

    /**
     * Retrieve the Payload.
     *
     * @return Proem\Api\Routing\Route\Payload
     */
    public function getPayload()
    {
        if ($this->payload === null) {
            $this->payload = new Payload;
        }

        return $this->payload;
    }

    /**
     * Method used to execute a route callback.
     *
     * @param Proem\IO\Request\Template $request
     */
    public function call(Request $request)
    {
        (new Callback($this->options->callback, $request))->call();
    }

    /**
     * Method to actually test for a match.
     *
     * If a match is found, $this->matched should be set to true
     * and the payload needs to be instantiated to contain the relevent
     * matched data.
     *
     * @param Proem\IO\Request\Template $request
     */
    abstract public function process(Request $request);

}
