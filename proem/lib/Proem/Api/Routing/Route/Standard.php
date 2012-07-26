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
 * Proem's standard route.
 *
 * This route should cover most use cases and is currently the only
 * route provided within the framework.
 */
class Standard extends Generic
{
    /**
     * Store default tokens.
     *
     * @var array
     */
    protected $default_tokens = [];

    /**
     * Store default filters.
     *
     * @var array
     */
    protected $default_filters = [];

    /**
     * Instantiate object & setup default filters / tokens.
     *
     * @param array $options Array of Proem\Utils\Option objects
     */
    public function __construct(array $options) {
        parent::__construct($options);

        $this->default_filters = [
            ':default'  => '[a-zA-Z0-9_\+\-%]+',
            ':gobble'   => '[a-zA-Z0-9_\+\-%\/]+',
            ':int'      => '[0-9]+',
            ':alpha'    => '[a-zA-Z]+',
            ':slug'     => '[a-zA-Z0-9_-]+'
        ];

        $this->default_tokens = [
            'module'     => $this->default_filters[':default'],
            'controller' => $this->default_filters[':default'],
            'action'     => $this->default_filters[':default'],
            'params'     => $this->default_filters[':gobble']
        ];
    }

    /**
     * Process the supplied url.
     *
     * This route takes a simplified series of patterns such as :controller and
     * replaces them with more complex regular expressions which are then used
     * within a preg_match_callback to match against the given uri.
     *
     * A default rule of /:controller/:action/:params is used if no rule is
     * provided through the $options array.
     *
     * If a 'filter' regex is set within the $options array that regex will be
     * used within the preg_match_callback. Otherwise a default regex of
     * ([a-zA-Z0-9_\+\-%]+) is used.
     *
     * If one of the 'simplified' patterns within the rule is :params, this is
     * treated specially and uses a ([a-zA-Z0-9_\+\-%\/]+) regex which will match
     * the same as the default as well as / .
     *
     * This causes the pattern to match entire sections of uri's. Allowing a
     * simple pattern like the default /:controller/:action/:params to match
     * uri's like /foo/bar/a/b/c/d/e/f/g/h and cause everything after /foo/bar
     * to be added to the Payload object as params (which are in turn transformed
     * into key => value pairs).
     *
     * @param Proem\IO\Request\Template $request
     * @return Proem\Api\Routing\Route\Template
     */
    public function process(Request $request)
    {
        if (!$this->options->rule) {
            return false;
        }

        $rule               = $this->options->rule;
        $target             = $this->options->targets;
        $custom_filters     = $this->options->filters;
        $method             = $this->options->method ?: 'GET';

        $default_tokens     = $this->default_tokens;
        $default_filters    = $this->default_filters;
        $uri                = $request->getRequestUri();
        $requestMethod      = $request->getMethod();

        $keys   = [];
        $values = [];
        $params = [];

        preg_match_all('@:([\w]+)@', $rule, $keys, PREG_PATTERN_ORDER);
        $keys = $keys[0];

        $regex = preg_replace_callback(
            '@:[\w]+@',
            function($matches) use ($custom_filters, $default_tokens, $default_filters)
            {
                $key = str_replace(':', '', $matches[0]);
                if (isset($custom_filters[$key])) {
                    if (isset($default_filters[$custom_filters[$key]])) {
                        return '(' . $default_filters[$custom_filters[$key]] . ')';
                    } else {
                        return '(' . $custom_filters[$key] . ')';
                    }
                } else if (isset($default_tokens[$key])) {
                    return '(' . $default_tokens[$key] . ')';
                } else {
                    return $default_filters[':default'];
                }
            },
            $rule
        ) . '/?';

        if (preg_match('@^' . $regex . '$@', $request->getRequestUri(), $values) && $requestMethod == $method) {
            array_shift($values);

            foreach ($keys as $index => $value) {
                if (isset($values[$index])) {
                    $params[substr($value, 1)] = urldecode($values[$index]);
                }
            }

            foreach ($target as $key => $value) {
                $params[$key] = $value;
            }

            foreach ($params as $key => $value) {
                // If the string within $value looks like a / seperated string,
                // parse it into an array and send it to setParams() instead
                // of the singular setParam.
                if ($key == 'params' && strpos($value, '/') !== false) {
                    $this->getPayload()->set('params', $this->createAssocArray(explode('/', trim($value, '/'))));
                } else {
                    $this->getPayload()->set($key, $value);
                }
            }

            $this->setMatch();
            $this->getPayload()->set('request', $request);
            $this->getPayload()->setPopulated();
        }
        return $this;
    }
}
