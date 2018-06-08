<?php

class Service extends Model
{
    public function getServList($limit){
        if($limit==0){
            $sql = "SELECT id, name, description FROM `se_services`";
        }
        else{
            $sql = "SELECT id, name, description FROM `se_services` LIMIT $limit";
        }
        $result = $this->db->query($sql);
        if($result){
            return $result;
        }
    }
    public function getCatList(){
        $sql = "SELECT id, name, description FROM `category`";
        $result = $this->db->query($sql);
        if($result){
            return $result;
        }
    }
    public function getServListTop(){
        $sql = "SELECT id, name, description FROM `se_services` WHERE `top`=1";
        $result = $this->db->query($sql);
        if($result){
            return $result;
        }
    }
}