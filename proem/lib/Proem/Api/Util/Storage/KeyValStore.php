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
 * A simple, generic key => value storage mechanism
 * implementing the \Iterator interface.
 */
class KeyValStore implements \Iterator
{
    /**
     * Store the data.
     *
     * @var array $data
     */
    protected $data = [];

    /**
     * Instantiate
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    /**
     * Retrieve all data.
     *
     * @return array
     */
    public function all()
    {
        return $this->data;
    }

    /**
     * Retrieve a value by index.
     *
     * Optionaly returns a default value.
     *
     * @param string $index
     * @param mixed $default
     * @return mixed
     */
    public function get($index, $default = null)
    {
        return $this->has($index) ? $this->data[$index] : $default;
    }

    /**
     * Magic proxy to get()
     *
     * @param string $index
     * @return mixed
     */
    public function __get($index)
    {
        return $this->get($index);
    }

    /**
     * Set value by index.
     *
     * @param string $index
     * @param mixed $value
     * @return Proem\Api\Util\Storage\KeyValStore
     */
    public function set($index, $value)
    {
        $this->data[$index] = $value;
        return $this;
    }

    /**
     * Magic proxy to set()
     *
     * @param string $index
     * @param mixed $value
     * @return mixed
     */
    public function __set($index, $value)
    {
        return $this->set($index, $value);
    }

    /**
     * Remove an item by index
     *
     * @param string $index
     * @return Proem\Api\Util\Storage\KeyValStore
     */
    public function remove($index)
    {
        if (isset($this->data[$index])) {
            unset($this->data[$index]);
        }
        return $this;
    }

    /**
     * Does storage have index?
     *
     * @param string $index
     * @return mixed
     */
    public function has($index)
    {
        return isset($this->data[$index]);
    }

    /**
     * Reset internal pointer
     *
     * @return mixed
     */
    public function rewind() {
        return reset($this->data);
    }

    /**
     * Return the current element
     *
     * @return mixed
     */
    public function current() {
        return current($this->data);
    }

    /**
     * Fetch current key
     *
     * @return mixed
     */
    public function key() {
        return key($this->data);
    }

    /**
     * Advance the internal pointer and return its data
     *
     * @return mixed
     */
    public function next() {
        return next($this->data);
    }

    /**
     * Does the current internal pointer position point to an existing element?
     *
     * @return mixed
     */
    public function valid() {
        return $this->current();
    }

}
