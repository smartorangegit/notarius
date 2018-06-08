<?php

session_start();

$areas = array();
$status = 'Success';

$id  = $_POST['id'];
$notary = $_POST['notary'];
try {
    $dbh = new PDO('mysql:host=customsh.mysql.tools;dbname=customsh_notar','customsh_notar','2yud9j7w');
    $dbh -> exec("SET CHARACTER SET utf8");
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sth = $dbh->query("select se_services.name, se_services.id , category_services.servicesID, no_serv_list.costService
from se_services
LEFT JOIN category_services ON  se_services.id = category_services.servicesID
LEFT JOIN no_serv_list ON se_services.id = no_serv_list.serviceID
where category_services.categoryID='$id' AND no_serv_list.notaryID='$notary'");

    $list = $sth->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $status = 'Fail: ' . $e->getMessage();
}

$data = array(
    'SList' => $list,
    'status' => $status
);
echo json_encode($data);
//print_r($sth);
