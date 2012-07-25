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
 * @namespace Proem\Api\IO\Request\Http
 */
namespace Proem\Api\IO\Request\Http;

use Proem\IO\Request\Template,
    Proem\Util\Storage\KeyValStore;

/**
 * The standard http request class.
 */
class Standard implements Template
{
    /**
     * Store all internal data.
     *
     * @var array $data
     */
    protected $data = [];

    /**
     * Store the request body.
     *
     * @var string $body
     */
    protected $body;

    /**
     * Store a hash of valid content types.
     *
     * @var array $contentTypes
     */
    protected $contentTypes = [
        'form' => ['application/x-www-form-urlencoded'],
        'html' => ['text/html', 'application/xhtml+xml'],
        'txt'  => ['text/plain'],
        'js'   => ['application/javascript', 'application/x-javascript', 'text/javascript'],
        'css'  => ['text/css'],
        'json' => ['application/json', 'application/x-json'],
        'xml'  => ['text/xml', 'application/xml', 'application/x-xml'],
        'rdf'  => ['application/rdf+xml'],
        'atom' => ['application/atom+xml'],
        'rss'  => ['application/rss+xml'],
    ];

    /**
     * Valid request methods.
     *
     * @var array $methods
     */
    protected $methods = ['GET', 'POST', 'PUT', 'DELETE'];

    /**
     * Instantiate the request using data supplied by the super globals.
     *
     * @param array $param
     */
    public function __construct(array $param = []) {
        $this->data = [
            'param'     => new KeyValStore($param),
            'get'       => new KeyValStore($_GET),
            'post'      => new KeyValStore($_POST),
            'cookie'    => new KeyValStore($_COOKIE),
            'file'      => new KeyValStore($_FILES),
            'meta'      => new KeyValStore($_SERVER),
            'header'    => new KeyValStore($this->formHeaders($_SERVER))
        ];
    }

    /**
     * Used to split the HTTP headers into there own storage.
     *
     * @param array $meta
     * @return array
     */
    protected function formHeaders($meta)
    {
        $out = [];
        foreach ($meta as $k => $v) {
            if (substr($k, 0, 5) == 'HTTP_') {
                $out[substr($k, 5)] = $v;
            } elseif (in_array($k, ['CONTENT_LENGTH', 'CONTENT_MD5', 'CONTENT_TYPE', 'X_REQUESTED_WITH'])) {
                $out[$k] = $v;
            }
        }
        return $out;
    }

    /**
     * Retrieve data by index.
     *
     * @param string $index
     * @return string
     */
    public function __get($index)
    {
        return isset($this->data[$index]) ? $this->data[$index] : null;
    }

    /**
     * Retrieve the body of the request.
     *
     * @param bool $raw return body in it's raw format
     * @return string
     */
    public function getBody($raw = true)
    {
        if (!$this->body) {
            $this->body = file_get_contents('php://input');
        }

        if (!$raw) {
            $type = $this->getContentType();
            if (in_array($type, $this->contentTypes['form'])) {
                return mb_parse_str($this->body);
            } elseif (in_array($type, $this->contentTypes['json'])) {
                return json_decode($this->body);
            } elseif (in_array($type, $this->contentTypes['xml'])) {
                return simplexml_load_string($this->body);
            }
        }
        return $this->body;
    }

    /**
     * Set the content type.
     *
     * @param string $type
     * @return Proem\Api\IO\Request\Template
     */
    public function setContentType($type)
    {
        $valid = false;

        if (isset($this->contentTypes[$type])) {
            $valid = true;
            $type = $this->contentTypes[$type][0];
        } else {
            foreach ($this->contentTypes as $valid_type) {
                if (in_array($type, $valid_type)) {
                    $valid = true;
                    continue;
                }
            }
        }

        if ($valid) {
            $this->data['header']->set('CONTENT_TYPE', $type);
        }

        return $this;
    }

    /**
     * Retrieve request content type.
     *
     * @return string
     */
    public function getContentType()
    {
        return $this->data['header']->get('CONTENT_TYPE');
    }

    /**
     * Set the request method.
     *
     * @param string $method
     * @return Proem\Api\IO\Request\Template
     */
    public function setMethod($method)
    {
        $method = strtoupper($method);
        if (in_array($method, $this->methods)) {
            $this->data['meta']->set('REQUEST_METHOD', $method);
            if ($this->getContentType() === null) {
                if (in_array($method, ['POST', 'PUT', 'DELETE'])) {
                    $this->setContentType('form');
                }
            }
        }
        return $this;
    }

    /**
     * Set GET data.
     *
     * @param array $data
     * @return Proem\Api\IO\Request\Template
     */
    public function setGetData(array $data)
    {
        $this->data['get']->set($data);
        return $this;
    }

    /**
     * Get request method.
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->data['meta']->get('REQUEST_METHOD');
    }

    /**
     * Is this a GET request?
     *
     * @return bool
     */
    public function isGet()
    {
        return $this->getMethod() === 'GET';
    }

    /**
     * Is this a POST request?
     *
     * @return bool
     */
    public function isPost()
    {
        return $this->getMethod() === 'POST';
    }

    /**
     * Is this a XmlHttpRequest request?
     *
     * @return bool
     */
    public function isAjax() {
        return $this->data['header']->get('X_REQUESTED_WITH') === 'XMLHttpRequest';
    }

    /**
     * Retrieve the request uri without any querystring parameters.
     *
     * @return string
     */
    public function getRequestUri() {
        return parse_url($this->data['meta']->get('REQUEST_URI'), PHP_URL_PATH);
    }

    /**
     * Retrieve the host name
     *
     * @return string
     */
    public function getHostName()
    {
        return $this->data['header']->get('HOST');
    }

    /**
     * Retrieve the client ip address.
     *
     * @return string
     */
    public function getClientIp()
    {
        if ($this->meta->has('HTTP_CLIENT_IP')) {
            return $this->data['meta']->get('HTTP_CLIENT_IP');
        }

        if ($this->data['meta']->has('HTTP_X_FORWARDED_FOR')) {
            $ip = explode(',', $this->data['meta']->get('HTTP_X_FORWARDED_FOR'), 2);
            return isset($ip[0]) ? $ip[0] : null;
        }

        return $this->data['meta']->get('REMOTE_ADDR');
    }

}
