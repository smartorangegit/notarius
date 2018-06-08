<?php
session_start();
if($_SESSION['role']=='admin') {
    $msg_info = array();
    $status = 'Success';

    try {
        $dbh = new PDO('mysql:host=customsh.mysql.tools;dbname=customsh_notar', 'customsh_notar', '2yud9j7w');
        $dbh->exec("SET CHARACTER SET utf8");
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sth = $dbh->query("SELECT common_user.fName, common_user.sName, common_user.mName, users.login as userName, deal.cost, deal.finalCost, deal.serviceID, deal.dateOf, deal.timeOf, deal.transactionStatus, deal.place, se_services.name, 
                                  deal.id as deID, deal.appTime, deal.appDate, deal.reacTime, deal.reacDate 
                                  FROM deal 
                                  INNER JOIN se_services ON se_services.id=deal.serviceID 
                                  INNER JOIN users ON users.id=deal.userID
                                  INNER JOIN common_user ON common_user.userID=deal.userID 
                                  WHERE  deal.transactionStatus= '1' ORDER BY deal.id DESC");
        /*
        $sth = $dbh->query("SELECT deal.cost, deal.finalCost, deal.serviceID, deal.dateOf, deal.timeOf, deal.transactionStatus, deal.place, se_services.name,
                                  deal.id as deID FROM deal INNER JOIN se_services ON se_services.id=deal.serviceID WHERE deal.transactionStatus= '1'");
        */
        $msg_info = $sth->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $status = 'Fail: ' . $e->getMessage();
    }

    $ids = [];

    foreach ($msg_info as $item) {
        $ids[] = $item['deID'];
        $_SESSION['lng'] = count($ids);
    }

    if (array_diff($ids, $_SESSION['ids'])) {
        $_SESSION['ids'] = $ids;
        $change = 1;
    } else {
        $_SESSION['ids'] = $ids;
        if ($_SESSION['lng'] != count($ids)) {
            $change = 1;
        }
        $change = 0;

    }
    $data = array(
        'msg_info' => $msg_info,
        'massDeals' => $ids,
        'change' => $change,
        'status' => $status
    );
    echo json_encode($data);
}
