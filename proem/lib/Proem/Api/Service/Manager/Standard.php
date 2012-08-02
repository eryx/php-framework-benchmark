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

use Proem\Service\Manager\Template,
    Proem\Service\Asset\Template as Asset;

/**
 * A registry of assets.
 *
 * Within the manager itself assets are stored in a hash of key values where each
 * value is an asset container.
 *
 * These containers contain the parameters required to instantiate an asset as
 * well as a closure capable of returning a configured and instantiated asset.
 *
 * @see Proem\Api\Service\Asset\Standard
 */
class Standard implements Template
{
    /**
     * Store assets.
     *
     * @var $assets array
     */
    protected $assets = [];

    /**
     * Store an array containing information about what
     * Assets this manager provides.
     *
     * @var array
     */
    protected $provides = [];

    /**
     * Magic method proxies through to get()
     *
     * @param string $index The index the asset will be referenced by.
     * @param Proem\Api\Service\Asset\Template $asset
     * @return Proem\Api\Service\Manager\Template
     */
    public function __set($index, Asset $asset)
    {
        return $this->set($index, $asset);
    }

    /**
     * Store an Asset container by named index.
     *
     * @param string $index The index the asset will be referenced by.
     * @param Proem\Api\Service\Asset\Template $asset
     * @return Proem\Api\Service\Manager\Template
     */
    public function set($index, Asset $asset)
    {
        $this->assets[$index] = $asset;
        $this->provides[]     = $asset->provides();
        return $this;
    }

    /**
     * Retrieve an asset container by named index.
     *
     * @param string $index The index the asset is referenced by.
     * @return Proem\Api\Service\Asset\Template
     */
    public function getContainer($index)
    {
        return isset($this->assets[$index]) ? $this->assets[$index] : null;
    }

    /**
     * Magic method proxies through to get().
     *
     * @param string $index The index the asset is referenced by
     * @return object The object provided by the asset container
     */
    public function __get($index)
    {
        return $this->get($index);
    }

    /**
     * Retrieve an actual instantiated ssset object from within it's container.
     *
     * @param string $index The index the asset is referenced by
     * @return object The object provided by the asset container
     */
    public function get($index)
    {
        return isset($this->assets[$index]) ? $this->assets[$index]->get($this) : null;
    }

    /**
     * Check to see if this manager has a specific asset.
     *
     * @param string $index The index the asset is referenced by
     * @return bool
     */
    public function has($index)
    {
        return isset($this->assets[$index]);
    }

    /**
     * Check to see if this manager provides a specifically named
     * asset and that it provides a specific object.
     *
     * <code>
     * if ($am->provies('foo', 'Some\Bar\Object')) {}
     * </code>
     *
     * @param string $index
     * @param string|null $provides
     * @return bool
     */
    public function provides($index, $provides = null)
    {
        if (is_array($index)) {
            foreach ($index as $key) {
                if (!in_array($key, $this->provides)) {
                    return false;
                }
            }
            return true;
        } elseif ($provides === null) {
            return in_array($index, $this->provides);
        } else {
            if ($this->has($index)) {
                if ($this->assets[$index]->provides() == $provides) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * Retrieve an asset by what it provides.
     *
     * When called, this method will search all assets until it
     * finds the first that provides the functionality asked for.
     *
     * It then returns that object.
     *
     * @param string $provides
     * @return object
     */
    public function getProvided($provides)
    {
        foreach ($this->assets as $asset) {
            if ($asset->provides() == $provides) {
                return $asset->get($this);
            }
        }
    }

}
