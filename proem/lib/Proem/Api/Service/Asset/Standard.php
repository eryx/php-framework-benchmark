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

use Proem\Service\Asset\Template,
    Proem\Service\Manager\Template as Manager;

/**
 * Standard asset container.
 *
 * Asset containers are reponsible for instantiating assets. The containers themselves
 * are capable of holding all the parameters that might be required to configure an object
 * as well as having the ability to instantiate an object using these parameters via a
 * closure.
 */
class Standard implements Template
{
    /**
     * Store any required parameters.
     *
     * @var array @params
     */
    protected $params = [];

    /**
     * The Closure responsible for instantiating the payload.
     *
     * @var closure $asset
     */
    protected $asset;

    /**
     * Store a flag indicating what object this Asset provides.
     *
     * @var string $provides
     */
    protected $provides = null;

    /**
     * Validate that this object is what it advertises.
     *
     * @param object
     */
    protected function validate($object)
    {
        $object = (object) $object;
        if ($this->provides === null) {
            throw new \DomainException("Asset must advertise what it provides");
        }

        if ($object instanceof $this->provides) {
            return $object;
        }

        throw new \DomainException(sprintf("The Asset providing %s actually provides a %s object", $this->provides, get_class($object)));
    }

    /**
     * Retrieve what this object provides.
     *
     * @return string
     */
    public function provides()
    {
        return $this->provides;
    }

    /**
     * Set a parameter by named index.
     *
     * @param string $index
     * @param mixed $value
     * @return Proem\Api\Service\Asset\Template
     */
    public function setParam($index, $value)
    {
        $this->params[$index] = $value;
        return $this;
    }

    /**
     * A magic method shortcut that proxies to setParam().
     *
     * @param string $index
     * @param mixed $value
     * @return Proem\Api\Service\Asset\Template
     */
    public function __set($index, $value) {
        return $this->setParam($index, $value);
    }

    /**
     * Set multiple parameters use a key => value array.
     *
     * @param array $params
     * @return Proem\Api\Service\Asset\Template
     */
    public function setParams(array $params)
    {
        $this->params = array_merge($this->params, $params);
        return $this;
    }

    /**
     * Retrieve a parameter by named index.
     *
     * @param string $index
     */
    public function getParam($index)
    {
        return isset($this->params[$index]) ? $this->params[$index] : null;
    }

    /**
     * A magic method shortcut that proxies getParam().
     *
     * @param string $index
     */
    public function __get($index) {
        return $this->getParam($index);
    }

    /**
     * Retrieve all parameters.
     *
     * @return array
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Store the Closure reponsible for instantiating an asset.
     *
     * @param string The object this asset provides
     * @param Closure $closure
     * @return Proem\Api\Service\Asset\Template
     */
    public function set($provides, \Closure $closure)
    {
        $this->provides = $provides;
        $this->asset = $closure;
        return $this;
    }

    /**
     * Magic method proxies through to get()
     */
    public function __invoke()
    {
        return $this->get();
    }

    /**
     * Validate and retrieve an instantiated asset.
     *
     * Here the closure is passed this asset container and optionally a
     * Proem\Service\Manager\Template implementation.
     *
     * This provides the closure with the ability to use any required parameters
     * and also be able to call upon any other assets stored in the service manager.
     *
     * @param Proem\Api\Service\Manager\Template $assetManager
     */
    public function get(Manager $assetManager = null)
    {
        $asset = $this->asset;
        return $this->validate($asset($this, $assetManager));
    }

    /**
     * Store an asset in such a way that when it is retrieved it will always return
     * the same instance.
     *
     * Here we wrap a closure within a closure and store the returned value (an asset)
     * of the inner closure within a static variable in the outer closure. This insures
     * that whenever this Asset is retrieved it will always return the same instance.
     *
     * <code>
     * $foo = new Asset;
     * $foo->setAsset($foo->single(function() {
     *      return new Foo;
     * }));
     * </code>
     *
     * @param closure $closure
     */
    public function single(\Closure $closure)
    {
        return function ($assetContainer = null, $assetManager = null) use ($closure) {
            static $obj;
            if (is_null($obj)) {
                $obj = $this->validate($closure($assetContainer, $assetManager));
            }
            return $obj;
        };
    }

}
