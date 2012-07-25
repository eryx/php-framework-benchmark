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
 * @namespace Proem\Api\Bootstrap\Signal\Event\Bootstrap
 */
namespace Proem\Api\Bootstrap\Signal\Event;

use Proem\Service\Manager\Template as Manager,
    Proem\Signal\Event\Standard as StandardEvent;

/**
 * A custom event used by the bootstrap triggered events.
 */
class Bootstrap extends StandardEvent
{
    /**
     * Store the service manager.
     *
     * @var Proem\Api\Service\Manager\Template
     */
    protected $serviceManager;

    /**
     * Store the environment.
     *
     * @var string $environment
     */
    protected $environment;

    /**
     * Set the service manager
     *
     * @param Proem\Api\Service\Manager $serviceManager
     * @return Proem\Api\Bootstrap\Signal\Event\Bootstrap
     */
    public function setServiceManager(Manager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
        return $this;
    }

    /**
     * Retrieve the service manager
     *
     * @return Proem\Api\Service\Manager
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }

    /**
     * Set the environment
     *
     * @param string $environment
     * @return Proem\Api\Bootstrap\Signal\Event\Bootstrap
     */
    public function setEnvironment($environment)
    {
        $this->environment = $environment;
        return $this;
    }

    /**
     * Retrieve the environment
     *
     * @return string $environment
     */
    public function getEnvironment()
    {
        return $this->environment;
    }

}
