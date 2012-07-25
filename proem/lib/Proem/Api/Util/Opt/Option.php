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

use Proem\Util\Process\Callback,
    Proem\Service\Asset\Standard as StandardAsset,
    Proem\Service\Manager\Template as ServiceManager;

/**
 * Provides a mechanism for validiting a value.
 *
 * These values are used by the Proem\Api\Utils\Opt\Options trait.
 */
class Option
{
    /**
     * Store the value.
     *
     * @var mixed $value
     */
    protected $value;

    /**
     * Store a is_required flag.
     *
     * @var bool
     */
    protected $is_required = false;

    /**
     * Store a is_type flag.
     *
     * @var string
     */
    protected $is_type;

    /**
     * Store a is_asset flag.
     *
     * @var string
     */
    protected $is_asset;

    /**
     * Store a is_object flag.
     *
     * @var bool
     */
    protected $is_object;

    /**
     * Store a is_classof flag.
     *
     * @var string
     */
    protected $is_classof;

    /**
     * Store a throws flag.
     *
     * @var string
     */
    protected $throws = null;

    /**
     * Store a unless flag.
     *
     * @var array
     */
    protected $unless = [];

    /**
     * Store type_validators.
     *
     * @var array
     */
    protected $type_validators    = [];

    /**
     * Instantiate the option object.
     *
     * We use the __FILE__ constant as a default here because
     * it is unlikely to ever be used as an actual value.
     *
     * @param mixed $value
     * @todo Using the __FILE__ constant here sux. Fix it!
     */
    public function __construct($value = __FILE__) {
        $this->value = $value;
    }

    /**
     * Add a custom type validator or override an existing validator.
     *
     * @param string $type
     * @param function $callback
     * @param bool $override
     * @return Proem\Api\Util\Opt\Option
     */
    public function addTypeValidator($type, $callback, $override = false)
    {
        if (!isset($this->type_validators[$type]) || $override) {
            $this->type_validators[$type] = $callback;
        }
        return $this;
    }

    /**
     * Set the value.
     *
     * @param mixed $value
     * @return Proem\Api\Util\Opt\Option
     */
    public function setValue($value) {
        $this->value = $value;
        return $this;
    }

    /**
     * Get the value.
     *
     * @return mixed $this->value
     */
    public function getValue() {
        return $this->value;
    }

    /**
     * Set this as required.
     *
     * @return Proem\Api\Util\Opt\Option
     */
    public function required() {
        $this->is_required = true;
        return $this;
    }

    /**
     * Is this option required?
     *
     * @return bool
     */
    public function isRequired() {
        return $this->is_required;
    }

    /**
     * Disable this option from being required if some other argument(s) has been provided
     *
     * @param string|array $options An array of Proem\Api\Util\Opt\Option objects
     * @return Proem\Api\Util\Opt\Option
     */
    public function unless($options)
    {
        if (is_array($options)) {
            $this->unless = $options;
        } else {
            $this->unless[] = $options;
        }
        return $this;
    }

    /**
     * Force this options value to be of a certain type.
     *
     * Once specified, this options value will then be processed through
     * an appropriate *type* validator.
     *
     * @param string $type
     * @return Proem\Api\Util\Opt\Option
     */
    public function type($type)
    {
        $this->is_type = $type;
        return $this;
    }

    /**
     * Force this options value to be an instance of a particular object.
     *
     * @param string $object A string representation of a class name
     * @return Proem\Api\Util\Opt\Option
     */
    public function object($object)
    {
        $this->is_object = $object;
        return $this;
    }

    /**
     * Force this options value to be an asset or an service mananger
     * providing a specific asset.
     *
     * @param $provides The asset this option provides
     * @return Proem\Api\Util\Opt\Option
     */
    public function asset($provides)
    {
        $this->is_asset = $provides;
        return $this;
    }

    /**
     * Set a custom exception to throw if invalid.
     *
     * @param \Exception $exception
     * @return Proem\Api\Util\Opt\Option
     */
    public function throws(callable $exception) {
        $this->throws = $exception;
        return $this;
    }

    /**
     * Force this pptions value to be a string representation of a
     * particular class or subclass.
     *
     * @param string $class
     * @return Proem\Api\Util\Opt\Option
     */
    public function classof($class)
    {
        $this->is_classof = $class;
        return $this;
    }

    /**
     * Validate this options value according to specified rules.
     *
     * @param array $options An array of all options that may have been processed alongside this Option
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     * @return bool
     */
    public function validate($options = []) {
        if ($this->unless) {
            $this->doUnlessCheck($options);
        }

        if ($this->is_required) {
            $this->doRequiredCheck();
        }

        if ($this->is_type && $this->value !== __FILE__) {
            $this->doTypeCheck();
        }

        if ($this->is_asset && $this->value !== __FILE__) {
            $this->doAssetCheck();
        }

        if ($this->is_object && $this->value !== __FILE__) {
            $this->doObjectCheck();
        }

        if ($this->is_classof && $this->value !== __FILE__) {
            $this->doClassOfCheck();
        }

        if ($this->value == __FILE__) {
            return false;
        }

        return true;
    }

    /**
     * Check to see if this option really is required based on
     * other options supplied.
     */
    protected function doUnlessCheck(array $options)
    {
        $keys = array_keys($options);
        if (!count(array_diff($this->unless, array_keys($options)))) {
            $this->is_required = false;
        }
    }

    /**
     * Check option is required.
     *
     * @throws InvalidArgumentException
     */
    protected function doRequiredCheck()
    {
        if ($this->value === __FILE__) {
            if ($this->throws === null) {
                throw new \InvalidArgumentException(' is a required option');
            } else {
                throw (new Callback($this->throws))->call();
            }
        }
    }

    /**
     * Validate the data type.
     *
     * This method also validates against custom supplied
     * validators.
     *
     * @thorws InvalidArgumentException
     * @thorws RuntimeException
     */
    protected function doTypeCheck()
    {
        if (isset($this->type_validators[$this->is_type])) {
            $result = (new Callback($this->type_validators[$this->is_type], [$this->value]))->call();
        } else {
            switch ($this->is_type) {
                case 'array':       $result = is_array($this->value);     break;
                case 'bool':        $result = is_bool($this->value);      break;
                case 'float':       $result = is_float($this->value);     break;
                case 'int':         $result = is_int($this->value);       break;
                case 'string':      $result = is_string($this->value);    break;
                case 'callable':    $result = is_callable($this->value);  break;
                case 'object':      $result = is_object($this->value);    break;
            }
        }

        if (!isset($result)) {
            throw new \RuntimeException('No validator found for type ' . $this->is_type);
        } else if (!$result) {
            if ($this->throws === null) {
                throw new \InvalidArgumentException(' did not pass the "' . $this->is_type . '" validator');
            } else {
                throw (new Callback($this->throws))->call();
            }
        }
    }

    /**
     * Validate option is Asset
     *
     * @thorws InvalidArgumentException
     */
    protected function doAssetCheck()
    {
        if ($this->value instanceof StandardAsset) {
            if ($this->value->provides() !== $this->is_asset) {
                if ($this->throws === null) {
                    throw new \InvalidArgumentException(' did not pass the "' . $this->is_asset . '" Asset validator');
                } else {
                    throw (new Callback($this->throws))->call();
                }
            }
        } elseif ($this->value instanceof ServiceManager) {
            if (!$this->value->provides($this->is_asset)) {
                if ($this->throws === null) {
                    throw new \InvalidArgumentException(' did not pass the "' . (is_array($this->is_asset) ? '[' . implode(', ', $this->is_asset) . ']' : $this->is_asset) . '" Asset validator');
                } else {
                    throw (new Callback($this->throws))->call();
                }
            }
        } else {
            if ($this->throws === null) {
                throw new \InvalidArgumentException(' is not a valid "Asset" or "Service\Manager"');
            } else {
                throw (new Callback($this->throws))->call();
            }
        }
    }

    /**
     * Validate object type.
     *
     * @thorws InvalidArgumentException
     */
    protected function doObjectCheck()
    {
        if (!$this->value instanceof $this->is_object) {
            if ($this->throws === null) {
                throw new \InvalidArgumentException(' is required to be an instance of ' . $this->is_object . ', ' . (is_object($this->value) ? get_class($this->value) : $this->value) . ' provided');
            } else {
                throw (new Callback($this->throws))->call();
            }
        }
    }

    /**
     * Validate class of type.
     *
     * @throws InvalidArgumentException
     */
    protected function doClassOfCheck()
    {
        if (!$this->value == $this->is_classof && !is_subclass_of($this->is_classof, $this->value)) {
            if ($this->throws === null) {
                throw new \InvalidArgumentException(' is required to be a string representation of the class of type ' . $this->is_classof);
            } else {
                throw (new Callback($this->throws))->call();
            }
        }
    }

}
