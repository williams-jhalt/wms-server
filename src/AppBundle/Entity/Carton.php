<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Carton
 *
 * @ORM\Table(name="carton")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CartonRepository")
 */
class Carton {

    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\Column(name="ucc", type="string", length=255)
     */
    private $ucc;

    /**
     * @var float
     *
     * @ORM\Column(name="carton_weight", type="float", nullable=true)
     */
    private $weight;

    /**
     * @var float
     *
     * @ORM\Column(name="ship_height", type="float", nullable=true)
     */
    private $height;

    /**
     * @var float
     *
     * @ORM\Column(name="ship_length", type="float", nullable=true)
     */
    private $length;

    /**
     * @var float
     *
     * @ORM\Column(name="ship_width", type="float", nullable=true)
     */
    private $width;

    public function getUcc() {
        return $this->ucc;
    }

    public function getWeight() {
        return $this->weight;
    }

    public function getHeight() {
        return $this->height;
    }

    public function getLength() {
        return $this->length;
    }

    public function getWidth() {
        return $this->width;
    }

    public function setWeight($weight) {
        $this->weight = $weight;
        return $this;
    }

    public function setHeight($height) {
        $this->height = $height;
        return $this;
    }

    public function setLength($length) {
        $this->length = $length;
        return $this;
    }

    public function setWidth($width) {
        $this->width = $width;
        return $this;
    }

}
