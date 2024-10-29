<?php

class LeastSoldProducts
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getLeastSoldProducts($limit = 10, $offset = 0, $period = 3)
    {
        $sql = "SELECT p.rowid, p.ref, p.label, p.description, ";
        $sql.= "COALESCE(SUM(fd.qty), 0) as total_qty ";
        $sql.= "FROM ".MAIN_DB_PREFIX."product as p ";
        $sql.= "LEFT JOIN ".MAIN_DB_PREFIX."facturedet as fd ON p.rowid = fd.fk_product ";
        $sql.= "LEFT JOIN ".MAIN_DB_PREFIX."facture as f ON fd.fk_facture = f.rowid ";
        $sql.= "WHERE p.tosell = 1 ";
        $sql.= "AND (f.datef >= DATE_SUB(NOW(), INTERVAL ".(int)$period." MONTH) OR f.datef IS NULL) ";
        $sql.= "GROUP BY p.rowid, p.ref, p.label, p.description ";
        $sql.= "ORDER BY total_qty ASC ";
        $sql.= $this->db->plimit($limit + 1, $offset);

        $result = array();
        $resql = $this->db->query($sql);

        if ($resql) {
            while ($obj = $this->db->fetch_object($resql)) {
                $result[] = $obj;
            }
            $this->db->free($resql);
            return $result;
        }
        return false;
    }
}