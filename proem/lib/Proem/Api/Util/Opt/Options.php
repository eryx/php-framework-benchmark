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
 * @namespace Proem\Api\Util\Opt
 */
namespace Proem\Api\Util\Opt;

use Proem\Util\Opt\Payload as Payload;

/**
 * The options trait.
 */
trait Options
{
    /**
     * Merge default options with user supplied arguments applying validation in the process.
     *
     * @param array $defaults Default Options
     * @param array $options User supplied Options
     * @return Proem\Api\Util\Opt\Payload End result of merging default options with validated user options
     */
    public function setOptions($defaults, $options)
    {
        $payload = new Payload;

        foreach ($options as $key => $value) {
            if (isset($defaults[$key]) && ($defaults[$key] instanceof Option)) {
                $defaults[$key]->setValue($value);
            } else {
                $defaults[$key] = $value;
            }
        }

        foreach ($defaults as $key => $value) {
            if ($value instanceof Option) {
                try {
                    if ($value->isRequired() || $value->getValue() !== null) {
                        if ($value->validate($options)) {
                            $payload->set($key, $value->getValue());
                        }
                    }
                } catch (\InvalidArgumentException $e) {
                    throw new \InvalidArgumentException($key . $e->getMessage());
                } catch (\RuntimeException $e) {
                    throw new \RuntimeException($e->getMessage());
                }
            } else {
                $payload->set($key, $value);
            }
        }
        return $payload;
    }

}
