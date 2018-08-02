<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ManufacturerRepository")
 */
class Manufacturer {

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="boolean")
     */
    private $active = true;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Product", mappedBy="manufacturer")
     */
    private $products;

    public function __construct() {
        $this->products = new ArrayCollection();
    }

    public function getId() {
        return $this->id;
    }

    public function getCode() {
        return $this->code;
    }

    public function setCode(string $code) {
        $this->code = $code;

        return $this;
    }

    public function getName() {
        return $this->name;
    }

    public function setName(string $name) {
        $this->name = $name;

        return $this;
    }

    public function getActive() {
        return $this->active;
    }

    public function setActive(bool $active) {
        $this->active = $active;

        return $this;
    }

    /**
     * @return Collection|Product[]
     */
    public function getProducts() {
        return $this->products;
    }

    public function addProduct(Product $product) {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setManufacturer($this);
        }

        return $this;
    }

    public function removeProduct(Product $product) {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
            // set the owning side to null (unless already changed)
            if ($product->getManufacturer() === $this) {
                $product->setManufacturer(null);
            }
        }

        return $this;
    }

}
