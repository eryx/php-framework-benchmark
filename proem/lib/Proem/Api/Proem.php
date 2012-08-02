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
 * @namespace Proem\Api
 */
namespace Proem\Api;

use Proem\Service\Manager\Standard as ServiceManager,
    Proem\Signal\Manager\Standard as SignalManager,
    Proem\Filter\Manager\Standard as FilterManager,
    Proem\Service\Asset\Standard as Asset,
    Proem\Bootstrap\Filter\Event\Dispatch,
    Proem\Bootstrap\Filter\Event\Response,
    Proem\Bootstrap\Filter\Event\Request,
    Proem\Bootstrap\Filter\Event\Route,
    Proem\Bootstrap\Signal\Event\Bootstrap,
    Proem\Ext\Template as Extension,
    Proem\Ext\Module\Generic as Module,
    Proem\Ext\Plugin\Generic as Plugin;

/**
 * The Proem boostrap wrapper
 *
 * Responsible for aiding in the bootstrap process
 */
class Proem
{
    /**
     * Store the framework version
     */
    const VERSION = '0.5.1';

    /**
     * Store events
     *
     * @var Proem\Api\Signal\Manager\Template
     */
    protected $events;

    /**
     * Store the filter manager
     *
     * @var Proem\Api\Filter\Manager\Template
     */
    protected $filterManager;

    /**
     * Store the service manager
     *
     * @var Proem\Api\Service\Manager\Template
     */
    protected $serviceManager;

    /**
     * Register an extension
     *
     * An extension is just a lower level interface that modules and plugins implement
     *
     * @param Proem\Api\Ext\Template $extension
     * @param string $event The event that will trigger this extensions init() method
     * @param int $priority The priority the Event Listener is attached at
     * @return Proem\Api\Proem
     */
    protected function attachExtension(Extension $extension, $event = 'proem.init', $priority = 0)
    {
        $this->attachEventListener([
            'name'      => $event,
            'priority'  => $priority,
            'callback'  => function($e) use ($extension) {
                $extension->init($e->getServiceManager(), $e->getEnvironment());
            }
        ]);
        return $this;
    }

    /**
     * Setup bootstraping
     */
    public function __construct()
    {
        $this->events = new Asset;
        $this->events->set('Proem\Signal\Manager\Template', $this->events->single(function($asset) {
            return new SignalManager;
        }));

        $this->serviceManager = new ServiceManager;
    }

    /**
     * Attach a listener to the signal event manager
     *
     * @param array $listener
     * @return Proem\Api\Proem
     */
    public function attachEventListener(array $listener)
    {
        $this->events->get()->attach($listener);
        return $this;
    }

    /**
     * Attach a series of events to the signal event manager
     *
     * @param array $listeners
     * @return Proem\Api\Proem
     */
    public function attachEventListeners(array $listeners)
    {
        foreach ($listeners as $listener) {
            $this->attachEventListener($listener);
        }
        return $this;
    }

    /**
     * Register a plugin
     *
     * @param Proem\Api\Ext\Plugin\Generic
     * @param string $event The event that will trigger this extensions init() method
     * @param int $priority The priority the Event Listener is attached at
     * @return Proem\Api\Proem
     */
    public function attachPlugin(Plugin $plugin, $event = 'proem.init', $priority = 0)
    {
        return $this->attachExtension($plugin);
    }

    /**
     * Register a module
     *
     * @param Proem\Api\Proem\Ext\Module\Generic
     * @param string $event The event that will trigger this extensions init() method
     * @param int $priority The priority the Event Listener is attached at
     * @return Proem\Api\Proem
     */
    public function attachModule(Module $module, $event = 'proem.init', $priority = 0)
    {
        return $this->attachExtension($module, $event, $priority);
    }

    /**
     * Initialise the boostrap process
     *
     * This simple call will start the filter chain in motion
     *
     * @param string|null $environment
     */
    public function init($environment = null)
    {
        $this->serviceManager->set('events', $this->events);

        $this->events->get()->trigger(
            (new Bootstrap('proem.init'))->setServiceManager($this->serviceManager)->setEnvironment($environment),
            function($response) {
                if ($response instanceof Proem\Filter\Manager\Template) {
                    $this->filterManager = $response;
                }
            }
        );

        if ($this->filterManager === null) {
            $this->filterManager = new FilterManager;
        }

        $this->filterManager
            ->setServiceManager($this->serviceManager)
            ->attachEvent(new Response, FilterManager::RESPONSE_EVENT_PRIORITY)
            ->attachEvent(new Request, FilterManager::REQUEST_EVENT_PRIORITY)
            ->attachEvent(new Route, FilterManager::ROUTE_EVENT_PRIORITY)
            ->attachEvent(new Dispatch, FilterManager::DISPATCH_EVENT_PRIORITY)
            ->init();
    }
}
