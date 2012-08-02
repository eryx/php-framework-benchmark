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
 * @namespace Proem\Api\Signal\Manager
 */
namespace Proem\Api\Signal\Manager;

use Proem\Util\Storage\Queue,
    Proem\Util\Process\Callback,
    Proem\Util\Opt\Options,
    Proem\Util\Opt\Option,
    Proem\Signal\Event\Standard as Event,
    Proem\Signal\Manager\Template;

/**
 * Standard event manager implementation.
 *
 * Stores event listeners and provides the functionality
 * required to trigger an event.
 */
class Standard implements Template
{
    /**
     * @use Proem\Api\Util\Opt\Options
     */
    use Options;

    /**
     * Wildcard used when listening for all events
     */
    const WILDCARD = '*';

    /**
     * Stores listeners in a hash of priority queues.
     *
     * @var array $queues
     */
    protected $queues = [];

    /**
     * Store listener callbacks.
     *
     * @var array callbacks
     */
    protected $callbacks = [];

    /**
     * Remove event listeners from a particular index.
     *
     * Be aware that removeing listeners from the wildcard '*' will not literally
     * remove them from *all* events. If they have been attached to a specifically
     * named event that will need to be removed seperately.
     *
     * @param string $name
     * @return Proem\Api\Signal\Manager\Template
     */
    public function remove($name)
    {
        if (isset($this->queues[$name])) {
            unset($this->queues[$name]);
        }
        return $this;
    }

    /**
     * Register a listener attached to a particular named event.
     *
     * All listeners have there callbacks firstly stored within an associative array
     * using a unique md5 hash as an index and the callback as it's value.
     *
     * All event names are then stored within an associative array of splpriorityqueues. The
     * index of these arrays is the name of the event while the value inserted into the queue
     * is the above metnioned unique md5 hash.
     *
     * This allows a listener to attach itself to be triggered against multiple events
     * without having multiple copies of the callback being stored.
     *
     * Default priority is 0, the higher the number of the priority the earlier the
     * listener will respond, negative priorities are allowed.
     *
     * The name option can optionally take the form of an array of events for the listener
     * to attach itself with. A wildcard '*' is also provided and will attach the
     * listener to be triggered against all events.
     *
     * Be aware that attaching a listener to the same event multiple times will trigger
     * that listener multiple times. This includes using the wildcard.
     *
     * <code>
     *   $ops = $this->setOptions([
     *       'name'      => (new Option())->required(),                     // The name of the event to listen to
     *       'callback'  => (new Option())->required()->type('callable'),   // The Callable that will be executed when the event occurs
     *       'priority'  => 0                                               // The priority at which this listner will be executed
     *   ], $options);
     * </code>
     *
     * @param array $options An array of Proem\Util\Opt\Options objects
     * @return Proem\Api\Signal\Manager\Template
     */
    public function attach(array $options)
    {
        $ops = $this->setOptions([
            'name'      => (new Option())->required(),
            'callback'  => (new Option())->required()->type('callable'),
            'priority'  => 0
        ], $options);

        $key = md5(microtime());
        $this->callbacks[$key] = $ops->callback;

        if (is_array($ops->name)) {
            foreach ($ops->name as $event) {
                if (isset($this->queues[$event])) {
                    if ($event == self::WILDCARD) {
                        $this->queues[self::WILDCARD][] = [$key, $ops->priority];
                    } else {
                        $this->queues[$event]->insert($key, $ops->priority);
                    }
                } else {
                    if ($event == self::WILDCARD) {
                        $this->queues[self::WILDCARD][] = [$key, $ops->priority];
                    } else {
                        $this->queues[$event] = new Queue;
                        $this->queues[$event]->insert($key, $ops->priority);
                    }
                }
            }
        } else {
            if (isset($this->queues[$ops->name])) {
                if ($ops->name == self::WILDCARD) {
                    $this->queues[self::WILDCARD][] = [$key, $ops->priority];
                } else {
                    $this->queues[$ops->name]->insert($key, $ops->priority);
                }
            } else {
                if ($ops->name == self::WILDCARD) {
                    $this->queues[self::WILDCARD][] = [$key, $ops->priority];
                } else {
                    $this->queues[$ops->name] = new Queue;
                    $this->queues[$ops->name]->insert($key, $ops->priority);
                }
            }
        }

        return $this;
    }

    /**
     * Trigger the execution of all event listeners attached to a named event.
     *
     * <code>
     *   $ops = $this->setOptions([
     *       'name'      => (new Option())->required(),
     *       'params'    => (new Option())->type('array'),
     *       'callback'  => (new Option())->type('callable'),
     *       'event'     => (new Option(new Event))->object('\Proem\Signal\Event\Template')
     *   ], $options);
     * </code>
     *
     * @param array $options An array of Proem\Util\Opt\Options objects
     * @return Proem\Api\Signal\Manager\Template
     */
    public function trigger(Event $event, Callable $callback = null)
    {
        $name = $event->getName();
        if (isset($name) || isset($this->queues[self::WILDCARD])) {
            if (isset($this->queues[self::WILDCARD])) {
                foreach ($this->queues[self::WILDCARD] as $listener) {
                    if (isset($this->queues[$name])) {
                        $this->queues[$name]->insert($listener[0], $listener[1]);
                    } else {
                        $this->queues[$name] = new Queue;
                        $this->queues[$name]->insert($listener[0], $listener[1]);
                    }
                }
            }

            if (isset($this->queues[$name])) {
                foreach ($this->queues[$name] as $key) {
                    if ($return = (new Callback($this->callbacks[$key], $event))->call()) {
                        if ($callback !== null) {
                            (new Callback($callback, $return))->call();
                        }
                    }
                }
            }
        }

        return $this;
    }

}
