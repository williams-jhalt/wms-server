<?php

namespace ErpBundle\Repository;

use ErpBundle\Model\Product;
use ErpBundle\Model\ProductCollection;

interface ProductRepositoryInterface {

    /**
     * 
     * @param integer $limit
     * @param integer $offset
     * 
     * @return ProductCollection
     */
    public function findAll($limit = 1000, $offset = 0);
    
    /**
     * 
     * @param string $searchTerms
     * @param integer $limit
     * @param integer $offset
     * 
     * @return ProductCollection
     */
    public function findByTextSearch($searchTerms, $limit = 1000, $offset = 0);
    
    /**
     * 
     * @param string $itemNumber
     * 
     * @return Product
     */
    public function getByItemNumber($itemNumber);
    
    /**
     * @param integer $limit
     * @param integer $offset
     * 
     * @return ProductCollection
     */
    public function findCommittedItems($limit = 1000, $offset = 0);

}
