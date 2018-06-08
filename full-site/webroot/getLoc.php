<?php

session_start();

$areas = array();
$status = 'Success';
$id = $_POST['id'];
try {
    $dbh = new PDO('mysql:host=customsh.mysql.tools;dbname=customsh_notar','customsh_notar','2yud9j7w');
    $dbh -> exec("SET CHARACTER SET utf8");
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sth = $dbh->query("SELECT lo_city_area_link.areaID, lo_area.areaName, lo_area.id
                                  FROM lo_city_area_link 
                                  INNER JOIN lo_area ON lo_city_area_link.areaID=lo_area.id WHERE lo_city_area_link.cityID='$id'");

    $sth2 = $dbh->query("SELECT lo_city_mArea_link.mAreaID, lo_mArea.mAreaName, lo_mArea.id
                                  FROM lo_city_mArea_link 
                                  INNER JOIN lo_mArea ON lo_city_mArea_link.mAreaID=lo_mArea.id WHERE lo_city_mArea_link.cityID='$id'");

    $sth3 = $dbh->query("SELECT lo_city_metro_link.metroID, lo_metro.metroName, lo_metro.id
                                  FROM lo_city_metro_link 
                                  INNER JOIN lo_metro ON lo_city_metro_link.metroID=lo_metro.id WHERE lo_city_metro_link.cityID='$id'");

    $areas = $sth->fetchAll(PDO::FETCH_ASSOC);
    $mAreas = $sth2->fetchAll(PDO::FETCH_ASSOC);
    $metro = $sth3->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $status = 'Fail: ' . $e->getMessage();
}

$data = array(
    'areas' => $areas,
    'mAreas' => $mAreas,
    'metro' => $metro,
    'status' => $status
);
echo json_encode($data);
//print_r($data);
