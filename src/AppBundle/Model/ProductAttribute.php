<?php

namespace AppBundle\Model;

class ProductAttribute {

    private $name;
    private $value;
    
    public function __construct($name, $value) {
        $this->name = $name;
        $this->value = $value;
    }

    public function getName() {
        return $this->name;
    }

    public function getValue() {
        return $this->value;
    }

    public function setName($name) {
        $this->name = $name;
        return $this;
    }

    public function setValue($value) {
        $this->value = $value;
        return $this;
    }

}
