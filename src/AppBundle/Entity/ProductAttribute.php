<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductAttributeRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class ProductAttribute {

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $value;

    /**
     * @ORM\ManyToOne(targetEntity="ProductDetail", inversedBy="attributes")
     */
    private $detail;

    public function __construct($detail = null, $name = null, $value = null) {
        $this->detail = $detail;
        $this->name = $name;
        $this->value = $value;
    }

    public function getId() {
        return $this->id;
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

    public function getDetail() {
        return $this->detail;
    }

    public function setDetail($detail) {
        $this->detail = $detail;
        return $this;
    }

}
