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
 * @namespace Proem\Api\Routing\Router
 */
namespace Proem\Api\Routing\Router;

use Proem\Routing\Route\Template as Route;

/**
 * Interface all routers must implement.
 */
interface Template
{
    /**
     * Register a route with the router.
     *
     * @param string $name
     * @param Proem\Api\Routing\Route\Template $route
     */
    public function attach($name, Route $route);

    /**
     * Recurse through the Routes until a match is found.
     *
     * When called multiple times (in a loop for instance)
     * this method should return a new matching route until
     * all routes have been processed.
     *
     * Once exhausted this function should return false and the
     * internal pointer should be reset so the router can be used
     * again.
     */
    public function route();

}
