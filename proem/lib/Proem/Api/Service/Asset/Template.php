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
 * @namespace Proem\Api\Service\Asset
 */
namespace Proem\Api\Service\Asset;

use Proem\Service\Manager\Template as Manager;

/**
 * Interface that all assets must implement.
 */
interface Template
{
    /**
     * Retrieve what this object provides.
     *
     * @return string
     */
    public function provides();

    /**
     * Set a parameter by named index.
     *
     * @param string $index
     * @param mixed $value
     * @return Proem\Api\Service\Asset\Template
     */
    public function setParam($index, $value);

    /**
     * Set multiple parameters use a key => value array.
     *
     * @param array $params
     * @return Proem\Api\Service\Asset\Template
     */
    public function setParams(array $params);

    /**
     * Retrieve a parameter by named index.
     *
     * @param string $index
     */
    public function getParam($index);

    /**
     * Retrieve all parameters.
     *
     * @return array
     */
    public function getParams();

    /**
     * Store the Closure reponsible for instantiating an asset.
     *
     * @param string The object this asset provides
     * @param Closure $closure
     * @return Proem\Api\Service\Asset\Template
     */
    public function set($provides, \Closure $closure);

    /**
     * Validate and retrieve an instantiated asset.
     *
     * @param Proem\Api\Service\Manager\Template $assetManager
     */
    public function get(Manager $assetManager = null);

    /**
     * Magic method to proxy through to get().
     */
    public function __invoke();

    /**
     * Store an asset in such a way that when it is retrieved it will always return
     * the same instance.
     *
     * @param closure $closure
     */
    public function single(\Closure $closure);

}
