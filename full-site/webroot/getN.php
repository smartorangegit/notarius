<?php
$msg_info = array();
$conditions = [];
$where = [];
if(($_POST['fio'] =='') && ($_POST['limX'] == '') && ($_POST['limY']== '')){
    $where[] = 1;
}
$status = 'Success';
try {
    $dbh = new PDO('mysql:host=customsh.mysql.tools;dbname=customsh_notar', 'customsh_notar', '2yud9j7w');
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh -> exec("SET CHARACTER SET utf8");
    if (!empty($_POST['fio'])) {
        $where[0] = "(fName LIKE '%" . $_POST['fio'] . "%' OR sName LIKE '%" . $_POST['fio'] . "%' OR mName LIKE '%" . $_POST['fio'] . "%' )";
    }
    if(!empty($_POST['area'])) {
        //$where[1] = "area LIKE '%" . $_POST['area'] . "%'";
        if($_POST['area']==0){
            $where[1] = '';
        }
        $where[1] = "(area = '" . $_POST['area'] . "')";
    }
    if(!empty($_POST['week']) || (empty($_POST['vskr']) && empty($_POST['sb']))) {
        if (!empty($_POST['limX']) && !empty($_POST['limY'])) {
            $where[2] = "(stWeekday <= '" . $_POST['limX'] . "' AND etWeekday >= '" . $_POST['limY'] . "')";
        }
    }
    if(!empty($_POST['sb'])) {
        if (!empty($_POST['limX']) && !empty($_POST['limY'])) {
            $where[3] = "(stSaturday <= '" . $_POST['limX'] . "' AND etSaturday >= '" . $_POST['limY'] . "')";
        }
    }
    if(!empty($_POST['vskr'])) {
        if (!empty($_POST['limX']) && !empty($_POST['limY'])) {
            $where[4] = "(stSunday <= '" . $_POST['limX'] . "' AND etSunday >= '" . $_POST['limY'] . "')";
        }
    }
    $query = "SELECT * FROM `notary`  WHERE ".implode(" AND ",$where);
    $usr = $dbh->prepare($query);
    $usr->execute($conditions);

    $it = 0;
    while($user = $usr->fetch()) {
        $msg_info[]=$user;
        echo "<option value='".$user['id']."'>".$user['fName'].' '.$user['sName'].' '.$user['mName'].' '."</option>";
        $it++;
    }



} catch (PDOException $e) {
    $status = 'Fail: ' . $e->getMessage();
    //print_r($query);
}
$data = array(
    'msg_info' => $msg_info,
    'status' => $status
);
//print_r($data);
//echo '<br>';
print_r($_POST);
print_r($query);