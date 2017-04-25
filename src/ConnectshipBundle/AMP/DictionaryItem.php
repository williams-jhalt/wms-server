<?php

namespace ConnectshipBundle\AMP;

class DictionaryItem
{

    /**
     * @var string $key
     */
    protected $key = null;

    /**
     * @var anyType $value
     */
    protected $value = null;

    /**
     * @param string $key
     * @param anyType $value
     */
    public function __construct($key, $value)
    {
      $this->key = $key;
      $this->value = $value;
    }

    /**
     * @return string
     */
    public function getKey()
    {
      return $this->key;
    }

    /**
     * @param string $key
     * @return \ConnectshipBundle\AMP\DictionaryItem
     */
    public function setKey($key)
    {
      $this->key = $key;
      return $this;
    }

    /**
     * @return anyType
     */
    public function getValue()
    {
      return $this->value;
    }

    /**
     * @param anyType $value
     * @return \ConnectshipBundle\AMP\DictionaryItem
     */
    public function setValue($value)
    {
      $this->value = $value;
      return $this;
    }

}
