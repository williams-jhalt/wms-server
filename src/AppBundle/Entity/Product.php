<?php

namespace AppBundle\Entity;

use DateTime;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Product {

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $itemNumber;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2, nullable=true)
     */
    private $wholesalePrice;

    /**
     * @ORM\Column(type="date")
     */
    private $releaseDate;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $binLocation;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $quantityOnHand;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $quantityCommitted;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdOn;

    /**
     * @ORM\Column(type="datetime")
     */
    private $updatedOn;

    /**
     * @ORM\Column(type="boolean")
     */
    private $deleted;

    /**
     * @ORM\Column(type="boolean")
     */
    private $webItem;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $warehouse;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $unitOfMeasure;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $barcode;

    /**
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\ProductDetail", cascade={"persist", "remove"})
     */
    private $detail;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Manufacturer", inversedBy="products", cascade={"persist"})
     */
    private $manufacturer;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\ProductType", inversedBy="products", cascade={"persist"})
     */
    private $productType;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\ProductAttachment", mappedBy="product", orphanRemoval=true, cascade={"persist", "remove"})
     */
    private $attachments;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\MapPolicy", inversedBy="products")
     */
    private $mapPolicy;

    public function __construct() {
        $this->attachments = new ArrayCollection();
        $this->detail = new ProductDetail();
    }

    public function getId() {
        return $this->id;
    }

    public function getItemNumber() {
        return $this->itemNumber;
    }

    public function setItemNumber(string $itemNumber) {
        $this->itemNumber = $itemNumber;

        return $this;
    }

    public function getName() {
        return $this->name;
    }

    public function setName(string $name) {
        $this->name = $name;

        return $this;
    }

    public function getWholesalePrice() {
        return $this->wholesalePrice;
    }

    public function setWholesalePrice($wholesalePrice) {
        $this->wholesalePrice = $wholesalePrice;

        return $this;
    }

    public function getReleaseDate() {
        return $this->releaseDate;
    }

    public function setReleaseDate(DateTimeInterface $releaseDate) {
        $this->releaseDate = $releaseDate;

        return $this;
    }

    public function getBinLocation() {
        return $this->binLocation;
    }

    public function setBinLocation($binLocation) {
        $this->binLocation = $binLocation;

        return $this;
    }

    public function getQuantityOnHand() {
        return $this->quantityOnHand;
    }

    public function setQuantityOnHand($quantityOnHand) {
        $this->quantityOnHand = $quantityOnHand;

        return $this;
    }

    public function getQuantityCommitted() {
        return $this->quantityCommitted;
    }

    public function setQuantityCommitted($quantityCommitted) {
        $this->quantityCommitted = $quantityCommitted;

        return $this;
    }

    public function getCreatedOn() {
        return $this->createdOn;
    }

    public function setCreatedOn(DateTimeInterface $createdOn) {
        $this->createdOn = $createdOn;

        return $this;
    }

    public function getUpdatedOn() {
        return $this->updatedOn;
    }

    public function setUpdatedOn(DateTimeInterface $updatedOn) {
        $this->updatedOn = $updatedOn;

        return $this;
    }

    public function getDeleted() {
        return $this->deleted;
    }

    public function setDeleted(bool $deleted) {
        $this->deleted = $deleted;

        return $this;
    }

    public function getWebItem() {
        return $this->webItem;
    }

    public function setWebItem(bool $webItem) {
        $this->webItem = $webItem;

        return $this;
    }

    public function getWarehouse() {
        return $this->warehouse;
    }

    public function setWarehouse(string $warehouse) {
        $this->warehouse = $warehouse;

        return $this;
    }

    public function getUnitOfMeasure() {
        return $this->unitOfMeasure;
    }

    public function setUnitOfMeasure(string $unitOfMeasure) {
        $this->unitOfMeasure = $unitOfMeasure;

        return $this;
    }

    public function getDetail() {
        return $this->detail;
    }

    public function setDetail($detail) {
        $this->detail = $detail;

        return $this;
    }

    public function getManufacturer() {
        return $this->manufacturer;
    }

    public function setManufacturer($manufacturer) {
        $this->manufacturer = $manufacturer;

        return $this;
    }

    public function getProductType() {
        return $this->productType;
    }

    public function setProductType($productType) {
        $this->productType = $productType;

        return $this;
    }

    /**
     * @return Collection|ProductAttachment[]
     */
    public function getAttachments() {
        return $this->attachments;
    }

    public function addAttachment(ProductAttachment $attachment) {
        if (!$this->attachments->contains($attachment)) {
            $this->attachments[] = $attachment;
            $attachment->setProduct($this);
        }

        return $this;
    }

    public function removeAttachment(ProductAttachment $attachment) {
        if ($this->attachments->contains($attachment)) {
            $this->attachments->removeElement($attachment);
            // set the owning side to null (unless already changed)
            if ($attachment->getProduct() === $this) {
                $attachment->setProduct(null);
            }
        }

        return $this;
    }

    public function getMapPolicy() {
        return $this->mapPolicy;
    }

    public function setMapPolicy($mapPolicy) {
        $this->mapPolicy = $mapPolicy;

        return $this;
    }

    public function getBarcode() {
        return $this->barcode;
    }

    public function setBarcode($barcode) {
        $this->barcode = $barcode;
        return $this;
    }

    /**
     * @ORM\PrePersist
     */
    public function setCreatedAtValue() {
        $this->createdOn = new DateTime();
        $this->updatedOn = new DateTime();
    }

    /**
     * @ORM\PreUpdate
     */
    public function setUpdateAtValue() {
        $this->updatedOn = new DateTime();
    }

    public function setAttachments($attachments) {
        $this->attachments = $attachments;
        return $this;
    }

}
