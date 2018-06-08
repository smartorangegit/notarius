<?php

class Rating extends Model
{
    public function getRatingList($id){
        $sql = "SELECT deal.rating, deal.userComment, se_services.name, common_user.fName, common_user.sName, common_user.mName,
                notary.fName as nfName, notary.sName as nsName, notary.mName as nmName, deal.id, deal.userID, notary.userID as notaryID,
                deal.dateOf
                FROM `deal` 
                LEFT JOIN `notary` ON deal.notaryID=notary.id
                LEFT JOIN `common_user`ON deal.userID=common_user.userID
                LEFT JOIN `se_services` ON deal.serviceID=se_services.id
                WHERE deal.transactionStatus='4' AND notary.userID='$id'";
        $result = $this->db->query($sql);
        if($result){
            return $result;
        }
    }
}