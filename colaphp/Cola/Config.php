<?php
class Cola_Config
{
    /**
     * Whether in-memory modifications to configuration data are allowed
     *
     * @var boolean
     */
    protected $_overwrite;

    /**
     * Contains array of configuration data
     *
     * @var array
     */
    protected $_data = array();

    /**
     * Cola_Config provides a property based interface to
     * an array. The data are read-only unless $allowModifications
     * is set to true on construction.
     *
     * Cola_Config also implements Countable and Iterator to
     * facilitate easy access to the data.
     *
     * @param  array   $array
     * @param  boolean $allowModifications
     * @return void
     */
    public function __construct(array $data = array(), $overwrite = false)
    {
        $this->_overwrite = (boolean) $overwrite;

        $this->_data = $data;
    }

    /**
     * Retrieve a value and return $default if there is no element set.
     *
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function get($name = null, $default = null, $delimiter = '.')
    {
        if (null === $name) {
            return $this->_data;
        }

        if (false === strpos($name, $delimiter)) {
            return isset($this->_data[$name]) ? $this->_data[$name] : $default;
        }

        $name = explode($delimiter, $name);

        $ret = $this->_data;
        foreach ($name as $key) {
            if (!isset($ret[$key])) return $default;
            $ret = $ret[$key];
        }

        return $ret;
    }

    /**
     * Magic function so that $obj->value will work.
     *
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        return $this->get($name);
    }

    public function set($name, $value, $delimiter = '.')
    {
        $pos = & $this->_data;
        if (!is_string($delimiter) || false === strpos($name, $delimiter)) {
            $key = $name;
        } else {
            $name = explode($delimiter, $name);
            $cnt = count($name);
            for ($i = 0; $i < $cnt - 1; $i ++) {
                if (!isset($pos[$name[$i]])) $pos[$name[$i]] = array();
                $pos = & $pos[$name[$i]];
            }
            $key = $name[$cnt - 1];
        }

        if (!$this->_overwrite && isset($pos[$key])) {
            throw new Cola_Exception('this name of config is read only');
        } else {
            $pos[$key] = $value;
        }
    }

    /**
     * Only allow setting of a property if $allowModifications
     * was set to true on construction. Otherwise, throw an exception.
     *
     * @param  string $name
     * @param  mixed  $value
     * @throws Cola_Exception
     * @return void
     */
    public function __set($name, $value)
    {
        $this->set($name, $value);
    }

    /**
     * Support isset() overloading on PHP 5.1
     *
     * @param string $name
     * @return boolean
     */
    public function __isset($name)
    {
        return isset($this->_data[$name]);
    }

    /**
     * Support unset() overloading on PHP 5.1
     *
     * @param  string $name
     * @throws Cola_Exception
     * @return void
     */
    public function __unset($name)
    {
        if ($this->_allowModifications) {
            unset($this->_data[$name]);
        } else {
            throw new Cola_Exception('Cola_Config is read only');
        }
    }


    /**
     * Defined by Iterator interface
     *
     * @return mixed
     */
    public function keys()
    {
        return array_keys($this->_data);
    }

    /**
     * Prevent any more modifications being made to this instance. Useful
     * after merge() has been used to merge multiple Cola_Config objects
     * into one object which should then not be modified again.
     *
     */
    public function overwrite($overwrite = null)
    {
        if (null === $overwrite) return $this->_overwrite;
        $this->_overwrite = $overwrite;
        return $this;
    }

    public function merge($config)
    {
        $this->_data = $this->_merge($this->_data, $config);
        return $this;
    }

    protected function _merge($arr1, $arr2)
    {
        foreach($arr2 as $key => $value) {
            if(isset($arr1[$key]) && is_array($value)) {
                $arr1[$key] = $this->_merge($arr1[$key], $arr2[$key]);
            } else {
                $arr1[$key] = $value;
            }
        }
        return $arr1;
    }

    public function &reference()
    {
        return $this->_data;
    }
}