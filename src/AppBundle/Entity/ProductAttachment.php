<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductAttachmentRepository")
 */
class ProductAttachment {

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

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
     * @ORM\Column(type="boolean")
     */
    private $explicit;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Product", inversedBy="attachments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;

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

    public function getExplicit() {
        return $this->explicit;
    }

    public function setExplicit(bool $explicit) {
        $this->explicit = $explicit;

        return $this;
    }

    public function getProduct() {
        return $this->product;
    }

    public function setProduct($product) {
        $this->product = $product;

        return $this;
    }

}
