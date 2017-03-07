<?php

namespace AppBundle\Service;

use Williams\ErpBundle\Service\ErpService;
use Williams\WholesaleBundle\Service\WholesaleService;

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

    public function __construct(ErpService $erp, WholesaleService $wholesale) {
        $this->erp = $erp;
        $this->wholesale = $wholesale;
    }
    
    public function findBySearchTerms($searchTerms, $limit = 25, $offset = 0) {
        
        $repo = $this->erp->getProductRepository();
        
        $products = $repo->findByTextSearch($searchTerms, $limit, $offset)->getProducts();
        
        $result = array();
        
        foreach ($products as $product) {
            
            $t = new \AppBundle\Model\Product();
            $result[] = $this->loadProductFromErp($t, $product);
            
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
    
    private function loadProductFromErp(\AppBundle\Model\Product $product, \Williams\ErpBundle\Model\Product $erpProduct) {
        
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
    
    private function loadProductFromWholesale(\AppBundle\Model\Product $product, \Williams\WholesaleBundle\Model\Product $wholesaleProduct) {
        
        $product->setDescription($wholesaleProduct->getDescription())
                ->setColor($wholesaleProduct->getColor())
                ->setMaterial($wholesaleProduct->getMaterial())
                ->setHeight($wholesaleProduct->getHeight())
                ->setLength($wholesaleProduct->getLength())
                ->setWidth($wholesaleProduct->getWidth())
                ->setDiameter($wholesaleProduct->getDiameter())
                ->setWeight($wholesaleProduct->getWeight())
                ->setKeywords($wholesaleProduct->getKeywords());
        
        return $product;
        
    }

}
