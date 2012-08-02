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
 * @namespace Proem\Api\Bootstrap\Filter\Event
 */
namespace Proem\Api\Bootstrap\Filter\Event;

use Proem\Service\Manager\Template as Manager,
    Proem\Bootstrap\Signal\Event\Bootstrap,
    Proem\Service\Asset\Standard as Asset,
    Proem\IO\Response\Http\Standard as HTTPResponse,
    Proem\Filter\Event\Generic as Event;

/**
 * The default "Response" filter event.
 */
class Response extends Event
{
    /**
     * Called prior to inBound.
     *
     * A listener responding with an object implementing the
     * Proem\Api\IO\Response\Template interface, will result in that
     * object being placed within the main service manager under
     * the index of *response*.
     *
     * @param Proem\Api\Service\Manager\Template $assets
     * @triggers Proem\Api\Bootstrap\Signal\Event\Bootstrap proem.pre.in.response
     */
    public function preIn(Manager $assets)
    {
        if ($assets->provides('events', 'Proem\Signal\Manager\Template')) {
            $assets->get('events')->trigger(
                (new Bootstrap('proem.pre.in.response'))->setServiceManager($assets),
                function($response) use ($assets) {
                    if ($response->provides('Proem\IO\Response\Template')) {
                        $assets->set('response', $response);
                    }
                }
            );
        }
    }

    /**
     * Method to be called on the way into the filter.
     *
     * If not already provided, this method will add a default
     * Proem\Api\IO\Response\Template implementation to the main service
     * manager under the index of *response*.
     *
     * @param Proem\Api\Service\Manager\Template $assets
     */
    public function inBound(Manager $assets)
    {
        if (!$assets->provides('Proem\IO\Response\Template')) {
            $asset = new Asset;
            $assets->set(
                'response',
                $asset->set('Proem\IO\Response\Template', $asset->single(function() {
                    return new HTTPResponse;
                }))
            );
        }
    }

    /**
     * Called after outBound.
     *
     * @param Proem\Api\Service\Manager\Template $assets
     * @triggers Proem\Api\Bootstrap\Signal\Event\Bootstrap proem.post.in.response
     */
    public function postIn(Manager $assets)
    {
        if ($assets->provides('events', 'Proem\Signal\Manager\Template')) {
            $assets->get('events')->trigger((new Bootstrap('proem.post.in.response'))->setServiceManager($assets));
        }
    }

    /**
     * Called prior to outBound.
     *
     * @param Proem\Api\Service\Manager\Template $assets
     * @triggers Proem\Api\Bootstrap\Signal\Event\Bootstrap proem.pre.out.response
     */
    public function preOut(Manager $assets)
    {
        if ($assets->provides('events', 'Proem\Signal\Manager\Template')) {
            $assets->get('events')->trigger((new Bootstrap('proem.pre.out.response'))->setServiceManager($assets));
        }
    }

    /**
     * Method to be called on the way out of the filter.
     *
     * This method is responseible for sending the response
     * to the client.
     *
     * @see Proem\Api\IO\Response\Template
     * @param Proem\Api\Service\Manager\Template $assets
     * @triggers Proem\Api\Bootstrap\Signal\Event\Bootstrap proem.pre.in.response
     */
    public function outBound(Manager $assets)
    {
        if ($assets->provides('Proem\IO\Response\Template')) {
            $assets->get('response')->send();
        }
    }

    /**
     * Called after outBound.
     *
     * @param Proem\Api\Service\Manager\Template $assets
     * @triggers Proem\Api\Bootstrap\Signal\Event\Bootstrap proem.post.out.response
     */
    public function postOut(Manager $assets)
    {
        if ($assets->provides('events', 'Proem\Signal\Manager\Template')) {
            $assets->get('events')->trigger((new Bootstrap('proem.post.out.response'))->setServiceManager($assets));
        }
    }
}
