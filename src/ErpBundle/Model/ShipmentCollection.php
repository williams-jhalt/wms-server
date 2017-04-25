<?php

namespace ErpBundle\Model;

use JMS\Serializer\Annotation as JMS;

class ShipmentCollection {

    /**
     * @JMS\Type("array<ErpBundle\Model\Shipment>")
     * @var Shipment[]
     */
    protected $shipments;
    
    /**
     * @param Shipment[] $shipments
     */
    public function __construct(array $shipments) {
        $this->shipments = $shipments;
    }

    /**
     * 
     * @return Shipment[]
     */
    function getShipments() {
        return $this->shipments;
    }

    /**
     * 
     * @param Shipment[] $shipments
     * @return ShipmentCollection
     */
    function setShipments(array $shipments) {
        $this->shipments = $shipments;
        return $this;
    }

}
