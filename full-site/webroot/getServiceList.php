<?php

session_start();

$areas = array();
$status = 'Success';
$id  = str_replace('id-','',$_POST['catID']);
try {
    $dbh = new PDO('mysql:host=customsh.mysql.tools;dbname=customsh_notar','customsh_notar','2yud9j7w');
    $dbh -> exec("SET CHARACTER SET utf8");
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sth = $dbh->query("select se_services.name, se_services.id from se_services
                                  INNER JOIN category_services ON category_services.servicesID = se_services.id  where categoryID={$id}");

    $list = $sth->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $status = 'Fail: ' . $e->getMessage();
}

$data = array(
    'SList' => $list,
    'status' => $status
);
echo json_encode($data);
//print_r($data);
