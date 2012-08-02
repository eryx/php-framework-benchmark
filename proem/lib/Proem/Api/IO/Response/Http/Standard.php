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
 * @namespace Proem\Api\IO\Response\Http
 */
namespace Proem\Api\IO\Response\Http;

use Proem\IO\Response\Template,
    Proem\Util\Storage\KeyValStore;

/**
 * A standard http response implementation.
 */
class Standard implements Template
{
    /**
     * Store the HTTP Version
     *
     * @var string
     */
    protected $httpVersion = '1.1';

    /**
     * Store the HTTP Status code
     *
     * @var int
     */
    protected $httpStatus = 200;

    /**
     * Store response body
     *
     * @var string
     */
    protected $body = '';

    /**
     * Store headers
     *
     * @var Proem\Api\Util\Storage\KeyValStore
     */
    protected $headers;

    /**
     * Store response body length
     *
     * @var int
     */
    protected $length;

    /**
     * Hash of status code -> message
     *
     * @var array
     */
    protected $httpStatusCodes = [
        100 => 'Continue',
        101 => 'Switching Protocols',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        307 => 'Temporary Redirect',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
    ];

    /**
     * Instantiate the http response.
     */
    public function __construct()
    {
        $this->headers = new KeyValStore;
        $this->headers->set('X-Powered-By','Proem Framework ' . \Proem\Proem::VERSION);
    }

    /**
     * Silence the X-Powered-By header produced by Proem.
     *
     * This header produces X-Powered-By: Proem Framework X.X.X
     * and may pose a security concern. It's just here as an easter
     * egg more than anything. Removing it from this Request may
     * not remove it all together as PHP itself can produce this
     * same heeader.
     *
     * @return Proem\Api\IO\Response\Template;
     */
    public function silence()
    {
        $this->headers->remove('X-Powered-By');
        return $this;
    }

    /**
     * Set the HTTP version.
     *
     * @param float $version
     * @return Proem\Api\IO\Response\Template;
     */
    public function setHttpVersion($version)
    {
        if (in_array($version, [1.0,1.1])) {
            $this->httpVersion = $version;
        }
        return $this;
    }

    /**
     * Retrieve the HTTP version.
     */
    public function getHttpVersion()
    {
        return $this->httpVersion;
    }

    /**
     * Set the HTTP status.
     *
     * @param int $status
     * @return Proem\Api\IO\Response\Template;
     */
    public function setHttpStatus($status)
    {
        if (isset($this->httpStatusCodes[$status])) {
            $this->httpStatus = $status;
        } else {
            $codes = array_flip($this->httpStatusCodes);
            if (isset($status, $codes)) {
                $this->httpStatus = $codes[$status];
            }
        }
        return $this;
    }

    /**
     * Retrieve the HTTP status.
     *
     * @param bool $asMessage Retrieve status as message instead of code
     * @return int|string
     */
    public function getHttpStatus($asMessage = false)
    {
        if ($asMessage) {
            return $this->httpStatusCodes[$this->httpStatus];
        }
        return $this->httpStatus;
    }

    /**
     * Set a HTTP header by index.
     *
     * @param string $index
     * @param string $value
     * @return Proem\Api\IO\Response\Template;
     */
    public function setHeader($index, $value)
    {
        $this->headers->set($index, $value);
        return $this;
    }

    /**
     * Retrieve a HTTP header by index.
     *
     * @param string $index
     * @return string
     */
    public function getHeader($index)
    {
        if ($this->headers->has($index)) {
            return $this->headers->get($index);
        }
    }

    /**
     * Retrieve HTTP headers.
     *
     * @return Proem\Api\Util\Storage\KeyValStore
     */
    public function getHeaders()
    {
        return $this->headers;
    }

    /**
     * Append to the HTTP body.
     *
     * As data is appended to the body the $length
     * property is incremented accordingly.
     *
     * @param string $string
     * @return Proem\Api\IO\Response\Template;
     */
    public function appendToBody($string)
    {
        $string = (string) $string;
        $this->length += strlen($string);
        $this->body .= $string;
        $this->headers->set('Content-Length', $this->length);
        return $this;
    }

    /**
     * Retrieve the HTTP body as string.
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Send the HTTP headers to the client.
     */
    public function sendHeaders()
    {
        if (headers_sent()) {
            return;
        }

        if (in_array($this->httpStatus, [204, 304])) {
            $this->headers->remove('Content-Type');
        }

        header(sprintf('HTTP/%s %s %s', $this->httpVersion, $this->getHttpStatus(), $this->getHttpStatus(true)));

        foreach ($this->headers->all() as $index => $value) {
            header(sprintf('%s: %s', $index, $value));
        }

        header('Connection: close');

        flush();
    }

    /**
     * Send the response to the client.
     *
     * This method will first send any headers and then the request body.
     */
    public function send()
    {
        $this->sendHeaders();

        if (( $this->httpStatus < 100 || $this->httpStatus >= 200 ) && $this->httpStatus != 204 && $this->httpStatus != 304) {
            echo $this->body;
        }
    }

}
