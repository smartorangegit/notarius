<?php
if($_POST['notaryL']){
    $_POST['notaryG']=0;
}
$status = 'Success';
$reacTime = date("H:i:s");
$reacDate = date("Y-m-d");

$timeChange =  date("H:i:s");
$dateChange = date("Y-m-d");
try {
    $dbh = new PDO('mysql:host=customsh.mysql.tools;dbname=customsh_notar','customsh_notar','2yud9j7w');
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh -> exec("SET CHARACTER SET utf8");

    $sql = "UPDATE deal SET cost=?, finalCost=?, transactionStatus=?, groupID=?, notaryList=?, adKom=?, reacTime=?, reacDate=? WHERE id=?";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([$_POST['sum'], $_POST['fSum'], 2, $_POST['notaryG'], $_POST['notaryL'], $_POST['adKom'], $reacTime, $reacDate, $_POST['deID']]);
    $sql2 = $dbh->query("insert into `deal_state` set
                        dealID='{$_POST['id']}',
                        stat='3',
                         timeChange='$timeChange',
                         dateChange='$dateChange'");
} catch (PDOException $e) {
    $status = 'Fail: ' . $e->getMessage();
}

echo 'заявка отправлена нотариусу';