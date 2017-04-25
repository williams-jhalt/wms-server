<?php

namespace ErpBundle\Repository;

use ErpBundle\Model\Product;
use ErpBundle\Model\ProductCollection;

class ClientProductRepository extends AbstractClientRepository implements ProductRepositoryInterface {

    /**
     * 
     * @param integer $limit
     * @param integer $offset
     * @return ProductCollection
     */
    public function findAll($limit = 1000, $offset = 0) {

        $format = 'json';

        $response = $this->client->get("products.{$format}", ['query' => ['limit' => $limit, 'offset' => $offset]]);
        
        $data = $response->getBody();

        $serializer = $this->erp->getSerializer();

        $result = $serializer->deserialize($data, 'ErpBundle\Model\ProductCollection', $format);

        return $result;
    }

    /**
     * 
     * @param string $searchTerms
     * @param integer $limit
     * @param integer $offset
     * @return ProductCollection
     */
    public function findByTextSearch($searchTerms, $limit = 1000, $offset = 0) {

        $format = 'json';

        $response = $this->client->get("products.{$format}", ['query' => ['limit' => $limit, 'offset' => $offset, 'search' => $searchTerms]]);
        
        $data = $response->getBody();

        $serializer = $this->erp->getSerializer();

        $result = $serializer->deserialize($data, 'ErpBundle\Model\ProductCollection', $format);

        return $result;
        
    }

    /**
     * 
     * @param string $itemNumber
     * @return Product
     */
    public function getByItemNumber($itemNumber) {
        
        $format = 'json';

        $response = $this->client->get("products/{$itemNumber}.{$format}");
        
        $data = $response->getBody();

        $serializer = $this->erp->getSerializer();

        $result = $serializer->deserialize($data, 'ErpBundle\Model\Product', $format);

        return $result;
        
    }
    
    public function findCommittedItems($limit = 1000, $offset = 0) {

        $format = 'json';

        $response = $this->client->get("products.{$format}", ['query' => ['limit' => $limit, 'offset' => $offset, 'committed' => true]]);
        
        $data = $response->getBody();

        $serializer = $this->erp->getSerializer();

        $result = $serializer->deserialize($data, 'ErpBundle\Model\ProductCollection', $format);

        return $result;
        
    }


}
