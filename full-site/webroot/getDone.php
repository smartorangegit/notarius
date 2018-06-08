<?php

session_start();

$msg_info = array();
$status = 'Success';

try {
    $dbh = new PDO('mysql:host=customsh.mysql.tools;dbname=customsh_notar','customsh_notar','2yud9j7w');
    $dbh -> exec("SET CHARACTER SET utf8");
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sth = $dbh->query('SELECT deal.notaryID, deal.cost, deal.finalCost, deal.serviceID, deal.dateOf, deal.timeOf, deal.transactionStatus, deal.place,
 se_services.name,deal.id as deID, common_user.fName, common_user.sName, common_user.mName, notary.fName as notarfName ,
notary.sName as notarsName , notary.mName as notarmName, notary.userID as notarID
FROM deal 
INNER JOIN se_services ON se_services.id=deal.serviceID
INNER JOIN common_user ON deal.userID=common_user.userID
LEFT JOIN notary ON (deal.notaryID=notary.id) WHERE transactionStatus=4');
    //ORDER BY deal.id DESC LIMIT 1
    $msg_info = $sth->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $status = 'Fail: ' . $e->getMessage();
}

$data = array(
    'msg_info' => $msg_info,
    'status' => $status
);

echo "<h3 class='section-heading'>Таблица завершенных заявок</h3>";
echo "<div class='limiter'>";
echo "<div class='container-table100'>";
echo "<div class='wrap-table100'>";

echo "<div class='table100 ver6 m-b-110'>";
echo "<table data-vertable='ver6'>";
echo "<thead>";
echo "<tr class='row100 head'>";
echo "<th class='column100 column2' data-column='column2'>Пользователи</th>";
echo "<th class='column100 column3' data-column='column3'>Нотариус</th>";
echo "<th class='column100 column4' data-column='column4'>Услуга</th>";
echo "<th class='column100 column5' data-column='column5'>Цена</th>";
echo "<th class='column100 column6' data-column='column6'>Финальная цена</th>";
echo "<th class='column100 column7' data-column='column7'>Дата</th>";
echo "<th class='column100 column8' data-column='column8'>Время</th>";
echo "<th class='column100 column9' data-column='column9'>Статус</th>";
echo "<th class='column100 column10' data-column='column10'>Тип</th>";
echo "</tr>";
echo "</thead>";
echo "<tbody>";


//print_r($msg_info);
foreach ($msg_info as $item){ 
	echo "<tr class='row100'>";
    echo "<td class='column100 column1' data-column='column1'>";
    echo "<a class='cell__link' href='/admin/dashboard/done/".$item['deID']."'>";
    	echo "<object type=''><a class='cell_font_family' href='/admin/users/list_c_users/" . $item['userID'] . '/' . "'>" .' '.  $item['fName'] . ' ' . $item['sName'] . ' ' . $item['mName'] . "</a></object>";
    echo "</a>";
    echo "</td>";

    if(!empty($item['notarfName'])) {
        echo "<td class='column100 colum201 column2' data-column='column2'>";
    	echo "<a class='cell__link' href='/admin/dashboard/done/".$item['deID']."'>";
        	echo "<object type=''><a class='cell_font_family' href='/admin/users/list_notary/" . $item['notarID'] . '/' . "'>" .' '. $item['notarfName'] . ' ' . $item['notarsName'] . ' ' . $item['notarmName'] . "</a></object>";
        echo "</a>";
        echo '</td>';
    } else {
    	echo "<td class='column100 column2' data-column='column2'>";
    	echo "<a class='cell__link' href='/admin/dashboard/done/".$item['deID']."'><div style='width: 1px; height:20px'></div></a>";
    	echo "</td>";
    }

    echo "<td class='column100 column3' data-column='column3'>";
    echo "<a class='cell__link' href='/admin/dashboard/done/".$item['deID']."'>";
    echo      "<p class='cell_font_family'>".$item['name']."</p>";
    echo "</a>";
    echo "</td>";

    echo "<td class='column100 column4' data-column='column4'>";
    echo "<a class='cell__link' href='/admin/dashboard/done/".$item['deID']."'>";
    echo      "<p class='cell_font_family'>".$item['cost']."</p>";
    echo "</a>";
    echo "</td>";

    echo "<td class='column100 column5' data-column='column5'>";
    echo "<a class='cell__link' href='/admin/dashboard/done/".$item['deID']."'>";
    echo      "<p class='cell_font_family'>".$item['finalCost']."</p>";
    echo "</a>";
    echo "</td>";

    echo "<td class='column100 column6' data-column='column6'>";
    echo "<a class='cell__link' href='/admin/dashboard/done/".$item['deID']."'>";
    echo      "<p class='cell_font_family'>".$item['dateOf']."</p>";
    echo "</a>";
    echo "</td>";

    echo "<td class='column100 column7' data-column='column7'>";
    echo "<a class='cell__link' href='/admin/dashboard/done/".$item['deID']."'>";
    echo      "<p class='cell_font_family'>".$item['timeOf']."</p>";
    echo "</a>";
    echo "</td>";

    echo "<td class='column100 column8' data-column='column8'>";
    echo "<a class='cell__link' href='/admin/dashboard/done/".$item['deID']."'>";
    echo  "<p class='option cell_font_family' id='transactionStatus'>";
    	if($item['transactionStatus']==1){echo 'Новая';}
        elseif($item['transactionStatus']==2){echo 'Обработана';}
        elseif($item['transactionStatus']==3){echo 'Назначено';}
        elseif($item['transactionStatus']==4){echo 'Состоялась';}
        elseif($item['transactionStatus']==5){echo 'Отмена';}
        // echo ".$item['transactionStatus'].";
    echo  "</p>";
    echo "</a>";
    
    echo "</td>";

    echo "<td class='deal column100 column9' data-column='column9'>";      
    echo "<a class='cell__link' href='/admin/dashboard/done/".$item['deID']."'>";
    echo      "<div class='circle'></div>";
    echo "</a>";
    echo "</td>";
    echo "</tr>";
}

echo "</tbody>";
echo  "</table>";
echo  "</div>";
echo "</div>";
echo "</div>";
echo "</div>";

//echo json_encode($data);
?>


