<?php
class Main extends Model{

    public function GetList()
    {
        $sql = "select * from listOfgroups";
        return $this->db->query($sql);
    }

    public function GetNotaryByLogin($data)
    {
        $sql = "select userID,email,fName,sName,mName,sex,license,dateOfRegLicense,aboutNotary,city,area,street,house,entrance,floor,room,doorphoneCode,
                metro,arrayName,geolocation,offiePhoto,poweredByExit,stWeekday,etWeekday,wSaturday,wSunday,wHolidays,stSaturday,etSaturday,stSunday,
                 etSunday,stHolidays,etHolidays,cityPhone,nameOfAssistant,assistantPhone,emailAssistant,video from notary WHERE login={$data}";
        $result = $this->db->query($sql);
        if(isset($result[0])) {
            return $result[0];
        }
    }
    public function GetUserByLogin($data)
    {
        $sql = "select common_user.userID, common_user.fName, common_user.sName, common_user.mainPhone,
                common_user.mName, common_user.email, users.role, common_user.sex, common_user.birthdayDate
                from common_user INNER JOIN users 
                WHERE users.login={$data}";
        $result = $this->db->query($sql);
        if(isset($result[0])) {
            return $result[0];
        }
    }

    public function addGroup($data){
        if (!isset($data['name']) || !isset($data['message'])) {
            return false;
        }
        $name = $this->db->escape($data['name']);
        $massage = $this->db->escape($data['message']);
            $sql = "insert into listOfgroups
                   set name = '{$name}',
                       descriptionAbout = '{$massage}'";
        return $this->db->query($sql);

    }
    public function updNotaryInfo($data,$log){
        $email = $this->db->escape($data['email']);
        $fName = $this->db->escape($data['fName']);
        $sName = $this->db->escape($data['sName']);
        $mName = $this->db->escape($data['mName']);
        $sex = $this->db->escape($data['sex']);

        $license = $this->db->escape($data['license']);
        $dateOfRegLicense = $this->db->escape($data['dateOfRegLicense']);
        $aboutNotary = $this->db->escape($data['aboutNotary']);
        $city = $this->db->escape($data['city']);
        $area = $this->db->escape($data['area']);

        $typeOfNotary = $this->db->escape($data['typeOfNotary']);
        $street = $this->db->escape($data['street']);
        $house = $this->db->escape($data['house']);
        $entrance = $this->db->escape($data['entrance']);
        $floor = $this->db->escape($data['floor']);
        $room = $this->db->escape($data['room']);
        $doorphoneCode = $this->db->escape($data['doorphoneCode']);

        $metro = $this->db->escape($data['metro']);
        $arrayName = $this->db->escape($data['arrayName']);
        $geolocation = $this->db->escape($data['geolocation']);
        $offiePhoto = $this->db->escape($data['offiePhoto']);
        $poweredByExit = $this->db->escape($data['poweredByExit']);

        $stWeekday = $this->db->escape($data['stWeekday']);
        $etWeekday = $this->db->escape($data['etWeekday']);
        $wSaturday = $this->db->escape($data['wSaturday']);
        $wSunday = $this->db->escape($data['wSunday']);
        $wHolidays = $this->db->escape($data['wHolidays']);

        $stSaturday = $this->db->escape($data['stSaturday']);
        $etSaturday = $this->db->escape($data['etSaturday']);
        $stSunday = $this->db->escape($data['stSunday']);
        $etSunday = $this->db->escape($data['etSunday']);
        $stHolidays = $this->db->escape($data['stHolidays']);

        $etHolidays = $this->db->escape($data['etHolidays']);
        $cityPhone = $this->db->escape($data['cityPhone']);
        $nameOfAssistant = $this->db->escape($data['nameOfAssistant']);
        $assistantPhone = $this->db->escape($data['assistantPhone']);
        $emailAssistant = $this->db->escape($data['emailAssistant']);

        $video = $this->db->escape($data['video']);


        $sql = "update notary
        set email='$email', fName='$fName', sName='$sName', mName='$mName', sex='$sex',
        license='$license', dateOfRegLicense='$dateOfRegLicense', aboutNotary='$aboutNotary', city='$city', area='$area',
        street='$street' ,house='$house', entrance='$entrance', floor='$floor', room='$room', doorphoneCode='$doorphoneCode',
        metro='$metro', arrayName='$arrayName', geolocation='$geolocation', offiePhoto='$offiePhoto', poweredByExit='$poweredByExit',
        stWeekday='$stWeekday', etWeekday='$etWeekday', wSaturday='$wSaturday', wSunday='$wSunday', wHolidays='$wHolidays',
        stSaturday='$stSaturday', etSaturday='$etSaturday', stSunday='$stSunday', etSunday='$etSunday', stHolidays='$stHolidays',
        etHolidays='$etHolidays', cityPhone='$cityPhone', nameOfAssistant='$nameOfAssistant', assistantPhone='$assistantPhone', emailAssistant='$emailAssistant',
        video='$video', typeOfNotary='$typeOfNotary'
         WHERE login={$log}";
        $result = $this->db->query($sql);
        if(isset($result)) {
            return $result;
        }
    }
    public function addServNotary($data,$userID){
        //$service = $this->db->escape($data['addServNotary']);
        $sqL = 'INSERT INTO `no_serv_list` ( notaryID, serviceID ) VALUES ';
        foreach ($data['addServNotary'] as $item){
            $sqlCheckNotary = "select * from `no_serv_list` where notaryID = '{$userID}' AND serviceID = '{$item}' limit 1";
            $result = $this->db->query($sqlCheckNotary);
            if (isset($result[0]) ){
                //return false;
                $sqL = $sqL."";
            }
            else{
                $sqL = $sqL." ({$userID}, {$item}),";
            }
        }
        $trimmed = substr($sqL,0, -1);
        return $trimmed;
    }

    public function addServicesList($sqL){
       //return $sqL;

        $resultFin = $this->db->query($sqL);
        if(isset($resultFin)){
            return $resultFin;
        }
    }

    public function getListServices($id){
        $sql = "select se_services.name, se_services.id, no_serv_list.costService, no_serv_list.costAService, no_serv_list.id as idDell  from se_services
                 INNER JOIN no_serv_list ON no_serv_list.serviceID = se_services.id  where notaryID={$id}";
        $result = $this->db->query($sql);
        if ( isset($result) ){
            return $result;
        }
    }
    public function dellID($id){
        $sql = "delete from `no_serv_list` WHERE id = $id";
        $result = $this->db->query($sql);
        if ( isset($result) ){
            return $result;
        }
    }

    public function getServices(){
        $sql = "select * from se_services WHERE `top`='1'";
        $result = $this->db->query($sql);
        if ( isset($result) ){
            return $result;
        }
    }
    public function mainFastRegUser($user,$token){
        $login = $this->db->escape($user);
        $password = md5(Config::get('salt') . $user);
        $today = date("Y-m-d");
        $sql = "
                insert into users
                   set login = '{$login}',
                       regDt = '{$today}',
                       password = '{$password}',
                       token = '{$token}',
                       role = 'user'
            ";
        $result = $this->db->query($sql);
        if ( isset($result) ){
            $takeIdByUser = "select id from users where login = '$login' limit 1";
            $userID = $this->db->query($takeIdByUser);

            $addToUserTable =  "insert into common_user
                  set mainPhone = '{$login}',
                  userID = '{$userID[0]['id']}'";
            return $this->db->query($addToUserTable);
        }
    }

    public function fastDeal($user,$data){
        $appTime = date("H:i:s");
        $appDate = date("Y-m-d");
        $takeIdByUser = "select id from users where login = '$user' limit 1";
        $userID = $this->db->query($takeIdByUser);

        $service = $this->db->escape($data['service']);
        $date = $this->db->escape($data['dateOf']);
        $time = $this->db->escape($data['timeOf']);
        $homeCheck = 0 ;
        $status = 1;
        if(!empty($data['homeCheck'])){
            $homeCheck = 1;
        }


        $sql = "insert into `deal`
                set
                userID = {$userID[0]['id']},
                serviceID='{$service}',
                dateOf='{$date}',
                timeOf='{$time}',
                place='{$homeCheck}',
                transactionStatus='{$status}',
                appTime='{$appTime}',
                appDate='$appDate'";
        $result = $this->db->query($sql);
        if(isset($result)){
            return $result;
        }

    }
    public function smsInfo($tel){
        try {
            // Подключаемся к серверу
            $client = new SoapClient('http://turbosms.in.ua/api/wsdl.html');
            // Данные авторизации
            $auth = [
                'login' => 'zavireno',
                'password' => 'zavireno99'
            ];
            // Авторизируемся на сервере
            $result = $client->Auth($auth);

            // Результат авторизации
            // echo $result->AuthResult . PHP_EOL;

            // Получаем количество доступных кредитов
            $result = $client->GetCreditBalance();
            // echo $result->GetCreditBalanceResult . PHP_EOL;

            // Текст сообщения ОБЯЗАТЕЛЬНО отправлять в кодировке UTF-8
            $text = iconv('utf-8', 'utf-8', 'Ваш заказ оформлен!');
            $sms = [
                'sender' => 'zavireno24',
                'destination' => $tel,
                'text' => $text
            ];
            $result = $client->SendSMS($sms);
           // print_r($result);
        } catch (Exception $e) {
            //echo 'Ошибка: ' . $e->getMessage() . PHP_EOL;
            Session::setFlash('Ошибка отправки смс с доступом.');
        }
    }

    public function getNotariesCount(){
        $sql = "SELECT COUNT(id) as count FROM `notary`";
        $result = $this->db->query($sql);
        if($result){
            return $result;
        }
    }

    public function getServicesCount(){
        $sql = "SELECT COUNT(id) as count FROM `se_services`";
        $result = $this->db->query($sql);
        if($result){
            return $result;
        }
    }

    public function getNotaryList(){
        $sql = "SELECT notary.login, notary.fName, notary.sName, notary.mName, notary.rating, notary.poweredByExit, notary.profPhoto, lo_metro.metroName, notary.userID
                FROM `notary`
                LEFT  JOIN `lo_metro` ON notary.metro=lo_metro.id";
        $result = $this->db->query($sql);
        if($result){
            return $result;
        }
    }

    public function getMyNotary($id){
        $sql = "SELECT concat(notary.fName,' ',notary.sName,' ',notary.mName) as nFio, notary.profPhoto, notary.rating,
                se_services.name, deal.cost, lo_metro.metroName, deal.dateOf, notary.login, deal.id as dID
                FROM `deal`
                LEFT JOIN `notary` ON deal.notaryID = notary.id
                LEFT JOIN `common_user` ON deal.userID = common_user.userID
                LEFT JOIN `se_services` ON deal.serviceID = se_services.id
                LEFT JOIN `lo_metro` ON notary.metro = lo_metro.id
                WHERE common_user.userID = '$id' AND deal.transactionStatus='4'";
        $result = $this->db->query($sql);
        if($result){
            return $result;
        }
    }

    function actionDeal($data,$login){
        $getUserID = "SELECT users.id
                FROM `users` WHERE login='$login'";
        $res = $this->db->query($getUserID);

        if(!empty($data['homeCheck'])){
            $homeCheck = 1;
        }
        else{
            $homeCheck = 0;
        }
        $sql = "insert into `deal`
                set
                userID = {$res[0]['id']},
                serviceID='{$data['service']}',
                cost='{$data['cost']}',
                finalCost='{$data['fCost']}',
                notaryID='{$data['notaryID']}',
                dateOf='{$data['dateOf']}',
                timeOf='{$data['timeOf']}',
                place='{$homeCheck}',
                transactionStatus='3',
                specialStatus='1'";

        $result = $this->db->query($sql);
        if($result){
            return $result;
        }
    }

    public function registerUser($data, $id = null, $token, $role){
        $today = date("Y-m-d");
        if (!isset($data)) {
            return false;
        }
        $login = $this->db->escape($data);


        $sqlCheckNotary = "select * from `users` where login = '{$login }' limit 1";
        $result = $this->db->query($sqlCheckNotary);
        if (isset($result[0]) ){
            return false;
        }

        $id = (int)$id;
        $passwordClean = $this->db->escape($data);
        $password = md5(Config::get('salt').$passwordClean);

        if (!$id) { // Add new record
            $sql = "
                insert into users
                   set login = '{$login}',
                       password = '{$password}',
                       role = '{$role}',
                       token = '{$token}',
                       regDt = '{$today}'
            ";
        } else { // Update existing record
            $sql = "
                update users
                   set login = '{$login}',
                       password = '{$password}',
                   where id = {$id}
            ";
        }
            if($role=='user'){
            $this->db->query($sql);

            $takeIdByNotary = "select id from users where login = '$login' limit 1";
            $userID = $this->db->query($takeIdByNotary);

            $addToUserTable =  "insert into common_user
                  set mainPhone = '{$login}',
                  userID = '{$userID[0]['id']}'";
            return $this->db->query($addToUserTable);
        }
        else{
            return  $this->db->query($sql);
        }



    }


}