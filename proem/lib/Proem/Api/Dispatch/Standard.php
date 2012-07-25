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

use Proem\Dispatch\Template as Template,
    Proem\Service\Manager\Template as Manager,
    Proem\Routing\Route\Payload as Payload;

/**
 * Proem\Dispatch\Standard
 */
class Standard implements Template
{
    /**
     * Store the Assets manager
     *
     * @var Proem\Api\Service\Manager\Template
     */
    protected $assets;

    /**
     * Store an array of patterns used to searching
     * for classes within a namepspace.
     *
     * @var array $controllerMaps
     */
    protected $controllerMaps = [];

    /**
     * Store the absolute namespace to the current class
     *
     * @var string $class
     */
    protected $class;

    /**
     * Store the Router Payload.
     *
     * @var Proem\Api\Routing\Route\Payload $payload
     */
    protected $payload;

    /**
     * Store the module name
     *
     * @var string $module
     */
    protected $module;

    /**
     * Store the controller name
     *
     * @var string $controller
     */
    protected $controller;

    /**
     * Store the action name
     *
     * @var string $action
     */
    protected $action;

    /**
     * Setup the dispatcher
     *
     * @param Proem\Api\Service\Manager\Template $assets
     */
    public function __construct(Manager $assets)
    {
        $this->assets = $assets;
        $this->controllerMaps = ['Module\:module\Controller\:controller'];
    }

    /**
     * Set the payload object
     *
     * @param Proem\Api\Routing\Route\Payload $payload
     * @return Proem\Api\Dispatch\Template
     */
    public function setPayload(Payload $payload)
    {
        $this->payload = $payload;
        return $this;
    }

    /**
     * Add a new controller map onto the stack of controller
     * maps.
     *
     * This method allows us to add different directory structures
     * which the dispatcher can use to locate controllers.
     *
     * The default controller map looks like: 'Module\:module\Controller\:controller'
     *
     * You can create your own. The tokens :module and :controller will be replaced
     * with the module and controller that are made available via the payload.
     *
     * @param string $map
     * @return Proem\Api\Dispatch\Template
     */
    public function attachControllerMap($map) {
        $this->controllerMaps[] = $map;
        return $this;
    }

    /**
     * Test to see if the current payload is dispatchable.
     *
     * This method iterates through all of the controller maps
     * and checks to see if the class can be instantiated and the
     * action executed.
     *
     * This method will actually store an instantiated controller
     * within the $class property of this Dispatch object.
     *
     * @return bool
     */
    public function isDispatchable()
    {
        $this->module     = $this->payload->has('module')           ? ucfirst(strtolower($this->payload->get('module')))      : '';
        $this->controller = $this->payload->has('controller')       ? ucfirst(strtolower($this->payload->get('controller')))  : '';
        $this->action     = $this->payload->has('action')           ? $this->payload->get('action') : '';

        foreach ($this->controllerMaps as $map) {
            $this->class = str_replace(
                [':module', ':controller'],
                [$this->module, $this->controller],
                $map
            );

            if (class_exists($this->class)) {
                $this->class = new $this->class($this->assets);
                if ($this->class instanceof \Proem\Controller\Template) {
                    if (is_callable([$this->class, $this->action . 'Action'])) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    /**
     * Dispatch the current controller stored within
     * the $class property.
     *
     * Prior to dispatch this method will add any params
     * present in the payload to the *request* object stored
     * within the service manager.
     *
     * It will then execute the controllers preAction method, the action
     * method provided by the payload, then postAction.
     *
     * @return Proem\Api\Dispatch\Standard
     */
    public function dispatch()
    {
        if ($this->assets->has('request') && $this->payload->get('params') && is_array($this->payload->get('params'))) {
            $this->assets->get('request')
                ->setGetData($this->payload->get('params'));
        }
        $this->class->dispatch($this->action);
        return $this;
    }
}
