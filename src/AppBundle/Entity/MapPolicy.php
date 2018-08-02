<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MapPolicyRepository")
 */
class MapPolicy {

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $file;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $url;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $filename;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $fileType;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Product", mappedBy="mapPolicy")
     */
    private $products;

    public function __construct() {
        $this->products = new ArrayCollection();
    }

    public function getId() {
        return $this->id;
    }

    public function getUrl() {
        return $this->url;
    }

    public function setUrl(string $url) {
        $this->url = $url;

        return $this;
    }

    public function getFilename() {
        return $this->filename;
    }

    public function setFilename(string $filename) {
        $this->filename = $filename;

        return $this;
    }

    public function getFileType() {
        return $this->fileType;
    }

    public function setFileType(string $fileType) {
        $this->fileType = $fileType;

        return $this;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setDescription(string $description) {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection|Product[]
     */
    public function getProducts(): Collection {
        return $this->products;
    }

    public function addProduct(Product $product) {
        if (!$this->products->contains($product)) {
            $this->products[] = $product;
            $product->setMapPolicy($this);
        }

        return $this;
    }

    public function removeProduct(Product $product) {
        if ($this->products->contains($product)) {
            $this->products->removeElement($product);
            // set the owning side to null (unless already changed)
            if ($product->getMapPolicy() === $this) {
                $product->setMapPolicy(null);
            }
        }

        return $this;
    }

}
