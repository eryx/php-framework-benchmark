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
    Proem\Routing\Route\Payload as Payload;

/**
 * Interface all dispatcher must implement.
 */
interface Template
{
    /**
     * Setup the dispatcher
     *
     * @param Proem\Api\Service\Manager\Template $assets
     */
    public function __construct(Manager $assets);

    /**
     * Set the payload object
     *
     * @param Proem\Api\Routing\Route\Payload $payload
     * @return Proem\Api\Dispatch\Template
     */
    public function setPayload(Payload $payload);

    /**
     * Test to see if the current payload is dispatchable.
     *
     * @return bool
     */
    public function isDispatchable();

    /**
     * Dispatch the current controller/action
     *
     * @return Proem\Api\Dispatch\Template
     */
    public function dispatch();
}
