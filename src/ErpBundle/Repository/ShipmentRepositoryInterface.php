<?php

namespace ErpBundle\Repository;

use ErpBundle\Model\Shipment;
use ErpBundle\Model\ShipmentCollection;
use ErpBundle\Model\ShipmentItemCollection;
use ErpBundle\Model\ShipmentPackageCollection;

interface ShipmentRepositoryInterface {

    /**
     * 
     * @param integer $limit
     * @param integer $offset
     * 
     * @return ShipmentCollection
     */
    public function findAll($limit = 1000, $offset = 0);

    /**
     * 
     * @param integer $limit
     * @param integer $offset
     * 
     * @return ShipmentCollection
     */
    public function findOpen($limit = 1000, $offset = 0);
    
    /**
     * 
     * @param integer $orderNumber
     * 
     * @return ShipmentCollection
     */
    public function findByOrderNumber($orderNumber);
    
    /**
     * 
     * @param integer $orderNumber
     * @param integer $recordSequence
     * 
     * @return Shipment
     */
    public function get($orderNumber, $recordSequence = 1);
    
    /**
     * 
     * @param integer $orderNumber
     * @param integer $recordSequence
     * 
     * @return ShipmentItemCollection
     */
    public function getItems($orderNumber, $recordSequence = 1);
    
    /**
     * 
     * @param integer $orderNumber
     * 
     * @return ShipmentPackageCollection
     */
    public function getPackages($orderNumber);
    
}
