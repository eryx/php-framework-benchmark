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
 * @namespace Proem
 */
namespace Proem;

/**
 * The Proem Autoloader
 *
 * An autoloader implementing the PSR-0 standard
 *
 * @link https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md
 */
class Autoloader
{
    /**
     * Store namespaces
     *
     * @var array $namespaces
     */
    protected $namespaces = [];

    /**
     * Store Pear prefixes
     *
     * @var array $pearPrefixes
     */
    protected $pearPrefixes = [];

    /**
     * Store a flag indicating the abilty to load the APC extension
     */
    protected $apcEnabled = false;

    /**
     * Set enable APC caching.
     */
    public function enableAPC()
    {
        $this->apcEnabled = true;
	return $this;
    }

    /**
     * Register an array of namespaces
     *
     * @param array $namespaces An array of namespaces
     * @return Proem\Api\Autoloader
     */
    public function attachNamespaces(array $namespaces)
    {
        foreach ($namespaces as $namespace => $paths) {
            $this->attachNamespace($namespace, $paths);
        }
        return $this;
    }

    /**
     * Register a namespace.
     *
     * @param string $namespace The namespace
     * @param array|string $paths The path to the namespace
     * @return Proem\Api\Autoloader
     */
    public function attachNamespace($namespace, $paths)
    {
        $this->namespaces[$namespace] = (array) $paths;
        return $this;
    }

    /**
     * Registers an array of classes using the Pear naming convention
     *
     * @param array $classes
     * @return Proem\Api\Autoloader
     */
    public function attachPearPrefixes(array $classes)
    {
        foreach ($classes as $prefix => $paths) {
            $this->attachPearPrefix($prefix, $paths);
        }
        return $this;
    }

    /**
     * Register a class using the PEAR naming convention
     *
     * @param string $prefix The prefix
     * @param array|string $paths The path
     * @return Proem\Api\Autoloader
     */
    public function attachPearPrefix($prefix, $paths)
    {
        $this->pearPrefixes[$prefix] = (array) $paths;
        return $this;
    }

    /**
     * Register the autoloader.
     *
     * @return Proem\Api\Autoloader
     */
    public function register()
    {
        spl_autoload_register([$this, 'load'], true);
        return $this;
    }

    /**
     * Unregister the autoloader.
     *
     * @return Proem\Api\Autoloader
     */
    public function unregister()
    {
        spl_autoload_unregister([$this, 'load']);
        return $this;
    }

    /**
     * Load a class
     *
     * This load mechanism is not only responsible for locating and including the
     * file where a class is defined, but is also responsible for implementing Proem's
     * cascading file system. This is achieved by suffixing \Api onto the Proem part
     * of any namespace starting with Proem, including the class from within the Proem\Api
     * namespace, and then aliasing that class back to Proem (without the \Api suffix)
     *
     * @link http://proemframework.org/docs/cascading-namespace.html
     * @param string $class The absolute (including namespace) name of the class
     * @return Proem\Api\Autoloader
     */
    public function load($class)
    {
        if ($class[0] == '\\') {
            $class = substr($class, 1);
        }

        if ($file = $this->locate($class)) {
            include $file;
        } else {
            if (substr($class, 0, 5) == 'Proem') {
                $api_class = str_replace('Proem\\', 'Proem\\Api\\', $class);
                if ($file = $this->locate($api_class)) {
                    include $file;
                    class_alias($api_class, $class);
                }
            }
        }

        return $this;
    }

    /**
     * A simple wrapper around locateFile() that first
     * checks to see if we are using APC caching.
     *
     * @param string $class The name of the class
     * @return string|null The path, if found
     */
    private function locate($class)
    {
        if ($this->apcEnabled && !$file = apc_fetch($class)) {
            if ($file = $this->locateFile($class)) {
                apc_store($class, $file);
            }
        } else {
            $file = $this->locateFile($class);
        }

        return $file;
    }

    /**
     * Locate the path to the file where $class is defined.
     *
     * @param string $class The name of the class
     * @return string|null The path, if found
     */
    private function locateFile($class)
    {
        if (false !== $pos = strrpos($class, '\\')) {
            $namespace = substr($class, 0, $pos);
            $className = substr($class, $pos + 1);
            $normalized = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR . str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
            foreach ($this->namespaces as $space => $paths) {
                if (strpos($namespace, $space) !== 0) {
                    continue;
                }

                foreach ($paths as $path) {
                    $file = $path . DIRECTORY_SEPARATOR . $normalized;

                    if (is_file($file)) {
                        return $file;
                    }
                }
            }

        } else {
            $normalized = str_replace('_', DIRECTORY_SEPARATOR, $class) . '.php';
            foreach ($this->pearPrefixes as $prefix => $paths) {
                if (0 !== strpos($class, $prefix)) {
                    continue;
                }

                foreach ($paths as $path) {
                    $file = $path . DIRECTORY_SEPARATOR . $normalized;
                    if (is_file($file)) {
                        return $file;
                    }
                }
            }
        }
    }
}
