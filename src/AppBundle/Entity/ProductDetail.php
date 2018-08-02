<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductDetailRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class ProductDetail {

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
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $htmlDescription;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $brand;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $category;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=3, nullable=true)
     */
    private $packageHeight;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=3, nullable=true)
     */
    private $packageLength;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=3, nullable=true)
     */
    private $packageWidth;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=3, nullable=true)
     */
    private $packageWeight;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $dimUnit;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $weightUnit;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private $msrp;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private $mapPrice;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $attributes;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdOn;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedOn;

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function setName(string $name) {
        $this->name = $name;

        return $this;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setDescription($description) {
        $this->description = $description;

        return $this;
    }

    public function getHtmlDescription() {
        return $this->htmlDescription;
    }

    public function setHtmlDescription($htmlDescription) {
        $this->htmlDescription = $htmlDescription;

        return $this;
    }

    public function getBrand() {
        return $this->brand;
    }

    public function setBrand($brand) {
        $this->brand = $brand;

        return $this;
    }

    public function getCategory() {
        return $this->category;
    }

    public function setCategory($category) {
        $this->category = $category;

        return $this;
    }

    public function getPackageHeight() {
        return $this->packageHeight;
    }

    public function setPackageHeight($packageHeight) {
        $this->packageHeight = $packageHeight;

        return $this;
    }

    public function getPackageLength() {
        return $this->packageLength;
    }

    public function setPackageLength($packageLength) {
        $this->packageLength = $packageLength;

        return $this;
    }

    public function getPackageWidth() {
        return $this->packageWidth;
    }

    public function setPackageWidth($packageWidth) {
        $this->packageWidth = $packageWidth;

        return $this;
    }

    public function getPackageWeight() {
        return $this->packageWeight;
    }

    public function setPackageWeight($packageWeight) {
        $this->packageWeight = $packageWeight;

        return $this;
    }

    public function getDimUnit() {
        return $this->dimUnit;
    }

    public function setDimUnit($dimUnit) {
        $this->dimUnit = $dimUnit;

        return $this;
    }

    public function getWeightUnit() {
        return $this->weightUnit;
    }

    public function setWeightUnit($weightUnit) {
        $this->weightUnit = $weightUnit;

        return $this;
    }

    public function getMsrp() {
        return $this->msrp;
    }

    public function setMsrp($msrp) {
        $this->msrp = $msrp;

        return $this;
    }

    public function getMapPrice() {
        return $this->mapPrice;
    }

    public function setMapPrice($mapPrice) {
        $this->mapPrice = $mapPrice;

        return $this;
    }

    public function getAttributes() {
        return $this->attributes;
    }

    public function setAttributes($attributes) {
        $this->attributes = $attributes;

        return $this;
    }

    public function getCreatedOn() {
        return $this->createdOn;
    }

    public function getUpdatedOn() {
        return $this->updatedOn;
    }

    public function setCreatedOn($createdOn) {
        $this->createdOn = $createdOn;
        return $this;
    }

    public function setUpdatedOn($updatedOn) {
        $this->updatedOn = $updatedOn;
        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedAtValue() {
        $this->createdOn = new \DateTime();
        $this->updatedOn = new \DateTime();
    }

    /**
     * @ORM\PreUpdate
     */
    public function setUpdateAtValue() {
        $this->updatedOn = new \DateTime();
    }

}
