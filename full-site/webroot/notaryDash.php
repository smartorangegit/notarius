<?php
$group = $_POST['grMass'];
$notary = $_POST['notaryID'];

$dbh = new PDO('mysql:host=customsh.mysql.tools;dbname=customsh_notar','customsh_notar','2yud9j7w');
$dbh -> exec("SET CHARACTER SET utf8");
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$sth0 = $dbh->query("select groupID from deal WHERE deal.transactionStatus = 2 ORDER BY id DESC");
$checkSql = $sth0->fetchAll(PDO::FETCH_ASSOC);
foreach ($checkSql as $itemG){
    $grMass[] = $itemG['groupID'];
}

if(empty($_POST['notaryList'])){
    //echo 'q';
}
else{

}

for($itz=0; $itz<count($_POST['notaryList']); $itz++) {
    if (stristr($_POST['notaryList'][$itz], ',') === FALSE) {
        //echo '"earth" не найдена в строке';
    } else {
        $nia[] = explode(',',$_POST['notaryList'][$itz]);
    }
}
if(empty($nia)||$nia==0) {
    $fnia = $_POST['notaryList'];
}
else{
    $fnia = $nia[0];
}
if(array_intersect($group,$_POST['grMass']) || in_array($notary['id'], $fnia)) {
$grpID = array_intersect($group,$_POST['grMass']);
if(empty($grpID)){
    $grpID='z';
    }
    $sth = $dbh->query("SELECT deal.notaryList, deal.adKom, deal.groupID, deal.cost, deal.finalCost, deal.serviceID, deal.dateOf, deal.timeOf, deal.transactionStatus, deal.place, se_services.name, deal.id as deID FROM deal 
                INNER JOIN se_services ON se_services.id=deal.serviceID 
                WHERE (deal.transactionStatus = 2) AND (deal.cost <> '')
                AND(
                ((`groupID` LIKE '%{$grpID[0]}%')OR(`notaryList` LIKE '%{$notary['id']}%')) 
                OR((`groupID` LIKE '%0%')AND (`notaryList` LIKE '%0%'))
                ) AND `notaryID` = 0
                ORDER BY deal.id DESC ");
    $msg_info = $sth->fetchAll(PDO::FETCH_ASSOC);
}

//echo "<pre>";
//print_r($msg_info);
//echo "</pre>";

if(empty($msg_info)){
    echo "<h4 class='section-heading client-page__section-heading сlient-page-orders__section-heading'>Доступных заказов нет!</h4>";
}
else {
    echo "<h4 class='section-heading client-page__section-heading сlient-page-orders__section-heading'>Доступные заказы</h4>";
    foreach ($msg_info as $item) {
        echo "
<div class='notary-page-orders notary-available-order'>
    <ul class='order-table-head order-table__row notary-available-order-thead'>
        <li class='order-table-head__item notary-available-order-thead-col-1'>услуга</li>
        <li class='order-table-head__item notary-available-order-thead-col-2'>дата</li>
        <li class='order-table-head__item notary-available-order-thead-col-3'>вермя</li>
        <li class='order-table-head__item notary-available-order-thead-col-4'>сумма</li>
        <li class='order-table-head__item notary-available-order-thead-col-5'>финальная сумма</li>
        <li class='order-table-head__item notary-available-order-thead-col-6'>на дом</li>
        <li class='order-table-head__item notary-available-order-thead-col-7'>комментарии</li>
        <li class='order-table-head__item notary-available-order-thead-col-8'></li>
    </ul>
    <div class='notary-page-orders-padding'>
    <div class='order-table-wrap notary-page-orders-scroll notary-available-order-scroll'>
            <form action='' method='post'>
                <div class='notary-available-order-tabel'>
                        <div class='order-table__cell order-table__cell_wide'>
                          <input type='text' id='servicesName' name='servicesName'  value='" . $item['name'] . "'  class='notary-available-order__input' readonly>
                          <input type='hidden' name='serviceID' id='serviceID' value='" . $item['serviceID'] . "'>
                          <input type='hidden' name='deID' id='deID' value='" . $item['deID'] . "'>
                        </div>
                        <div class='order-table__cell notary-available-order-body-middle'>
                          <input type='date' id='date' name='date' class='notary-available-order__input' value='" . $item['dateOf'] . "' readonly>
                        </div>
                        <div class='order-table__cell order-table__cell_narrow'>
                          <input type='time' id='time' name='time' class='notary-available-order__input' value='" . $item['timeOf'] . "' readonly>
                        </div>
                        <div class='order-table__cell order-table__cell_narrow'>
                          <input name='sum' id='sum' class='notary-available-order__input' type='number' value='" . $item['cost'] . "' readonly>
                        </div>
                        <div class='order-table__cell order-table__cell_narrow'>
                          <input name='fSum' id='fSum' class='notary-available-order__input' type='number' value='" . $item['finalCost'] . "' readonly>
                        </div>
                        <div class='order-table__cell order-table__cell_narrow'>
                          <input  name='homeCheck' id='homeCheck' class='table__checkbox-input'  value='" . $item['place'] . "' type='checkbox'>
                        </div>
                        <div class='order-table__cell order-table__cell_wide'>
                            <textarea readonly  class='notary-available-order__input notary-available-order__text-area' >" . $item['adKom'] . "</textarea>
                        </div>
                        <div class='order-table__cell order-table__cell_narrow'>
                          <input type='submit' class='site-button notary-available-order__button' value='GO'>
                      </div>
                    </div> 
            </form>
        </div> 
    </div>
    </div>



";
    }
}
?>


