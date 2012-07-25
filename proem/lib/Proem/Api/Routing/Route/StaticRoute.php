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
 * @namespace Proem\Api\Routing\Route
 */
namespace Proem\Api\Routing\Route;

use Proem\Routing\Route\Template,
    Proem\Routing\Route\Generic,
    Proem\IO\Request\Template as Request;

/**
 * A simple static route.
 */
class StaticRoute extends Generic
{
    public function process(Request $request)
    {
        if (!$this->options->rule) {
            return false;
        }

        if (!isset($this->options->targets['module']) || !isset($this->options->targets['controller']) || !isset($this->options->targets['action'])) {
            return false;
        }

        if ($request->getRequestUri() == $this->options->rule && $request->getMethod()  == ($this->options->method ?: 'GET')) {
            $this->getPayload()->set('module', $this->options->targets['module']);
            $this->getPayload()->set('controller', $this->options->targets['controller']);
            $this->getPayload()->set('action', $this->options->targets['action']);

            $this->setMatch();
            $this->getPayload()->set('request', $request);
            $this->getPayload()->setPopulated();
        }

        return $this;
    }
}
