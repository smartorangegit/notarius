<?php

class Notaries extends Model
{
    public function getNotariesCords(){
        $sql = "SELECT geolocation, fName, sName, mName, userID, rating from `notary` WHERE geolocation <> ''";
        $result = $this->db->query($sql);
        if($result){
            return $result;
        }
    }
    public function getNotariesList(){
        $sql = "SELECT * from `notary` ";
        $result = $this->db->query($sql);
        if($result){
            return $result;
        }
    }
    public function getRatingNotaryByID($notaries){

    }
    public function getContPages($data){
//        if(empty($data['page']) || !$data['page'] || $data['page']==1){
//            $lA = 0;
//            $lB = 2;
//        }
//        else{
//            $lA = $data['page'];
//            $lB = $lA+1;
//        }
        if(empty($data['fio']) && empty( $data['serviceName']) && empty($data['metro']) && empty($data['street']) && empty($data['area']) && empty($data['workTimeS']) &&
            empty($data['workTimeF']) && empty($data['stWorkS']) && empty($data['stWorkF']) && empty($data['saWorkS']) && empty($data['saWorkF']) && empty($data['costS'] )
            && empty($data['costF']) && ($data['typeOfNotary']=='') && empty($data['ratingMin']) && empty($data['ratingMax']) && ($data['poweredByExit']=='')){
            $where[] = 1;
            $inner = '';
        }
        else{
            if($data['fio']){
                $where[0] = "(fName LIKE '%" . $data['fio'] . "%' OR sName LIKE '%" . $data['fio'] . "%' OR mName LIKE '%" . $data['fio'] . "%' )";
            }
            if(!empty($data['serviceName']) ){
                $whereCount[] = 1;
                $where[1] = '(no_serv_list.serviceID  = '.$data['serviceName'].')';
                $inner = "INNER JOIN  `no_serv_list` ON no_serv_list.notaryID=notary.userID ";
            }
            if(!empty($data['metro'])){
                $where[2] = "metro = ".$data['metro'];
            }
            if(!empty($data['street'])){
                $where[3] = "street LIKE '%" . $data['street'] . "%'";
            }
            /*тут будет иф с райноном*/
            if(!empty($data['workTimeS']) || !empty($data['workTimeF'])){
                $workTimeS = $data['workTimeS'];
                $workTimeF = $data['workTimeF'];
                if($data['workTimeS']==''){
                    $workTimeS =  $data['workTimeF'];
                }
                if($data['workTimeF']==''){
                    $workTimeF =  $data['workTimeS'];
                }

                $where[5] = "(stWeekday <= '" . $workTimeS. "' AND etWeekday >= '" . $workTimeF . "')";
            }
            if(!empty($data['stWorkS']) || !empty($data['stWorkF'])){
                $stWorkS = $data['stWorkS'];
                $stWorkF = $data['stWorkF'];
                if($data['stWorkS']==''){
                    $stWorkS =  $data['stWorkF'];
                }
                if($data['stWorkF']==''){
                    $stWorkF =  $data['stWorkS'];
                }
                $where[6] = "(stSaturday <= '" . $stWorkS . "' AND etSaturday >= '" . $stWorkF . "')";
            }
            if(!empty($data['saWorkS']) || !empty($data['saWorkF'])){
                $saWorkS = $data['saWorkS'];
                $saWorkF = $data['saWorkF'];
                if($data['saWorkS']==''){
                    $saWorkS =  $data['saWorkF'];
                }
                if($data['saWorkF']==''){
                    $saWorkF =  $data['saWorkS'];
                }
                $where[7] = "(stSunday <= '" . $saWorkS  . "' AND etSunday >= '" . $saWorkF . "')";
            }
            if(!empty($data['area'])){
                $where[4] = "area = ".$data['area'];
            }
            if(($data['typeOfNotary'] != '')){
                $where[9] = "typeOfNotary = ".$data['typeOfNotary'];
            }
            if(!empty($data['ratingMin']) && !empty($data['ratingMax'])){
                $where[10] = "(rating BETWEEN '" . $data['ratingMin'] . "' AND '" . $data['ratingMax'] .'.01'. "')";;
            }
            if(!empty($data['costS']) || !empty($data['costF'])){
                $costS = $data['costS'];
                $costF = $data['costF'];
                if($data['costS']==''){
                    $costS =  $data['costF'];
                }
                if($data['costF']==''){
                    $costF =  $data['costS'];
                }

                $where[11] = "(no_serv_list.fullSumServ >= '" . $costS . "' AND no_serv_list.fullSumServ <= '" . $costF . "')";
                $inner = "INNER JOIN  `no_serv_list` ON no_serv_list.notaryID=notary.userID ";
            }
            if($data['poweredByExit'] != ''){
                $where[12] = "poweredByExit = ".$data['poweredByExit'];
            }
            //$where[] = 1;
        }

        $sql = "SELECT COUNT(DISTINCT notary.id) as countNotaries  FROM `notary` ".$inner." WHERE ".implode(" AND ",$where);
        $result = $this->db->query($sql);
        //return $sql;
        if($result){
           return $result[0];
        }
    }
    public function getListOfServices(){
        $sql = "select distinct `serviceID` from `no_serv_list`";
        $result = $this->db->query($sql);
        if($result){
            return $result;
        }
    }
    public function getServicesInfo($data){
        $sql = "SELECT * FROM `se_services` WHERE `id` IN ($data);";
        $result = $this->db->query($sql);
        if($result){
            return $result;
        }
    }
    public function getListOfMetros(){
        $sql = "SELECT lo_metro.metroName, lo_metro.id FROM `lo_metro`";
        $result = $this->db->query($sql);
        if($result){
            return $result;
        }
    }
    public function getListOfAreas(){
        $sql = "SELECT lo_area.areaName, lo_area.id FROM `lo_area`";
        $result = $this->db->query($sql);
        if($result){
            return $result;
        }
    }

    public function getNotaryByID($id){
        $sql = "SELECT notary.login, notary.fName, notary.sName, notary.mName, notary.rating, notary.poweredByExit, users.regDt,
                notary.aboutNotary, notary.is_active, notary.profPhoto, notary.id, notary.metro, notary.arrayName, lo_metro.metroName, lo_mArea.mAreaName, notary.video,
                notary.userID
                FROM `notary`
                LEFT JOIN `users` ON notary.userID=users.id 
                LEFT JOIN `lo_metro` ON notary.metro=lo_metro.id
                LEFT JOIN `lo_mArea` ON notary.arrayName=lo_mArea.id
                WHERE userID='{$id}'";
        $result = $this->db->query($sql);
        if($result[0]){
            return $result[0];
        }
    }

    public function getServicesByNotaryID($id){
        $sql = "select se_services.name, se_services.id, no_serv_list.costService, no_serv_list.costAService, no_serv_list.id as idDell  from se_services
                 INNER JOIN no_serv_list ON no_serv_list.serviceID = se_services.id  where no_serv_list.notaryID={$id}";
        $result = $this->db->query($sql);
        if ( isset($result) ){
            return $result;
        }
    }

    public function getCommentsByNotaryID($id){
        $sql = "SELECT deal.rating, deal.userComment
                FROM `deal` 
                LEFT JOIN `notary` ON deal.notaryID=notary.id
                WHERE deal.transactionStatus='4' AND  notary.userID='{$id}'";
        $result = $this->db->query($sql);
        if($result){
            return $result;
        }
    }

    public function getCountFD($id){
        $sql = "SELECT COUNT(id) as id FROM `deal` WHERE `notaryID`='{$id}' AND `transactionStatus`=4";
        $result = $this->db->query($sql);
        if($result[0]){
            return $result[0];
        }
    }

    public function getCountRW($id){
        $sql = "SELECT COUNT(id) as countCom FROM `deal`  WHERE `notaryID`='{$id}' AND `userComment` <> '';";
        $result = $this->db->query($sql);
        if($result[0]){
            return $result[0];
        }
    }

    public function getMidCountRW($id){
        $sql = "SELECT COUNT(id) as countCom FROM `deal`  WHERE `notaryID`='{$id}' AND `rating` >= 3";
        $result = $this->db->query($sql);
        if($result[0]){
            return $result[0];
        }
    }

    public function getListCat(){
        $sql = "SELECT name,id
                FROM `category`";
        $result = $this->db->query($sql);
        if($result){
            return $result;
        }
    }

    public function getMetro(){
        $sql = "SELECT metroName,id
                FROM `lo_metro`";
        $result = $this->db->query($sql);
        if($result){
            return $result;
        }
    }

}