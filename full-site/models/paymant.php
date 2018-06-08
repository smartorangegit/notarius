<?php

class Payments extends Model
{
    public function getListPayments(){
        $sql = "SELECT concat(common_user.fName,' ',common_user.sName,' ',common_user.mName) as uFio,
                common_user.mainPhone, payments.dateTransaction, payments.cost,
                se_services.name, concat(notary.fName,' ',notary.sName,' ',notary.mName) as nFio,
                notary.login,
                payments.id
                FROM `payments`
                LEFT JOIN common_user ON payments.userID = common_user.userID
                LEFT JOIN notary ON payments.notaryID = notary.id
                LEFT JOIN se_services ON payments.serviceID = se_services.id";
        $result = $this->db->query($sql);
        if($result){
            return $result;
        }
    }
}