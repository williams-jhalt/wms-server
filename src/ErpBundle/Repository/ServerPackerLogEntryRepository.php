<?php

namespace ErpBundle\Repository;

use DateTimeInterface;
use ErpBundle\Model\PackerLogEntry;
use ErpBundle\Model\PackerLogEntryCollection;

class ServerPackerLogEntryRepository extends AbstractServerRepository implements PackerLogEntryRepositoryInterface {

    public function findByStartDateAndEndDate(DateTimeInterface $startDate, DateTimeInterface $endDate, $limit = 1000, $offset = 0): PackerLogEntryCollection {

        $query = "FOR EACH ed_ucc128pk NO-LOCK "
                . "WHERE ed_ucc128pk.company_oe = '" . $this->erp->getCompany() . "' "
                . "AND ed_ucc128pk.build_date >= '" . $startDate->format('m/d/Y') . "' AND ed_ucc128pk.build_date <= '" . $endDate->format('m/d/Y') . "'";

        $fields = "user_id,"
                . "ucc,"
                . "qty_shp";

        $response = $this->erp->read($query, $fields, $limit, $offset);

        $result = array();

        foreach ($response as $erpItem) {
            $result[] = $this->_loadFromErp($erpItem);
        }

        return new PackerLogEntryCollection($result);
    }

    public function findByUcc($ucc, $limit = 1000, $offset = 0): PackerLogEntryCollection {

        $query = "FOR EACH ed_ucc128pk NO-LOCK "
                . "WHERE ed_ucc128pk.company_oe = '" . $this->erp->getCompany() . "' "
                . "AND ed_ucc128pk.ucc = '" . $ucc . "'";

        $fields = "user_id,"
                . "ucc,"
                . "qty_shp";

        $response = $this->erp->read($query, $fields, $limit, $offset);

        $result = array();

        foreach ($response as $erpItem) {
            $result[] = $this->_loadFromErp($erpItem);
        }

        return new PackerLogEntryCollection($result);
    }

    public function findByUserId($userId, $limit = 1000, $offset = 0): PackerLogEntryCollection {


        $query = "FOR EACH ed_ucc128pk NO-LOCK "
                . "WHERE ed_ucc128pk.company_oe = '" . $this->erp->getCompany() . "' "
                . "AND ed_ucc128pk.user_id = '" . $userId . "'";

        $fields = "user_id,"
                . "ucc,"
                . "qty_shp";

        $response = $this->erp->read($query, $fields, $limit, $offset);

        $result = array();

        foreach ($response as $erpItem) {
            $result[] = $this->_loadFromErp($erpItem);
        }

        return new PackerLogEntryCollection($result);
    }

    private function _loadFromErp($erpItem) {
        $t = new PackerLogEntry();
        $t->setUserId($erpItem->user_id);
        $t->setUcc($erpItem->ucc);
        $t->setQtyShipped($erpItem->qty_shp);
        return $t;
    }

}
