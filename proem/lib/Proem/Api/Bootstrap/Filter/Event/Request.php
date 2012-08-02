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
    Proem\IO\Request\Http\Standard as HTTPRequest,
    Proem\Service\Asset\Standard as Asset,
    Proem\Filter\Event\Generic as Event;

/**
 * The default "Request" filter event.
 */
class Request extends Event
{
    /**
     * Called prior to inBound.
     *
     * A listener responding with an object that implements the
     * Proem\Api\IO\Request\Template interface will result in that object
     * being placed within the service manager under the *request* index.
     *
     * @param Proem\Api\Service\Manager\Template $assets
     * @triggers Proem\Api\Bootstrap\Signal\Event\Bootstrap proem.pre.in.request
     */
    public function preIn(Manager $assets)
    {
        if ($assets->provides('events', '\Proem\Signal\Manager\Template')) {
            $assets->get('events')->trigger([
                'name'      => 'proem.pre.in.request',
                'params'    => [],
                'target'    => $this,
                'method'    => __FUNCTION__,
                'event'     => (new Bootstrap())->setServiceManager($assets),
                'callback'  => function($responseAsset) use ($assets) {
                    if ($responseAsset->provides('Proem\IO\Request\Template')) {
                        $assets->set('request', $responseAsset);
                    }
                },
            ]);
        }
    }

    /**
     * Method to be called on the way into the filter.
     *
     * If not already provided this method will add a default
     * Proem\Api\IO\Request\Template implementation to the service manager
     * under the index of *request*.
     *
     * @param Proem\Api\Service\Manager\Template $assets
     */
    public function inBound(Manager $assets)
    {
        if (!$assets->provides('Proem\IO\Request\Template')) {
            $asset = new Asset;
            $assets->set(
                'request',
                $asset->set('Proem\IO\Request\Template', $asset->single(function() {
                    return new HTTPRequest;
                }))
            );
        }
    }

    /**
     * Called after outBound.
     *
     * @param Proem\Api\Service\Manager\Template $assets
     * @triggers Proem\Api\Bootstrap\Signal\Event\Bootstrap proem.post.in.request
     */
    public function postIn(Manager $assets)
    {
        if ($assets->provides('events', '\Proem\Signal\Manager\Template')) {
            $assets->get('events')->trigger([
                'name'      => 'proem.post.in.request',
                'params'    => [],
                'target'    => $this,
                'method'    => __FUNCTION__,
                'event'     => (new Bootstrap())->setServiceManager($assets),
                'callback'  => function($responseAsset) {},
            ]);
        }
    }

    /**
     * Called prior to outBound.
     *
     * @param Proem\Api\Service\Manager\Template $assets
     * @triggers Proem\Api\Bootstrap\Signal\Event\Bootstrap proem.pre.out.request
     */
    public function preOut(Manager $assets)
    {
        if ($assets->provides('events', '\Proem\Signal\Manager\Template')) {
            $assets->get('events')->trigger([
                'name'      => 'proem.pre.out.request',
                'params'    => [],
                'target'    => $this,
                'method'    => __FUNCTION__,
                'event'     => (new Bootstrap())->setServiceManager($assets),
                'callback'  => function($responseAsset) {},
            ]);
        }
    }

    /**
     * Method to be called on the way out of the filter.
     *
     * @param Proem\Api\Service\Manager\Template $assets
     */
    public function outBound(Manager $assets)
    {

    }

    /**
     * Called after outBound.
     *
     * @param Proem\Api\Service\Manager\Template $assets
     * @triggers Proem\Api\Bootstrap\Signal\Event\Bootstrap proem.post.out.request
     */
    public function postOut(Manager $assets)
    {
        if ($assets->provides('events', '\Proem\Signal\Manager\Template')) {
            $assets->get('events')->trigger([
                'name'      => 'proem.post.out.request',
                'params'    => [],
                'target'    => $this,
                'method'    => __FUNCTION__,
                'event'     => (new Bootstrap())->setServiceManager($assets),
                'callback'  => function($responseAsset) {},
            ]);
        }
    }
}
