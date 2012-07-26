<?php

/**
 * The MIT License
 *
 * Copyright (c) 2010 - 2012 Tony R Quilkey <trq@proemframework.org>
 * Copyright (c) 2004 - 2011 Fabien Potencier
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation file (the "Software"), to deal
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
 * The bulk of the following code was derived from code within the Symfony2 Framework
 * and is also subject to the same MIT License as Proem.
 */

/**
 * @namespace Proem\Api\IO\Request\Http
 */
namespace Proem\Api\IO\Request\Http;

use Proem\IO\Request\Template,
    Proem\Util\Storage\KeyValStore;

/**
 * A fake http request implementation.
 *
 * This call is useful for faking http requests to the framework.
 */
class Fake extends Standard
{
    /**
     * Instantiate a request using fake data.
     *
     * @param string $uri
     * @param string $method
     * @param string $body
     * @param array $param
     * @param array $cookie
     * @param array $file
     * @param array $meta
     */
    public function __construct($uri, $method = 'GET', $body = '', $param = [], $cookie = [], $file = [], $meta = [])
    {
        $defaults = [
            'SERVER_NAME'          => 'localhost',
            'SERVER_PORT'          => 80,
            'HTTP_HOST'            => 'localhost',
            'HTTP_USER_AGENT'      => 'Proem Framework',
            'HTTP_ACCEPT'          => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'HTTP_ACCEPT_LANGUAGE' => 'en-us,en;q=0.5',
            'HTTP_ACCEPT_CHARSET'  => 'ISO-8859-1,utf-8;q=0.7,*;q=0.7',
            'REMOTE_ADDR'          => '127.0.0.1',
            'SCRIPT_NAME'          => '',
            'SCRIPT_FILENAME'      => '',
            'SERVER_PROTOCOL'      => 'HTTP/1.1',
            'REQUEST_TIME'         => time(),
        ];

        $components = parse_url($uri);

        if (isset($components['host'])) {
            $defaults['SERVER_NAME']    = $components['host'];
            $defaults['HTTP_HOST']      = $components['host'];
        }

        if (isset($components['scheme'])) {
            if ('https' === $components['scheme']) {
                $defaults['HTTPS']          = 'on';
                $defaults['SERVER_PORT']    = 443;
            }
        }

        if (isset($components['port'])) {
            $defaults['SERVER_PORT']    = $components['port'];
            $defaults['HTTP_HOST']      = $defaults['HTTP_HOST'] . ':' . $components['port'];
        }

        if (isset($components['user'])) {
            $defaults['PHP_AUTH_USER'] = $components['user'];
        }

        if (isset($components['pass'])) {
            $defaults['PHP_AUTH_PW'] = $components['pass'];
        }

        if (!isset($components['path'])) {
            $components['path'] = '';
        }

        switch (strtoupper($method)) {
            case 'POST':
            case 'PUT':
            case 'DELETE':
                $defaults['CONTENT_TYPE'] = 'application/x-www-form-urlencoded';
            case 'PATCH':
                $post   = $param;
                $get    = [];
                break;
            default:
                $post   = [];
                $get    = $param;
                if (false !== $pos = strpos($uri, '?')) {
                    $qs = substr($uri, $pos + 1);
                    parse_str($qs, $param);
                    $get = array_merge($param, $get);
                }
                break;
        }

        $getString = isset($components['query']) ? html_entity_decode($components['query']) : '';
        parse_str($getString, $qs);
        if (is_array($qs)) {
            $get = array_replace($qs, $get);
        }

        $uri = $components['path'] . ($getString ? '?' . $getString : '');

        $meta = array_replace($defaults, $meta, [
            'REQUEST_METHOD'       => strtoupper($method),
            'PATH_INFO'            => '',
            'REQUEST_URI'          => $uri,
            'QUERY_STRING'         => $getString,
        ]);

        $this->body = $body;

        $this->data = [
            'param'     => new KeyValStore($param),
            'get'       => new KeyValStore($get),
            'post'      => new KeyValStore($post),
            'cookie'    => new KeyValStore($cookie),
            'file'      => new KeyValStore($file),
            'meta'      => new KeyValStore($meta),
            'header'    => new KeyValStore($this->formHeaders($meta))
        ];
    }

}
