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
 * @namespace Proem\Api\Filter\Event
 */
namespace Proem\Api\Filter\Event;

use Proem\Filter\Manager\Standard as FilterManager,
    Proem\Service\Manager\Template as ServiceManager,
    Proem\Filter\Event\Template as Template;

/**
 * Filter event abstract
 *
 * @todo A lot of the functionality described in this abstract
 * is really only useful when used by the bootstrap process. The
 * pre* and post* events should likely be moved into another filter
 * designed specifically for the bootstrap.
 */
abstract class Generic implements Template
{
    /**
     * Called prior to inBound
     *
     * @param Proem\Api\Service\Manager\Template $assets
     */
    public function preIn(ServiceManager $assets) {}

    /**
     * Define the method to be called on the way into the filter.
     *
     * @param Proem\Api\Service\Manager\Template $assets
     */
    public abstract function inBound(ServiceManager $assets);

    /**
     * Called after inBound
     *
     * @param Proem\Api\Service\Manager\Template $assets
     */
    public function postIn(ServiceManager $assets) {}

    /**
     * Called prior outBound
     *
     * @param Proem\Api\Service\Manager\Template $assets
     */
    public function preOut(ServiceManager $assets) {}

    /**
     * Define the method to be called on the way out of the filter.
     *
     * @param Proem\Api\Service\Manager\Template $assets
     */
    public abstract function outBound(ServiceManager $assets);

    /**
     * Called after outBound
     *
     * @param Proem\Api\Service\Manager\Template $assets
     */
    public function postOut(ServiceManager $assets) {}

    /**
     * Execute this event.
     *
     * Executes preIn(), inBound() and postIn() then init() on the next event
     * in the filter chain before returning to execute preOut(), outBound()
     * and finally postOut().
     *
     * @param Proem\Api\Filter\Manager $filterManager
     * @return Proem\Api\Filter\Manager
     */
    public function init(FilterManager $filterManager)
    {
        $this->preIn($filterManager->getServiceManager());
        $this->inBound($filterManager->getServiceManager());
        $this->postIn($filterManager->getServiceManager());

        if ($filterManager->hasEvents()) {
            $event = $filterManager->getNextEvent();
            if (is_object($event)) {
                $event->init($filterManager);
            }
        }

        $this->preOut($filterManager->getServiceManager());
        $this->outBound($filterManager->getServiceManager());
        $this->postOut($filterManager->getServiceManager());

        return $this;
    }
}
