<?php
class Board extends Model
{
    public function getGroups(){

    $sql = "select * from  `listOfgroups`";
    $result = $this->db->query($sql);
    return $result;
    }

    public function getServList(){
        $sql = "select id,name from se_services";
        $result = $this->db->query($sql);
        return $result;
    }

    public function getNotaryList(){
        $sql = "SELECT deal.notaryList, deal.serviceID, deal.transactionStatus, deal.id as deID FROM deal 
                INNER JOIN se_services ON se_services.id=deal.serviceID WHERE deal.transactionStatus = 1  ORDER BY deal.id ASC";
        $result = $this->db->query($sql);
        return $result;
    }

    public function getID($data){
        $sql = "select common_user.userID from common_user INNER JOIN users  ON common_user.userID=users.id WHERE users.login='{$data}'";
        $result = $this->db->query($sql);
        return $result;
    }

    public function Zayavka($data,$id){
        $appTime = date("H:i:s");
        $appDate = date("Y-m-d");
        $serviceName = $data['services'];
        $date = $data['date'];
        $time = $data['time'];
        $homeCheck = 0 ;
        $status = 1;
        if(!empty($data['homeCheck'])){
            $homeCheck = 1;
        }
        $sql = "insert into `deal`
                set
                userID = $id,
                serviceID='{$serviceName}',
                dateOf='{$date}',
                timeOf='{$time}',
                place='{$homeCheck}',
                transactionStatus='{$status}',
                appTime='{$appTime}',
                appDate='$appDate'";
    $result = $this->db->query($sql);
    if(isset($result)){
        return $result;
        }
    }
    public function getNotary($login){
        $login = $this->db->escape($login);

        $sql = "select id from `users` where login = '{$login}' limit 1";
        $result = $this->db->query($sql);

        $sql2 = "select id from `notary` where userID = '{$result[0]['id']}' limit 1";
        $result2 = $this->db->query($sql2);
        if ( isset($result2[0]) ){
            return $result2[0];
        }
        return false;
    }
    public function getDeal($notary,$group,$nl){
        $check = "select groupID from deal WHERE deal.transactionStatus = 1 ORDER BY id DESC";
        $checkSql = $this->db->query($check);
        foreach ($checkSql as $itemG){
            $grMass[] = $itemG['groupID'];
        }

        for($itz=0; $itz<count($nl); $itz++) {
            if (stristr($nl[$itz], ',') === FALSE) {
                //echo '"earth" не найдена в строке';
            } else {
                $nia[] = explode(',',$nl[$itz]);
            }
        }
        if(empty($nia)||$nia==0) {
            $fnia = $nl;
        }
        else{
            $fnia = $nia[0];
        }
        if(array_intersect($group,$grMass) || in_array($notary['id'], $fnia)) {
            $grpID = array_intersect($group,$grMass);
            if(empty($grpID)){
                $grpID='z';
            }
            $sql = "SELECT deal.adKom, deal.groupID, deal.cost, deal.finalCost, deal.serviceID, deal.dateOf, deal.timeOf, deal.transactionStatus, deal.place, se_services.name, deal.id as deID FROM deal 
                INNER JOIN se_services ON se_services.id=deal.serviceID 
                WHERE (deal.transactionStatus = 1) AND (deal.cost <> '') AND ((`groupID` LIKE '%{$grpID[0]}%')OR(`notaryList` LIKE '%{$notary['id']}%')) 
                ORDER BY deal.id DESC ";
            $result = $this->db->query($sql);
            return $result;
        }
        //return $fnia;
    }

    public function getGroup($data){
        $id = $this->db->escape($data['id']);
        $sql = "SELECT `groupID` FROM `groupsOfNotaries` WHERE `notaryID`='{$id}'";
        $result = $this->db->query($sql);
        if(isset($result)){
            return $result;
        }

    }

    public function takeDeal($id,$deal){
        $timeChange =  date("H:i:s");
        $dateChange = date("Y-m-d");
        $check = "select * from deal WHERE notaryID='0' AND id='{$deal}' ";
        $checkSql = $this->db->query($check);
        if(isset($checkSql[0])) {
            $sql = "update deal 
        set notaryID='$id',
            transactionStatus = '3'
        WHERE id='$deal'";
            $result = $this->db->query($sql);
            if (isset($result)) {
                $sqlStat = "insert into `deal_state` set
                        dealID='{$deal}',
                        stat='3',
                         timeChange='$timeChange',
                         dateChange='$dateChange'";
                $result2 = $this->db->query($sqlStat);
                return $result2;
            }
        }
        else{
            $result = "Заказ был принят другим нотариусом!";
            return $result;
        }
        //return $checkSql[0];
    }
    public function getDealByID($id){
        $sql = "SELECT deal.notaryID, deal.cost, deal.finalCost, deal.serviceID, deal.dateOf, deal.timeOf, deal.transactionStatus, deal.place,
 se_services.name,deal.id as deID, common_user.fName, common_user.sName, common_user.mName, notary.fName as notarfName ,
notary.sName as notarsName , notary.mName as notarmName, notary.userID as notarID, deal.userID, common_user.mainPhone, deal.groupID, deal.adKom, deal.notaryList
FROM deal 
INNER JOIN se_services ON se_services.id=deal.serviceID
INNER JOIN common_user ON deal.userID=common_user.userID
LEFT JOIN notary ON (deal.notaryID=notary.id) 
                WHERE deal.id = '$id'";
        $result = $this->db->query($sql);
        if (isset($result)) {
            return $result[0];
        }
    }
    public function getDealByIDall($id){
        $sql = "SELECT  notary.fName as fNA, notary.sName as sNA, notary.mName as mNA, notary.login, common_user.fName, common_user.sName, common_user.mName, common_user.mainPhone,  deal.groupID, deal.cost, deal.finalCost, 
                deal.serviceID, deal.dateOf, deal.timeOf, deal.transactionStatus, deal.place, se_services.name, deal.id as deID, notary.userID as notaryID, deal.userID
                FROM deal 
                INNER JOIN se_services ON se_services.id=deal.serviceID 
                INNER JOIN common_user ON common_user.userID=deal.userID 
                INNER JOIN notary ON notary.id=deal.notaryID 
                WHERE deal.id = '$id'";
        $result = $this->db->query($sql);
        if (isset($result)) {
            return $result[0];
        }
    }
    public function judasCheck($id){
        $sql = "select notaryID from `deal` WHERE id='{$id}'";
        $result = $this->db->query($sql);
        if (isset($result)) {
            return $result[0];
        }
    }

    public function getUserDeal($id){
        $sql = "SELECT notary.geolocation, notary.street, notary.house, deal.onlinePayment, deal.livePayment,
                deal.notaryID,deal.groupID, deal.cost, deal.finalCost, deal.serviceID, deal.dateOf, deal.timeOf, 
                deal.transactionStatus, deal.place, concat(notary.fName,' ',notary.sName,' ',notary.mName) as nFio,
                se_services.name, deal.id as deID, notary.userID as nID , deal.onlinePayment, deal.livePayment
                FROM deal 
                LEFT JOIN se_services ON se_services.id=deal.serviceID 
                LEFT JOIN notary ON notary.id=deal.notaryID 
                WHERE deal.userID = '$id' AND (deal.transactionStatus='3' OR deal.transactionStatus='4')";
        $result = $this->db->query($sql);
        if (isset($result)) {
            return $result;
        }
    }
    public function getUserAll($id){
        $sql = "SELECT deal.onlinePayment, deal.livePayment, deal.notaryID,deal.groupID, deal.cost, deal.finalCost, deal.serviceID, deal.dateOf, 
                deal.timeOf, deal.transactionStatus, deal.place, deal.userComment,
                se_services.name, deal.id as deID, deal.userID 
                FROM deal INNER JOIN se_services ON se_services.id=deal.serviceID WHERE deal.userID = '$id' ";
        $result = $this->db->query($sql);
        if (isset($result)) {
            return $result;
        }
    }
    public function getDealForNotary($id){
        $sql = "SELECT deal.onlinePayment, deal.livePayment, deal.groupID, deal.cost, deal.finalCost, deal.serviceID, deal.dateOf, deal.timeOf, deal.transactionStatus, deal.place, se_services.name, deal.id as deID FROM deal 
                INNER JOIN se_services ON se_services.id=deal.serviceID WHERE deal.notaryID = '$id'";
        $result = $this->db->query($sql);
        if (isset($result)) {
            return $result;
        }
    }
    public function updStatDeal($data,$id){
        $timeChange =  date("H:i:s");
        $dateChange = date("Y-m-d");
        $sql = "UPDATE deal SET transactionStatus='{$data['state']}' WHERE id='{$id}'";
        $result = $this->db->query($sql);
        if (isset($result)) {
            $sqlStat = "insert into `deal_state` set
                        dealID='{$id}',
                        stat='{$data['state']}',
                         timeChange='$timeChange',
                         dateChange='$dateChange'";
            $result2 = $this->db->query($sqlStat);
            return $result2;
        }
    }
    /**/
    public function getStateDealbyID($id){
        $sql = "select timeChange, dateChange, stat from `deal_state` WHERE dealID='{$id}' ORDER BY id DESC ";
        $result = $this->db->query($sql);
        if (isset($result)) {
            return $result;
        }
    }
    /**/
    public function checkDeal($id){
        $sql = "select * from `deal` WHERE id='{$id}' AND notaryID='0'";
        $result = $this->db->query($sql);
        if (isset($result)) {
            return $result;
        }
    }
    public function getListDoneDeal($login){
        $sqlId = "SELECT id FROM `users` WHERE `login`='{$login}'";
        $resultID = $this->db->query($sqlId);
        if (isset($resultID)) {
            $sql = "select id from `deal` WHERE userID='{$resultID[0]['id']}' AND transactionStatus='4'";
            $result = $this->db->query($sql);
            if (isset($result)) {
                return $result;
            }
        }

    }
    public function getCurDoneDeal($id){
        $sql = "SELECT deal.notaryID, notary.fName, notary.sName, notary.mName, deal.rating, notary.login, deal.userComment, notary.userID as noID
                FROM `deal` LEFT JOIN notary ON deal.notaryID=notary.id WHERE deal.id='{$id}'";
        $result = $this->db->query($sql);
        if (isset($result)) {
            return $result;
        }
    }
    public function editDealRating($data,$id){
        $sql = "update `deal` set
                rating='{$data['rating']}',
                userComment='{$data['userComment']}'
                WHERE id='{$id}'";
        $result = $this->db->query($sql);
        if (isset($result)) {
            $z = "select notaryID from `deal` WHERE id='{$id}'";
            $zz = $this->db->query($z);
            $q ="select avg(rating), COUNT(adKom) from `deal` WHERE notaryID='{$zz[0]['notaryID']}' AND transactionStatus='4'";
            $qq = $this->db->query($q);
            $t = "update `notary` set
                  rating='{$qq[0]['avg(rating)']}',
                  couCom='{$qq[0]['COUNT(adKom)']}'
                  WHERE id='{$zz[0]['notaryID']}'";
            $tt = $this->db->query($t);
            return $result;
        }
    }
    public function getDealsByNotary($login){
        $sqlId = "SELECT notary.id FROM `notary`
                  LEFT JOIN `users` ON notary.login=users.login
                  WHERE users.login='{$login}'";
        $resultID = $this->db->query($sqlId);
        if (isset($resultID)) {
            $sql = "select id from `deal` WHERE notaryID='{$resultID[0]['id']}'";
            $result = $this->db->query($sql);
            if (isset($result)) {
                return $result;
            }
        }
    }

    public function updPayStat($id,$sum){
        $sql = "update `deal` set
                  transactionStatus='4',
                  onlinePayment='1',
                  paymentSum='{$sum}'
                  WHERE id='{$id}'";
        //return $sql;
        $result = $this->db->query($sql);
        if ($result) {
            return $result;
        }
    }

    public function addPayment($data){
        $dataPayment = date("Y-m-d");

        $userID = $this->db->escape($data['userID']);
        $notaryID = $this->db->escape($data['notaryID']);
        $serviceID = $this->db->escape($data['serviceID']);
        $cost = $this->db->escape($data['cost']);

        $sql = "INSERT INTO `payments` SET
                dateTransaction = '{$dataPayment}',
                userID = '{$userID}',
                notaryID = '{$notaryID}',
                serviceID = '{$serviceID}',
                cost = '{$cost}'
            ";

        $result = $this->db->query($sql);
        if ($result) {
            return $result;
        }
    }
}