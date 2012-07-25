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
 * @namespace Proem\Api\Service\Manager
 */
namespace Proem\Api\Service\Manager;

use Proem\Service\Asset\Template as Asset;

/**
 * Interface that all asset managers must implement.
 */
interface Template
{
    /**
     * Magic method to proxy through to set()
     *
     * @param string $index The index the asset will be referenced by.
     * @param Proem\Api\Service\Asset\Template $asset
     * @return Proem\Api\Service\Manager\Template
     */
    public function __set($index, Asset $asset);

    /**
     * Store an Asset container by named index.
     *
     * @param string $index The index the asset will be referenced by.
     * @param Proem\Api\Service\Asset\Template $asset
     * @return Proem\Api\Service\Manager\Template
     */
    public function set($index, Asset $asset);

    /**
     * Retrieve an asset container by named index.
     *
     * @param string $index The index the asset is referenced by.
     * @return Proem\Api\Service\Asset\Template
     */
    public function getContainer($index);

    /**
     * Magic method to proxy through to get().
     *
     * @param string $index The index the asset is referenced by
     * @return object The object provided by the asset container
     */
    public function __get($index);

    /**
     * Retrieve an actual instantiated ssset object from within it's container.
     *
     * @param string $index The index the asset is referenced by
     * @return object The object provided by the asset container
     */
    public function get($index);

    /**
     * Check to see if this manager has a specific asset.
     *
     * @param string $index The index the asset is referenced by
     * @return bool
     */
    public function has($index);

    /**
     * Check to see if this manager provides a specifically named
     * asset and that it provides a specific object.
     *
     * @param string $index
     * @param string|null $provides
     * @return bool
     */
    public function provides($index, $provides = null);

    /**
     * Retrieve an asset by what it provides.
     *
     * @param string $provides
     * @return object
     */
    public function getProvided($provides);

}
