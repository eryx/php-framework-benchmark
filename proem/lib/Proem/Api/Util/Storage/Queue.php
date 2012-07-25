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
 * @namespace Proem\Api\Util\Storage
 */
namespace Proem\Api\Util\Storage;

/**
 * Priority queue implementation.
 *
 * Implements the \IteratorAggregate and \Countable interfaces.
 *
 * Internally we use SplPriorityQueues to implement the priority parts.
 * Wrapping the SplPriorityQueue within a IterattorAggregate means however
 * that we can rewind and play the queue again if required. This cannot be
 * achieved with the SplPRiorityQueue alone because it is a stack and as
 * such items are lost as they are iterated over.
 */
class Queue implements \IteratorAggregate, \Countable
{
    /**
     * Store the SplPriorityQueue.
     *
     * @var \SplPriorityQueue
     */
    protected $queue;

    /**
     * Data aggregated in the priority queue.
     *
     * @var array
     */
    protected $data;

    /**
     * Store a very large number.
     *
     * @var int
     */
    protected $max = PHP_INT_MAX;

    /**
     * Retrieve the internal queue.
     *
     * @return \SplPriorityQueue
     */
    protected function getSplQueue()
    {
        if (null === $this->queue) {
            $this->queue = new \SplPriorityQueue;
        }
        return $this->queue;
    }

    /**
     * Insert an item into the queue.
     *
     * @param mixed $data
     * @param int $priority
     * @return Proem\Api\Util\Storage\Queue
     */
    public function insert($data, $priority = 0)
    {
        $this->data[] = [
            'data'     => $data,
            'priority' => $priority,
        ];

        $priority = array($priority, $this->max--);
        $this->getSplQueue()->insert($data, $priority);
        return $this;
    }

    /**
     * Retrieve current item from the queue
     *
     * @return mixed
     */
    public function current()
    {
        return $this->getSplQueue()->current();
    }

    /**
     * Return current node index
     *
     * @return mixed
     */
    public function key()
    {
        return $this->getSplQueue()->key();
    }

    /**
     * Move to the next node
     *
     * @return mixed
     */
    public function next()
    {
        return $this->getSplQueue()->next();
    }

    /**
     * Rewind iterator back to the start (no-op)
     *
     * @return mixed
     */
    public function rewind()
    {
        return $this->getSplQueue()->rewind();
    }

    /**
     * Check whether the queue contains more nodes
     *
     * @return mixed
     */
    public function valid()
    {
        return $this->getSplQueue()->valid();
    }

    /**
     * Return count of items in the queue
     *
     * @return int
     */
    public function count()
    {
        return count($this->data);
    }

    /**
     * Clone the internal priority queue and return it for iterating over.
     *
     * @return \SplPriorityQueue
     */
    public function getIterator()
    {
        $queue = $this->getSplQueue();
        return clone $queue;
    }

}
