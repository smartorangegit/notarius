<?php

class Discount extends Model
{
    public function editDiscount($data, $id = null){
//        if ( !isset($data['alias']) || !isset($data['title']) || !isset($data['content']) ){
//            return false;
//        }

        $id = (int)$id;
        $servID = $this->db->escape($data['servID']);
        $notaryID = $this->db->escape($data['notaryID']);
        $cost = $this->db->escape($data['cost']);
        $discount = $this->db->escape($data['discount']);
        $expiration_date = $this->db->escape($data['expiration_date']);


        if ( !$id ){ // Add new record
            $sql = "
                insert into discounts
                   set serviceID = '{$servID}',
                       notaryID = '{$notaryID}',
                       cost = '{$cost}',
                       discount = '$discount',
                       expiration_date = '$expiration_date'
            ";
        } else { // Update existing record
            $sql = "
                update discounts
                   set serviceID = '{$servID}',
                       notaryID = '{$notaryID}',
                       cost = '{$cost}',
                       discount = '{$discount}',
                       expiration_date = '{$expiration_date}'
                   where id = {$id}
            ";
        }
        $result = $this->db->query($sql);
        if($result){
            return $result;
        }
    }

    public function getListOfDiscounts($id = null){
        $id = (int)$id;

        if ( !$id ) {
            $sql = "SELECT discounts.id, discounts.cost, discounts.discount, discounts.expiration_date, notary.fName,
                notary.sName, notary.mName, se_services.name
                FROM `discounts`
                LEFT JOIN notary ON discounts.notaryID = notary.id
                LEFT JOIN se_services ON discounts.serviceID = se_services.id";
            $result = $this->db->query($sql);
            if ($result) {
                return $result;
            }
        }
        else{
            $sql = "SELECT discounts.id, discounts.cost, discounts.discount, discounts.expiration_date, notary.fName,
                notary.sName, notary.mName, se_services.name, discounts.serviceID, discounts.notaryID
                FROM `discounts`
                LEFT JOIN notary ON discounts.notaryID = notary.id
                LEFT JOIN se_services ON discounts.serviceID = se_services.id WHERE discounts.id='{$id}'";
            $result = $this->db->query($sql);
            if ($result[0]) {
                return $result[0];
            }
        }
    }

    public function getNotaryList(){
        $sql = "SELECT id, fName, sName, mName FROM `notary` WHERE 1";
        $result = $this->db->query($sql);
        if($result){
            return $result;
        }
    }

    public function getServiceList(){
        $sql = "SELECT id, name FROM `se_services` WHERE 1";
        $result = $this->db->query($sql);
        if($result){
            return $result;
        }
    }

    public function getDiscounts($data){
        $where[] = ' 1';
        if($data['service'] != ''){
            $where[1] = " discounts.serviceID = '{$data['service']}'";
        }
        if($data['serviceName'] != ''){
            $where[2] = " se_services.name LIKE '%{$data['serviceName']}%'";
        }
        if($data['area'] != ''){
            $where[3] = " lo_mArea.id = '{$data['area']}'";
        }
        $sql = "SELECT discounts.id, discounts.cost, discounts.discount, discounts.expiration_date, notary.fName,
                notary.sName, notary.mName, se_services.name, notary.rating, notary.street, lo_metro.metroName,
                lo_mArea.mAreaName, notary.house, notary.couCom, notary.id as nID, notary.profPhoto, notary.login,
                discounts.serviceID
                FROM `discounts`
                LEFT JOIN notary ON discounts.notaryID = notary.id
                LEFT JOIN se_services ON discounts.serviceID = se_services.id
                LEFT JOIN lo_metro ON notary.metro = lo_metro.id
                LEFT JOIN lo_mArea ON notary.arrayName = lo_mArea.id
                WHERE ".implode(" AND ",$where);
        $result = $this->db->query($sql);
        if($result){
            return $result;
        }
    }

    public function getCountOfDiscounts($data){
        $where[] = ' 1';
        if($data['service'] != ''){
            $where[1] = " discounts.serviceID = '{$data['service']}'";
        }
        if($data['serviceName'] != ''){
            $where[2] = " se_services.name LIKE '%{$data['serviceName']}%'";
        }
        if($data['area'] != ''){
            $where[3] = " lo_mArea.id = '{$data['area']}'";
        }
        $sql = "SELECT COUNT(discounts.id) AS cou 
                FROM `discounts`
                LEFT JOIN notary ON discounts.notaryID = notary.id
                LEFT JOIN lo_mArea ON notary.arrayName = lo_mArea.id
                LEFT JOIN se_services ON discounts.serviceID = se_services.id
                WHERE ".implode(" AND ",$where);
        $result = $this->db->query($sql);
        if($result[0]){
            return $result[0];
        }
    }
    public function deleteDiscount($id){
        $sql = "DELETE FROM `discounts` WHERE id='{$id}'";
        $result = $this->db->query($sql);
        if($result){
            return $result;
        }
    }

    public function getServList(){
        $sql = "SELECT id,name FROM `se_services`";
        $result = $this->db->query($sql);
        if($result){
            return $result;
        }
    }

    public function getAreaList(){
        $sql = "SELECT id,areaName FROM `lo_area`";
        $result = $this->db->query($sql);
        if($result){
            return $result;
        }
    }
}