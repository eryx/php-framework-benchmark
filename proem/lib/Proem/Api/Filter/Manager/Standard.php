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
 * @namespace Proem\Api\Filter\Manager
 */
namespace Proem\Api\Filter\Manager;

use Proem\Filter\Event\Template as Event,
    Proem\Util\Storage\Queue,
    Proem\Service\Manager\Template as ServiceManager,
    Proem\Filter\Manager\Template;

/**
 * The standard filter manager.
 */
class Standard implements Template
{
    /**
     * Constants used for priorities
     */
    const RESPONSE_EVENT_PRIORITY    = 300;
    const REQUEST_EVENT_PRIORITY     = 200;
    const ROUTE_EVENT_PRIORITY       = 100;
    const DISPATCH_EVENT_PRIORITY    = 0;

    /**
     * Store the priority queue.
     *
     * @var Proem\Api\Util\Storage\Queue $queue
     */
    protected $queue;

    /**
     * Store the service manager.
     *
     * @var Proem\Api\Service\Manager
     */
    protected $serviceManager;

    /**
     * Instantiate the Filter Manager.
     *
     * This sets up the queues and service manager.
     *
     * @param Proem\Api\Service\Manager\Template
     */
    public function __construct()
    {
        $this->queue = new Queue;
    }

    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager   = $serviceManager;
        return $this;
    }

    /**
     * Insert an event into the queue
     *
     * @param Proem\Api\Filter\Event\Template $event
     * @param int $priority
     * @return Proem\Api\Filter\Manager\Template
     */
    public function attachEvent(Event $event, $priority = self::RESPONSE_EVENT_PRIORITY)
    {
        $this->queue->insert($event, $priority);
        return $this;
    }

    /**
     * Rewind the queue to the start and return the first event
     *
     * @return Proem\Api\Filter\Event\Template
     */
    public function getInitialEvent()
    {
        return $this->queue->current();
    }

    /**
     * Retrieve the next event in the filter
     *
     * @return Proem\Api\Filter\Event\Template
     */
    public function getNextEvent()
    {
        $this->queue->next();
        return $this->queue->current();
    }

    /**
     * Retrieve the Service Manager
     *
     * @return Proem\Api\Service\Manager\Template
     */
    public function getServiceManager()
    {
        return $this->serviceManager;
    }

    /**
     * Check to see if there are more events left in the filter.
     *
     * @return bool
     */
    public function hasEvents()
    {
        return $this->queue->valid();
    }

    /**
     * Get the first event in the filter and execute it's init() method
     *
     * @return Proem\Api\Filter\Event\Template
     */
    public function init()
    {
        return $this->getInitialEvent()->init($this);
    }

}
