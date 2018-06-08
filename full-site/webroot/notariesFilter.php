<?php
if(empty($_POST['page']) || !$_POST['page'] || $_POST['page']==1){
    $lA = 0;
    $lB = 2;
}
else{
    $lA = $_POST['page'];
    $lB = $lA+1;
}
$msg_info = array();
$conditions = [];
$status = 'Success';
$inner = '';

/*если в фильтре ничего нет вывод первых 9 с базы*/
if(empty($_POST['fio']) && empty( $_POST['serviceName']) && empty($_POST['metro']) && empty($_POST['street']) && empty($_POST['area']) && empty($_POST['workTimeS']) &&
  empty($_POST['workTimeF']) && empty($_POST['stWorkS']) && empty($_POST['stWorkF']) && empty($_POST['saWorkS']) && empty($_POST['saWorkF']) && empty($_POST['costS'] )
    && empty($_POST['costF']) && ($_POST['typeOfNotary']=='') && empty($_POST['ratingMin']) && empty($_POST['ratingMax']) && empty($_POST['notary'])
    && empty($_POST['poweredByExit'])){
    $where[] = 1;
    $inner = "INNER JOIN  `lo_metro` ON notary.metro=lo_metro.id ";
}
if(!empty($_POST['fio']) ){
    $where[0] = "(fName LIKE '%" . $_POST['fio'] . "%' OR sName LIKE '%" . $_POST['fio'] . "%' OR mName LIKE '%" . $_POST['fio'] . "%' )";
    $whereCount[0] = "(fName LIKE '%" . $_POST['fio'] . "%' OR sName LIKE '%" . $_POST['fio'] . "%' OR mName LIKE '%" . $_POST['fio'] . "%' )";
    $inner = "LEFT JOIN  `lo_metro` ON notary.metro=lo_metro.id ";
}
if(!empty($_POST['serviceName']) ){
    $whereCount[] = 1;
    $where[1] = '(no_serv_list.serviceID  = '.$_POST['serviceName'].')';
    $inner = "INNER JOIN  `no_serv_list` ON no_serv_list.notaryID=notary.userID INNER JOIN  `lo_metro` ON notary.metro=lo_metro.id ";
}
if(!empty($_POST['metro'])){
    $where[2] = "metro = ".$_POST['metro'];
    $inner = "LEFT JOIN  `lo_metro` ON notary.metro=lo_metro.id ";
}
if(!empty($_POST['street'])){
    $where[3] = "street LIKE '%" . $_POST['street'] . "%'";
    $inner = "LEFT JOIN  `lo_metro` ON notary.metro=lo_metro.id ";
}
if(!empty($_POST['area'])){
    $where[4] = "area = ".$_POST['area'];
    $inner = "LEFT JOIN  `lo_metro` ON notary.metro=lo_metro.id ";
}
if(!empty($_POST['workTimeS']) || !empty($_POST['workTimeF'])){
    $workTimeS = $_POST['workTimeS'];
    $workTimeF = $_POST['workTimeF'];
    if($_POST['workTimeS']==''){
        $workTimeS =  $_POST['workTimeF'];
    }
    if($_POST['workTimeF']==''){
        $workTimeF =  $_POST['workTimeS'];
    }
    $where[5] = "(stWeekday <= '" . $workTimeS . "' AND etWeekday >= '" . $workTimeF . "')";
    $inner = "LEFT JOIN  `lo_metro` ON notary.metro=lo_metro.id ";
}
if(!empty($_POST['stWorkS']) || !empty($_POST['stWorkF'])){
    $stWorkS = $_POST['stWorkS'];
    $stWorkF = $_POST['stWorkF'];
    if($_POST['stWorkS']==''){
        $stWorkS =  $_POST['stWorkF'];
    }
    if($_POST['stWorkF']==''){
        $stWorkF =  $_POST['stWorkS'];
    }
    $where[6] = "(stSaturday <= '" . $stWorkS . "' AND etSaturday >= '" . $stWorkF . "')";
    $inner = "LEFT JOIN  `lo_metro` ON notary.metro=lo_metro.id ";
}
if(!empty($_POST['saWorkS']) || !empty($_POST['saWorkF'])){
    $saWorkS = $_POST['saWorkS'];
    $saWorkF = $_POST['saWorkF'];
    if($_POST['saWorkS']==''){
        $saWorkS =  $_POST['saWorkF'];
    }
    if($_POST['saWorkF']==''){
        $saWorkF =  $_POST['saWorkS'];
    }
    $where[7] = "(stSunday <= '" . $saWorkS . "' AND etSunday >= '" . $saWorkF . "')";
    $inner = "LEFT JOIN  `lo_metro` ON notary.metro=lo_metro.id ";
}
/*иф по стоимости*/
if(($_POST['typeOfNotary']) != ''){
    $where[9] = "typeOfNotary = ".$_POST['typeOfNotary'];
    $inner = "LEFT JOIN  `lo_metro` ON notary.metro=lo_metro.id ";
}
if(!empty($_POST['ratingMin']) && !empty($_POST['ratingMax'])){
    $where[10] = "(rating BETWEEN '" . $_POST['ratingMin'] . "' AND '" . $_POST['ratingMax'] .'.01'. "')";
    $inner = "LEFT JOIN  `lo_metro` ON notary.metro=lo_metro.id ";
}
if(!empty($_POST['notary'])){
    $where[11] = "id = ".$_POST['notary'];
    $inner = "LEFT JOIN  `lo_metro` ON notary.metro=lo_metro.id ";
}
if(!empty($_POST['costS'])){
    $costS = $_POST['costS'];
    $costF = $_POST['costF'];
    if($_POST['costS']==''){
        $costS =  $_POST['costF'];
    }
    if($_POST['costF']==''){
        $costF =  $_POST['costS'];
    }
    $where[12] = "(no_serv_list.fullSumServ >= '" . $costS . "' AND no_serv_list.fullSumServ <= '" . $costF . "')";
    $inner = "INNER JOIN  `no_serv_list` ON no_serv_list.notaryID=notary.userID LEFT JOIN  `lo_metro` ON notary.metro=lo_metro.id ";
}
if(($_POST['poweredByExit']) != ''){
    $where[13] = "notary.poweredByExit = ".$_POST['poweredByExit'];
    $inner = "LEFT JOIN  `lo_metro` ON notary.metro=lo_metro.id ";
}

try {
    $dbh = new PDO('mysql:host=customsh.mysql.tools;dbname=customsh_notar', 'customsh_notar', '2yud9j7w');
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->exec("SET CHARACTER SET utf8");

    $query = "SELECT distinct notary.userID, notary.profPhoto, notary.fName, notary.sName, notary.mName, notary.login, notary.rating,  notary.geolocation, notary.couCom, lo_metro.metroName
              FROM `notary` ".$inner
              ." WHERE ".implode(" AND ",$where)." LIMIT $lA,$lB";

    $usr = $dbh->prepare($query);
    $usr->execute($conditions);
    $user = $usr->fetchAll();
    $msg_info = $user;

    //print_r($query);

}
catch (PDOException $e){
    $data = array(
        'msg_info' => $msg_info,
        'status' => $status,
        'minA' =>  $lA,
        'minB' => $lB,
        'query' => $query,
        'post' => $_POST
    );
    $status = 'Fail: ' . $e->getMessage();
}

$data = array(
    'msg_info' => $msg_info,
    'status' => $status,
    'minA' =>  $lA,
    'minB' => $lB,
    'query' => $query,
    'post' => $_POST
);
echo json_encode($data);
