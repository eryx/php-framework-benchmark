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
 * @namespace Proem\Api\Dispatch
 */
namespace Proem\Api\Dispatch;

use Proem\Service\Manager\Template as Manager,
    Proem\Routing\Signal\Event\RouteMatch,
    Proem\Routing\Signal\Event\RouteDispatch,
    Proem\Routing\Signal\Event\RouteExhausted;

/**
 * The dispatch stage.
 *
 * This object sets up a staging area where the router and dispatcher
 * can put on there show
 */
class Stage
{
    /**
     * Store the Services Manager
     *
     * @var Proem\Api\Services\Manager\Template
     */
    protected $assets;

    /**
     * Store a flag
     */
    protected $dispatchable = false;

    /**
     * Setup the stage and start the dispatch process
     *
     * Within this single construct we attach listeners
     * for both the route.macth & route.exhausted events
     *
     * We then start processing the routes. Once the dispatchable
     * flag is true the route is dispatched and execution moves
     * into userland *controller* code
     *
     * @param Proem\Api\Service\Manager\Template $assets
     */
    public function __construct(Manager $assets)
    {
        $this->assets = $assets;

        $this->attachRouteMatchListener();
        $this->attachRouteExhaustedListener();
        $this->attachRouteDispatchListener();

        $this->processRoutes();
    }

    /**
     * Register a callback ready to listen for the route.match Event.
     */
    protected function attachRouteMatchListener()
    {
        if ($this->assets->has('events')) {
            $this->assets->get('events')->attach([
                'name'      => 'proem.route.match',
                'callback'  => [$this, 'testRoute']
            ]);
        }
    }

    /**
     * Register a callback ready to listen for the route.exhausted Event.
     */
    protected function attachRouteExhaustedListener()
    {
        if ($this->assets->has('events')) {
            $this->assets->get('events')->attach([
                'name'      => 'proem.route.exhausted',
                'callback'  => [$this, 'routesExhausted']
            ]);
        }
    }

    /**
     * Register a callback ready to listen for the route.dispatch Event.
     */
    protected function attachRouteDispatchListener()
    {
        if ($this->assets->has('events')) {
            $this->assets->get('events')->attach([
                'name'      => 'proem.route.dispatch',
                'callback'  => [$this, 'dispatch']
            ]);
        }
    }

    /**
     * Iterate through matching routes and trigger a match.route Event
     * on each iteration.
     *
     * A listener returning the bool true indicates that the payload is
     * dispatchable. This sets the dispatchable flag to true and will
     * exit this method.
     *
     * If all matching routes have been exhausted a route.exhausted event
     * is triggered.
     *
     * @triggers Proem\Api\Routing\Signal\Event\RouteMatch route.match Check to see if a route is dispatchable
     * @triggers Proem\Api\Routing\Signal\Event\RouteDispatch route.dispatch Dispatch a route
     * @triggers Proem\Api\Routing\Signal\Event\RouteExhausted route.exhausted All routes exhausted
     */
    protected function processRoutes()
    {
        if ($this->assets->has('router') && $this->assets->has('events')) {
            $assets     = $this->assets;
            $router     = $assets->get('router');
            $dispatched = false;
            while ($payload = $router->route()) {
                $assets->get('events')->trigger([
                    'name'      => 'proem.route.match',
                    'event'     => (new RouteMatch())->setPayload($payload),
                    'callback'  => function($e) use (&$dispatched, &$assets) {
                        if ($e) {
                            $dispatched = true;
                            $assets->get('events')->trigger([
                                'name' => 'proem.route.dispatch',
                                'event' => (new RouteDispatch)
                            ]);
                        }
                    }
                ]);
                if ($dispatched) {
                    break;
                }
            }

            if (!$dispatched) {
                $assets->get('events')->trigger([
                    'name' => 'proem.route.exhausted',
                    'event' => (new RouteExhausted)
                ]);
            }
        }
    }

    /**
     * Dispatch the payload.
     */
    public function dispatch($e)
    {
        if ($this->assets->has('dispatch')) {
            $this->assets->get('dispatch')->dispatch();
        }
    }

    /**
     * Listen for the route.match Event.
     *
     * Pass the RouteMatch event to the dispatcher and have it tested
     * to see if it is dispatchable. Return the result.
     *
     * @param Proem\Api\Routing\Signal\Event\RouteMatch $e
     * @return bool
     */
    public function testRoute($e)
    {
        if ($this->assets->has('dispatch')) {
            return $this->assets->get('dispatch')
                ->setPayload($e->getPayload())
                ->isDispatchable();
        }
    }

    /**
     * Listen for a route.exhuasted Event.
     *
     * If triggered, dispatch a very standard default 404
     *
     * @param Proem\Api\Routing\Signal\Event\RouteMatch $e
     */
    public function routesExhausted($e)
    {
        if ($this->assets->has('response')) {
            $this->assets->get('response')
                ->setHttpStatus(404)
                ->appendToBody('<h3>404 - Page Not Found</h3>');
        }
    }
}
