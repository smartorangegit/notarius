<?php
class Review extends Model
{
    public function getComments($arrayDeals){
        $list[] = '';
        foreach ($arrayDeals as $key => $item){
            $list[$key] = $item['id'];
        }
        return $list;
    }

    public function getList($list){
        $sqL = " SELECT deal.userComment, deal.rating, deal.dateOf, concat(notary.fName,' ',notary.sName,' ',notary.mName) as nFio,
                 notary.rating as nRank, notary.profPhoto, deal.id, se_services.name, notary.login
                 FROM `deal`
                 LEFT JOIN notary ON deal.notaryID = notary.id
                 LEFT JOIN se_services ON deal.serviceID = se_services.id
                 WHERE ";
        foreach ($list as $item){
            if (isset($result[0]) ){
                $sqL = $sqL."";
            }
            else{
                $sqL = $sqL." (deal.id = '$item') OR";
            }
        }
        $trimmed = substr($sqL,0, -2);
        //return $trimmed;
        $result = $this->db->query($trimmed);
        if($result){
            return $result;
        }
    }

}