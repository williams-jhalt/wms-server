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
            $t = new \AppBundle\Model\Product();
            $this->loadProductFromErp($t, $product);
            $wholesaleProduct = $this->wholesale->getProductRepository()->find($product->getItemNumber());
            $this->loadProductFromWholesale($t, $wholesaleProduct);
            $dimensions = $this->em->getRepository(\AppBundle\Entity\ProductDimension::class)->findOneByBarcode($t->getBarcode());
            if ($dimensions !== null) {
                $this->loadProductFromDimensions($t, $dimensions);
            }
            $result[] = $t;
        }

        return $result;
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

        if (empty($t->getHeight())) {
            $product->setHeight($dim->getHeight());
        }

        if (empty($t->getLength())) {
            $product->setLength($dim->getLength());
        }

        if (empty($t->getWidth())) {
            $product->setWidth($dim->getWidth());
        }

        if (empty($t->getWeight())) {
            $product->setWeight($dim->getWeight());
        }

        return $product;
    }

}
