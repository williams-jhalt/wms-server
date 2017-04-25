<?php

namespace ErpBundle\Model;

use JMS\Serializer\Annotation as JMS;

class ShipmentPackageCollection {

    /**
     * @JMS\Type("array<ErpBundle\Model\ShipmentPackage>")
     * @var ShipmentPackage[]
     */
    protected $shipmentPackages;
    
    /**
     * @param ShipmentPackage[] $shipmentPackages
     */
    public function __construct(array $shipmentPackages) {
        $this->shipmentPackages = $shipmentPackages;
    }

    /**
     * 
     * @return ShipmentPackage[]
     */
    function getShipmentPackages() {
        return $this->shipmentPackages;
    }

    /**
     * 
     * @param ShipmentPackage[] $shipmentPackages
     * @return ShipmentPackageCollection
     */
    function setShipmentPackages(array $shipmentPackages) {
        $this->shipmentPackages = $shipmentPackages;
        return $this;
    }

}
