<?php

namespace AppBundle\Service;

use AppBundle\Model\ProductImage;
use Doctrine\ORM\EntityManager;
use ErpBundle\Service\ErpService;
use WholesaleBundle\Service\WholesaleService;

class ProductService {

    /**
     *
     * @var ErpService
     */
    private $erp;

    /**
     * @var WholesaleService
     */
    private $wholesale;

    /**
     *
     * @var EntityManager
     */
    private $em;

    public function __construct(ErpService $erp, WholesaleService $wholesale, EntityManager $em) {
        $this->erp = $erp;
        $this->wholesale = $wholesale;
        $this->em = $em;
    }

    public function findBySearchTerms($searchTerms, $limit = 25, $offset = 0) {

        $repo = $this->erp->getProductRepository();

        $products = $repo->findByTextSearch($searchTerms, $limit, $offset)->getProducts();

        $result = array();

        foreach ($products as $product) {
            $result[] = $this->buildProductFromErp($product);
        }

        return $result;
    }

    public function getByItemNumber($itemNumber) {

        $repo = $this->erp->getProductRepository();

        $product = $repo->getByItemNumber($itemNumber);

        return $this->buildProductFromErp($product);
    }

    /**
     * @param int $limit
     * @param int $offset
     * 
     * @return \AppBundle\Model\Product[]
     */
    public function getCommittedProducts($limit = 25, $offset = 0) {

        $repo = $this->erp->getProductRepository();

        $products = $repo->findCommittedItems($limit, $offset)->getProducts();

        $result = array();

        foreach ($products as $product) {
            $t = new \AppBundle\Model\Product();
            $result[] = $this->loadProductFromErp($t, $product);
        }

        return $result;
    }

    private function loadProductFromErp(\AppBundle\Model\Product $product, \ErpBundle\Model\Product $erpProduct) {

        $product->setBarcode($erpProduct->getBarcode())
                ->setBinLocation($erpProduct->getBinLocation())
                ->setCreatedOn($erpProduct->getCreatedOn())
                ->setDeleted($erpProduct->getDeleted())
                ->setItemNumber($erpProduct->getItemNumber())
                ->setManufacturerCode($erpProduct->getManufacturerCode())
                ->setName($erpProduct->getName())
                ->setProductTypeCode($erpProduct->getProductTypeCode())
                ->setQuantityCommitted($erpProduct->getQuantityCommitted())
                ->setQuantityOnHand($erpProduct->getQuantityOnHand())
                ->setReleaseDate($erpProduct->getReleaseDate())
                ->setUnitOfMeasure($erpProduct->getUnitOfMeasure())
                ->setWarehouse($erpProduct->getWarehouse())
                ->setWholesalePrice($erpProduct->getWholesalePrice())
                ->setWebItem($erpProduct->getWebItem());

        return $product;
    }

    private function loadProductFromWholesale(\AppBundle\Model\Product $product, \WholesaleBundle\Model\Product $wholesaleProduct) {

        $product->setDescription($wholesaleProduct->getDescription())
                ->setColor($wholesaleProduct->getColor())
                ->setMaterial($wholesaleProduct->getMaterial())
                ->setHeight($wholesaleProduct->getHeight())
                ->setLength($wholesaleProduct->getLength())
                ->setWidth($wholesaleProduct->getWidth())
                ->setDiameter($wholesaleProduct->getDiameter())
                ->setWeight($wholesaleProduct->getWeight())
                ->setKeywords($wholesaleProduct->getKeywords());

        $wholesaleImages = $this->wholesale->getProductImageRepository()->findBySku($product->getItemNumber());

        $images = array();

        foreach ($wholesaleImages->getItems() as $wholesaleImage) {
            $images[] = $wholesaleImage;
        }

        $product->setImages($images);

        return $product;
    }

    private function loadProductFromDimensions(\AppBundle\Model\Product $product, \AppBundle\Entity\ProductDimension $dim) {

        if (empty($product->getHeight())) {
            $product->setHeight($dim->getHeight());
        }

        if (empty($product->getLength())) {
            $product->setLength($dim->getLength());
        }

        if (empty($product->getWidth())) {
            $product->setWidth($dim->getWidth());
        }

        if (empty($product->getWeight())) {
            $product->setWeight($dim->getWeight());
        }

        return $product;
    }

    /**
     * 
     * @param \ErpBundle\Model\Product $erpProduct
     * @return \AppBundle\Entity\Product
     */
    private function buildProductFromErp(\ErpBundle\Model\Product $erpProduct) {

        // create new Product model
        $product = new \AppBundle\Model\Product();
        $this->loadProductFromErp($product, $erpProduct); // copy information from ERP into new product model

        $wholesaleProduct = $this->wholesale->getProductRepository()->find($product->getItemNumber()); // look for item on Wholesale
        if ($wholesaleProduct !== null) {
            $this->loadProductFromWholesale($product, $wholesaleProduct); // if it exists add the data to the model
        }

        $dimensions = $this->em->getRepository(\AppBundle\Entity\ProductDimension::class)->findOneByBarcode($product->getBarcode()); // look for item in local cubiscan db
        if ($dimensions !== null) {
            $this->loadProductFromDimensions($product, $dimensions); // if it exists add the data to the model
        }

        $localProduct = $this->em->getRepository(\AppBundle\Entity\Product::class)->findOneByItemNumber($product->getItemNumber());

        if ($localProduct == null || $localProduct->getDetail()->getUpdatedOn() < $wholesaleProduct->getUpdatedOn()) {
            $localProduct = new \AppBundle\Entity\Product();
            $localProduct->setItemNumber($product->getItemNumber());

            $localProduct->setName($product->getName());
            $localProduct->setWholesalePrice($product->getWholesalePrice());
            $localProduct->setReleaseDate($product->getReleaseDate());
            $localProduct->setBinLocation($product->getBinLocation());
            $localProduct->setQuantityOnHand($product->getQuantityOnHand());
            $localProduct->setQuantityCommitted($product->getQuantityCommitted());
            $localProduct->setDeleted($product->getDeleted());
            $localProduct->setWebItem($product->getWebItem());
            $localProduct->setWarehouse($product->getWarehouse());
            $localProduct->setUnitOfMeasure($product->getUnitOfMeasure());
            $localProduct->setBarcode($product->getBarcode());

            if (!empty($product->getManufacturerCode())) {

                $manufacturer = $this->em->getRepository(\AppBundle\Entity\Manufacturer::class)->findOneByCode($product->getManufacturerCode());

                if ($manufacturer == null) {
                    $manufacturer = new \AppBundle\Entity\Manufacturer();
                    $manufacturer->setCode($product->getManufacturerCode());
                    $manufacturer->setName($product->getManufacturerCode());
                    $whsManufacturer = $this->wholesale->getManufacturerRepository()->find($product->getManufacturerCode());
                    if ($whsManufacturer !== null) {
                        $manufacturer->setName($whsManufacturer->getName());
                        $manufacturer->setActive($whsManufacturer->getActive());
                    }
                    $this->em->persist($manufacturer);
                    $this->em->flush($manufacturer);
                }

                $localProduct->setManufacturer($manufacturer);
            }

            if (!empty($product->getProductTypeCode())) {

                $type = $this->em->getRepository(\AppBundle\Entity\ProductType::class)->findOneByCode($product->getProductTypeCode());

                if ($type == null) {
                    $type = new \AppBundle\Entity\ProductType();
                    $type->setCode($product->getProductTypeCode());
                    $type->setName($product->getProductTypeCode());
                    $whsType = $this->wholesale->getProductTypeRepository()->find($product->getProductTypeCode());
                    if ($whsType !== null) {
                        $type->setName($whsType->getName());
                        $type->setActive($whsType->getActive());
                    }
                    $this->em->persist($type);
                    $this->em->flush($type);
                }

                $localProduct->setProductType($type);
            }

            $attachments = $localProduct->getAttachments();

            foreach ($product->getImages() as $image) {
                $attachment = new \AppBundle\Entity\ProductAttachment();
                $attachment->setUrl($image->getImageUrl());
                $attachment->setExplicit($image->getExplicit());
                $attachment->setFileType($image->getFileType());
                $attachment->setFilename($image->getFilename());
                $attachment->setProduct($localProduct);
                $attachments[] = $attachment;
            }

            $localProduct->setAttachments($attachments);

            $detail = $localProduct->getDetail();

            $detail->setName($wholesaleProduct->getName());
            $detail->setDescription($wholesaleProduct->getDescription());
            $detail->setBrand($wholesaleProduct->getBrand());
            $detail->setPackageHeight($product->getHeight());
            $detail->setPackageLength($product->getLength());
            $detail->setPackageWidth($product->getWidth());
            $detail->setPackageWeight($product->getWeight());
            $detail->setDimUnit("IN");
            $detail->setWeightUnit("LB");

            (!empty($wholesaleProduct->getProductLength())) ? $detail->addAttribute(new \AppBundle\Entity\ProductAttribute($detail, 'product_length', $wholesaleProduct->getProductLength())) : null;
            (!empty($wholesaleProduct->getInsertableLength())) ? $detail->addAttribute(new \AppBundle\Entity\ProductAttribute($detail, 'insertable_length', $wholesaleProduct->getInsertableLength())) : null;
            (!empty($wholesaleProduct->getRealistic())) ? $detail->addAttribute(new \AppBundle\Entity\ProductAttribute($detail, 'realistic', $wholesaleProduct->getRealistic())) : null;
            (!empty($wholesaleProduct->getBalls())) ? $detail->addAttribute(new \AppBundle\Entity\ProductAttribute($detail, 'balls', $wholesaleProduct->getBalls())) : null;
            (!empty($wholesaleProduct->getSuctionCup())) ? $detail->addAttribute(new \AppBundle\Entity\ProductAttribute($detail, 'suction_cup', $wholesaleProduct->getSuctionCup())) : null;
            (!empty($wholesaleProduct->getHarness())) ? $detail->addAttribute(new \AppBundle\Entity\ProductAttribute($detail, 'harness', $wholesaleProduct->getHarness())) : null;
            (!empty($wholesaleProduct->getVibrating())) ? $detail->addAttribute(new \AppBundle\Entity\ProductAttribute($detail, 'vibrating', $wholesaleProduct->getVibrating())) : null;
            (!empty($wholesaleProduct->getDoubleEnded())) ? $detail->addAttribute(new \AppBundle\Entity\ProductAttribute($detail, 'double_ended', $wholesaleProduct->getDoubleEnded())) : null;
            (!empty($wholesaleProduct->getCircumference())) ? $detail->addAttribute(new \AppBundle\Entity\ProductAttribute($detail, 'circumference', $wholesaleProduct->getCircumference())) : null;

            $localProduct->setDetail($detail);

            $this->em->persist($localProduct);
            $this->em->flush();
        }

        return $localProduct;
    }

}
