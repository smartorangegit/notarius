<?php

class User extends Model {
    
    public function recovery($login){
        $sql = "select users.token from `users` 
                LEFT JOIN `notary` ON users.login=notary.login
                LEFT JOIN `common_user` ON  users.login=common_user.mainPhone
                where (notary.email = '{$login}' OR common_user.email = '{$login}' ) OR (users.login = '{$login}')";
        $result = $this->db->query($sql);
        if ( isset($result) ){
            $sqlRecoveryOf = "update `users`
                              set passwordRecovery ='1'
                              where token = '{$result[0]['token']}'";
            $this->db->query($sqlRecoveryOf);
            return $result;
        }
    }
    public function smsRecovery($login,$recoveryStr){
        try {
            $client = new SoapClient('http://turbosms.in.ua/api/wsdl.html');
            // Данные авторизации
            $auth = [
                'login' => 'zavireno',
                'password' => 'zavireno99'
            ];
            // Авторизируемся на сервере
            $result = $client->Auth($auth);
            // Текст сообщения ОБЯЗАТЕЛЬНО отправлять в кодировке UTF-8
            $text = iconv('utf-8', 'utf-8', 'Ваша ссылка на востановление доступа http://notarius.smarto.com.ua/notary/users/login/?token='.$recoveryStr[0]['token']);
            $sms = [
                'sender' => 'zavireno24',
                'destination' => $login,
                'text' => $text
            ];
            $result = $client->SendSMS($sms);
        } catch (Exception $e) {
            //echo 'Ошибка: ' . $e->getMessage() . PHP_EOL;
            Session::setFlash('Error.');
        }

    }
    public function mailRecovery($login,$recoveryStr){
        $to = $login;
        $subject = "Request from Notarius";
        $txt = "Ваша ссылка на востановление доступа http://notarius.smarto.com.ua/notary/users/login/?token=".$recoveryStr[0]['token'];
        $headers = "Content-type:   text/html; charset=UTF-8; \r\n";
        $headers .= "From: Notarius" . "\r\n";
        mail($to, $subject, $txt, $headers);
    }
    public function getByLogin($login,$type){
        $login = $this->db->escape($login);
        //$sql = "select * from `users` where login = '{$login}' limit 1";
if($type=='user'){
    $sql = "select users.login, users.role, common_user.fName, users.password, users.is_active
                from `users`
                LEFT JOIN common_user ON users.id = common_user.userID  
                where users.login = '{$login}'  limit 1";
}
elseif($type=='notary'){
    $sql = "select users.login, users.role, notary.fName, users.password, users.is_active
                from `users`
                LEFT JOIN notary ON users.id = notary.userID  
                where users.login = '{$login}'  limit 1";
}
elseif($type=='admin'){
    $sql = "select * from `users` where login = '{$login}' limit 1";
}
$result = $this->db->query($sql);
        if ( isset($result[0]) ){
            return $result[0];
        }
        return false;
    }
    public function getByID($user,$id){
        $id = (int)$id;
        $user = $this->db->escape($user);
        $sql = "select 	* from `$user` where userID = '$id' limit 1";
        //$sql = "SELECT notary.fname, users.login, users.role FROM notary INNER JOIN users ON notary.userID = users.id WHERE userID = '$id'";
        $result = $this->db->query($sql);
        if ( isset($result[0]) ){
            return $result[0];
        }
        return false;
    }
    public function getLocationFor($id){
        $sql = "select id,cityName from `lo_city`";
        $result = $this->db->query($sql);
        if ( isset($result) ){
            return $result;
        }
    }
    public function getByIDPartner($id){
        $id = (int)$id;
        $sql = "select 	* from `common_user` where marriageLink = '$id' limit 1";
        $result = $this->db->query($sql);
        if ( isset($result[0]) ){
            return $result[0];
        }
        return false;
    }
    //найти правильное разделение для вывода по группам
    public function getGroup($Id){
         $sql = "select groupsOfNotaries.id, groupsOfNotaries.notaryID, groupsOfNotaries.groupID, notary.fName from groupsOfNotaries
                 INNER JOIN notary ON notary.id = groupsOfNotaries.notaryID  where groupID='{$Id}'";
         $result = $this->db->query($sql);
        if ( isset($result) ){
            return $result;
        }
    }
    public function getGroupList()
    {
        $sql = "select * from listOfgroups";
        return $this->db->query($sql);
    }
    public function getNotaryList()
    {
        $sql = "SELECT * FROM `notary`";
        $result = $this->db->query($sql);
        if ( isset($result) ){
            return $result;
        }
    }
    public function notaryGroupByID($id){
        $sql = "SELECT name,id FROM `listOfgroups` WHERE id='{$id}'";
        $result = $this->db->query($sql);
        if ( isset($result) ){
            return $result[0];
        }
    }
    /**/
    public function getByToken($token){
        $sql = "select * from users where token = '{$token}' limit 1";
        $result = $this->db->query($sql);
        if($result[0]['role']=='notary' || $result[0]['role']=='user'){
            $sqlRecoveryOf = "update `users` 
                              set passwordRecovery ='0'
                              where token = '{$token}'";
            $this->db->query($sqlRecoveryOf);
        }
        if ( isset($result[0]) ){
            return $result[0];
        }
        return false;

    }

    public function getUList(){
        //$table - like a param
        if(empty($table)){
            $table = 'user';
        }
        $sql = "SELECT common_user.fName,common_user.sName,common_user.mName, common_user.mainPhone, common_user.email,common_user.birthdayDate, common_user.passportInfo, 
common_user.INN, common_user.marriage,users.regDt,users.login, users.role, users.id from common_user INNER JOIN  users  WHERE common_user.userID=users.id;";
        //$sql = "SELECT id,login,role,email,is_active FROM users WHERE role ='$table'";
        $result = $this->db->query($sql);
        if ( isset($result) ){
            return $result;
        }
        return false;
    }
    public function getNList(){
        //$table - like a param
        if(empty($table)){
            $table = 'user';
        }
        $sql = "select users.regDt,notary.area, notary.street, notary.house, notary.entrance, notary.room, notary.doorphoneCode,
                notary.metro, notary.arrayName, notary.wSaturday, notary.wSunday, notary.fName,notary.sName,notary.mName, users.login, 
                users.role, users.id , notary.floor,
                lo_metro.metroName, lo_mArea.mAreaName
                from  notary  JOIN  users
                LEFT JOIN lo_metro ON notary.metro=lo_metro.id
                LEFT JOIN lo_mArea ON notary.arrayName=lo_mArea.id
                WHERE  notary.userID=users.id";
        //$sql = "SELECT id,login,role,email,is_active FROM users WHERE role ='$table'";
        $result = $this->db->query($sql);
        if ( isset($result) ){
            return $result;
        }
        return false;
    }

    public function registerUser($data, $id = null, $token){
        $today = date("Y-m-d");
        if (!isset($data['login']) || !isset($data['password'])) {
            return false;
        }
        $id = (int)$id;
        $login = $this->db->escape($data['login']);
        $password = $this->db->escape($data['password']);

        if (!$id) { // Add new record
            $sql = "
                insert into users
                   set login = '{$login}',
                       regDt = '{$today}',
                       password = '{$password}',
                       token = '{$token}'
            ";
        } else { // Update existing record
            $sql = "
                update user
                   set login = '{$login}',
                       password = '{$password}'
                   where id = {$id}
            ";
        }

        return $this->db->query($sql);
    }
    public function editUserData($data,$login){
        $fName = $data['fName'];
        $sName  = $data['sName'];
        $mName = $data['mName'];

        $birthdayDate = $this->db->escape($data['birthdayDate']);
        $citizenship = $this->db->escape($data['citizenship']);
        $passportInfo = $this->db->escape($data['passportInfo']);
        $passportIB = $this->db->escape($data['passportIB']);
        $PassportDate = $this->db->escape($data['PassportDate']);
        $residence = $this->db->escape($data['residence']);
        $INN = $this->db->escape($data['INN']);
        $marriage = $this->db->escape($data['marriage']);

        $messPhone = $data['messPhone'];
        $messenger = $data['messenger'];
        $email = $data['email'];
        $docs = $data['docs'];
        $marriafeInfo = $data['marriafeInfo'];
        $sms = $data['sms'];
        $promotions = $data['promotions'];
        $rating = $data['rating'];
        $orders = $data['orders'];
        $lastOrder = $data['lastOrder'];
        $sex = $data['sex'];


        $sqlID = "select `id` from users where login='$login' limit 1";
        $id = $this->db->query($sqlID);

        $sql = "
                update common_user
                   set fName = '{$fName}',
                       sName = '{$sName}',
                       mName = '{$mName}',
                        birthdayDate = '{$birthdayDate}',
                       citizenship = '{$citizenship}',
                       passportInfo = '{$passportInfo}',
                       passportIB = '{$passportIB}',
                       PassportDate = '{$PassportDate}',
                       residence = '{$residence}',
                       INN = '{$INN}',
                       marriage = '{$marriage}',
                       messPhone = '{$messPhone}',
                       messenger = '{$messenger}',
                       email = '{$email}',
                       docs = '{$docs}',
                       marriafeInfo = '{$marriafeInfo}',
                       sms = '{$sms}',
                       promotions = '{$promotions}',
                       rating = '{$rating}',
                       orders = '{$orders}',
                       lastOrder = '{$lastOrder}',
                       sex = '{$sex}'
                       
                   where userID = {$id[0]['id']}
            ";
        $result = $this->db->query($sql);
        if ( isset($result) ){
            return $result;
        }

    }
    public function test($data){
        $login = $this->db->escape($data['login']);
        $password = $this->db->escape($data['password']);
        if(!$password){
            return false;
        }
        $hash = md5(Config::get('salt').$password);

        $login = $this->db->escape($login);
        $sql = "select `id` from users where login = '{$login}' limit 1";
        $result = $this->db->query($sql);
        if($result[0]['id']){
            $id = (int)$result[0]['id'];
            $sql = "
                update users
                   set login = '{$login}',
                       password = '{$hash}'
                   where id = {$id}
            ";
        }
        $result = $this->db->query($sql);
        return $result;
    }
    /*функция загрузки документов пользователем или нотариусом*/
    public function uploadFiles($files,$login,$data){
        $dir = $login;
        $scan = scandir($dir);
        if(!$scan ){
            if(!$data['profilePhoto']) {
                mkdir(ROOT . DS . 'webroot/uploads/' . $dir, 0775);
                $succesUlp = '';
                $uploaddir = ROOT.DS.'webroot/uploads/'.$dir.'/';
                for($i=0;$i<=count($files['image']['name']);$i++) {
                    $uploadfile = $uploaddir . basename($files['image']['name'][$i]);
                    move_uploaded_file($files['image']['tmp_name'][$i], $uploadfile);
                    //rename($uploadfile,$uploaddir.$login.'-'.$i.'.'.$type);
                }
                if ($i!=0)
                {
                    $succesUlp = "<h3 class='fillsUpd'>Файлы успешно загружены на сервер</h3>";
                }
                else{
                    echo "<h3 class='fillsUpd'>Ошибка! Не удалось загрузить файлы на сервер!</h3>";
                }
                return $succesUlp;
            }
            if($data['profilePhoto']) {
                mkdir(ROOT . DS . 'webroot/uploads/' . $dir . '/photo', 0775);
                $uploaddir = ROOT.DS.'webroot/uploads/'.$dir.'/photo/';
                $uploadfile = $uploaddir . basename($files['image']['name']);
                $nama = basename($files['image']['name']);

                $fil = scandir($uploaddir,1);
                unlink($uploaddir . $fil[0]);

                    if(move_uploaded_file($files['image']['tmp_name'], $uploadfile)){
                        $sql = " update `notary` set profPhoto = '{$nama}' where login = {$login}";
                        $this->db->query($sql);
                        $succesUlp = "<h3 class='fillsUpd'>Файлы успешно загружены на сервер</h3>";
                    }
                    else{
                        echo "<h3 class='fillsUpd'>Ошибка! Не удалось загрузить файлы на сервер!</h3>";
                    }
                return $succesUlp;
            }
        }

    }

    public function registerNotary($data, $id = null, $token, $role){
        $today = date("Y-m-d");
        if (!isset($data['login']) || !isset($data['password'])) {
            return false;
        }
        $login = $this->db->escape($data['login']);
        $login = str_replace(' ','',$login);

        $sqlCheckNotary = "select * from `users` where login = '{$login }' limit 1";
        $result = $this->db->query($sqlCheckNotary);
        if (isset($result[0]) ){
            return false;
        }

        $id = (int)$id;
        $passwordClean = $this->db->escape($data['password']);
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


        if($role=='notary'){
            $this->db->query($sql);

            $fName = $data['fName'];
            $sName = $data['sName'];

            $takeIdByNotary = "select id from users where login = '$login' limit 1";
            $notaryID = $this->db->query($takeIdByNotary);


            $addToNotaryTable =  "insert into notary
                  set login = '{$login}',
                  userID = '{$notaryID[0]['id']}',
                       fName = '{$fName}',
                       sName = '{$sName}',
                       role = '{$role}',
                       token = '{$token}'";
            return $this->db->query($addToNotaryTable);
        }elseif ($role=='user'){
            $this->db->query($sql);

            $fName = $data['fName'];
            $sName = $data['sName'];

            $takeIdByNotary = "select id from users where login = '$login' limit 1";
            $userID = $this->db->query($takeIdByNotary);

            $addToUserTable =  "insert into common_user
                  set mainPhone = '{$login}',
                  userID = '{$userID[0]['id']}',
                       fName = '{$fName}',
                       sName = '{$sName}'";
            return $this->db->query($addToUserTable);
        }
        else{
            return  $this->db->query($sql);
        }



    }
    public function addNotatyIntoGroup($data,$group){
        $sqlCheckNotary = "select * from `groupsOfNotaries` where notaryID = '{$data[0]}' AND groupID = '{$group}' limit 1";
        $result = $this->db->query($sqlCheckNotary);
        if (isset($result[0]) ){
            return false;
        }
        $sql = "insert into groupsOfNotaries
                   set notaryID = '{$data[0]}',
                       groupID = '{$group}'
            ";
        $result = $this->db->query($sql);
        if ( isset($result) ){
            return $result;
        }
    }

    public function delNotatyFromNotary($id)
    {
        $ids = 0;
        $s = count($id);
        for($i=0;$i<$s;$i++){
            $ids = $ids.','.$id[$i];
        }
        //$id = (int)$id;
        $sql = "delete from `groupsOfNotaries` WHERE `id` IN ($ids)";
        return $this->db->query($sql);
    }
    public function addCatServ($data,$id){
        $name = $this->db->escape($data['name']);
        $descr = $this->db->escape($data['description']);

        if(!$id) {
            $sql = "insert into category
                   set name = '{$name}',
                       description = '{$descr}'
            ";
        }
        else{
            $sql = "update category
                   set name = '{$name}',
                       description = '{$descr}'
                       WHERE id = {$id}
            ";
        }
        $result = $this->db->query($sql);
        if ( isset($result) ){
            return $result;
        }
    }
    public function getListCatServ(){
        $sql = "Select id,name,description from category ";
        $result = $this->db->query($sql);
        if ( isset($result) ){
            return $result;
        }
    }
    public function getCatServ($id){
        $sql = "Select id,name,description from category WHERE id={$id} limit 1";
        $result = $this->db->query($sql);
        if ( isset($result) ){
            return $result[0];
        }
    }
    public function dellServToCar($data,$id){
        $sql = "delete from `category_services` WHERE servicesID = $data AND categoryID = $id";
        $result = $this->db->query($sql);
        if ( isset($result) ){
            return $result;
        }
    }
    public function addServToCar($data,$id){
        $servID = $this->db->escape($data);
        $id = (int)$id;
        $checkSQL = "Select * from category_services WHERE servicesID = $servID AND categoryID = $id";
        $check = $this->db->query($checkSQL);
        if (isset($check[0])) {
            return false;
        }

        $sql = "insert into category_services
                   set categoryID = '{$id}',
                    servicesID = '{$servID}'
            ";
        $result = $this->db->query($sql);
        if ( isset($result) ){
            return $result;
        }
    }
    public function getListServicesIn($id){
        $sql = "select se_services.name, se_services.id from se_services
                 INNER JOIN category_services ON category_services.servicesID = se_services.id  where categoryID={$id}";
        $result = $this->db->query($sql);
        if ( isset($result) ){
            return $result;
        }
    }
    public function addServices($data,$id){
// добавление услуги дописать условие (если есть массив или 1 доп услуга добавить в столбец "связь Услуги и ДопУслуги" )
        $name = $this->db->escape($data['name']);
        if(!$name){
            return false;
        }
        $cost = $this->db->escape($data['cost']);
        $validity = $this->db->escape($data['validity']);
        $additionalID = $this->db->escape($data['additionalID']);
        $descr = $this->db->escape($data['description']);
        $top = $this->db->escape($data['top']);

        if(!$id) {
            $sql = "insert into se_services
                   set name = '{$name}',
                    cost = '{$cost}',
                    validity = '{$validity}',
                    additionalID = '{$additionalID}',
                       description = '{$descr}',
                       top='{$top}'
            ";
        }
        else{
            $sql = "update se_services
                   set name = '{$name}',
                    cost = '{$cost}',
                    validity = '{$validity}',
                    additionalID = '{$additionalID}',
                       description = '{$descr}',
                       top='{$top}'
                   where id = {$id}
            ";
        }
        $result = $this->db->query($sql);
        if ( isset($result) ){
           return $result;
        }
    }
    public function getListServices(){
        $sql = "Select id,name,cost,validity,additionalID,description from se_services ";
        $result = $this->db->query($sql);
        if ( isset($result) ){
            return $result;
        }
    }
    public function getService($data){
        $sql = "Select name,cost,validity,description,top from `se_services` WHERE id={$data} limit 1";
        $result = $this->db->query($sql);
        if ( isset($result[0]) ){
            return $result[0];
        }
    }


    public function addComplementary($data,$id){
// добавление услуги дописать условие (если есть массив или 1 доп услуга добавить в столбец "связь Услуги и ДопУслуги" )
        $name = $this->db->escape($data['name']);
        if(!$name){
            return false;
        }
        $cost = $this->db->escape($data['cost']);
        $validity = $this->db->escape($data['validity']);
        $additionalID = $this->db->escape($data['additionalID']);
        $descr = $this->db->escape($data['description']);
        $payMen = $this->db->escape($data['payMen']);
        $necessarily = $this->db->escape($data['necessarily']);


        if(!$id) {
            $sql = "insert into se_additional_service
                   set name = '{$name}',
                    cost = '{$cost}',
                    validity = '{$validity}',
                    payMen = '{$payMen}',
                    necessarily = '{$necessarily}',
                       description = '{$descr}'
            ";
        }
        else{
            $sql = "update se_additional_service
                   set name = '{$name}',
                    cost = '{$cost}',
                    validity = '{$validity}',
                    payMen = '{$payMen}',
                    necessarily = '{$necessarily}',
                       description = '{$descr}'
                   where id = {$id}
            ";
        }
        $result = $this->db->query($sql);
        if ( isset($result) ){
            return $result;
        }
    }
    public function getListComplementary(){
        $sql = "Select id,name,cost,validity,necessarily,payMen,description from se_additional_service ";
        $result = $this->db->query($sql);
        if ( isset($result) ){
            return $result;
        }
    }
    public function additionalSAdd($data,$id){
        $additID = $this->db->escape($data['additionalSAdd']);
        if(!$additID){
            return false;
        }
        $sqlCheckNotary = "select * from `se_link_services` where additionalID = '{$additID}' AND servicesID = '{$id}' limit 1";
        $result = $this->db->query($sqlCheckNotary);
        if (isset($result[0]) ){
            return false;
        }
         $sql = "insert into `se_link_services`
                   set additionalID = '{$additID}',
                    servicesID = '{$id}'
                  ";
        $result = $this->db->query($sql);
        if ( isset($result) ){
            return $result;
        }
    }
    public function getAdditionalS($id){
        $sql = "select se_additional_service.id, se_additional_service.name from se_additional_service
                 INNER JOIN se_link_services ON se_additional_service.id = se_link_services.additionalID  where servicesID='{$id}'";
        $result = $this->db->query($sql);
        if ( isset($result) ){
            return $result;
        }
    }
    public function additionalSDell($data,$id){
        $additID = $this->db->escape($data['additionalSDell']);
        if(!$additID){
            return false;
        }
        $sql = "delete from `se_link_services` WHERE additionalID = $additID AND servicesID = $id";
        $result = $this->db->query($sql);
        if ( isset($result) ){
            return $result;
        }
    }
    public function getNotaryServbyID($notary,$service){
        $sql = "select no_serv_list.costService, no_serv_list.costAService, se_services.name, se_link_services.additionalID  
                  from no_serv_list
                 LEFT JOIN se_services ON se_services.id = no_serv_list.serviceID
                 INNER JOIN se_link_services ON se_services.id = no_serv_list.serviceID  
                 where notaryID='{$notary}' AND serviceID='$service'";
        $result = $this->db->query($sql);
        if ( isset($result[0]) ){
            return $result[0];
        }
    }
    public function updCoastAS($array,$notary,$service){
        $cosrServ = $array;
        unset($cosrServ['updCoastAS']);
        $costString =  implode(",", $cosrServ);

        $sql = "update `no_serv_list` set  costAService='$costString' WHERE notaryID='$notary' AND serviceID='$service'";

        $result = $this->db->query($sql);
        if ( isset($result) ){
            return $result;
        }
    }
    public function updCoastA($cost,$notary,$service){
        $sql = "update `no_serv_list` set  costService='$cost' WHERE notaryID='$notary' AND serviceID='$service'";

        $result = $this->db->query($sql);
        if ( isset($result) ){
            return $result;
        }
    }
    public function editTax($data,$id){
        if (!isset($data['name'])) {
            return false;
        }
        $id = (int)$id;
        $name = $this->db->escape($data['name']);
        $taxSize = $this->db->escape($data['taxSize']);
        $taxRate = $this->db->escape($data['taxRate']);
        $description = $this->db->escape($data['description']);
        $payMen = $this->db->escape($data['payMen']);
        $taxationBase = $this->db->escape($data['taxationBase']);
        $taxType = $this->db->escape($data['taxType']);

        if (!$id) { // Add new record
            $sql = "
                insert into se_tax
                   set name = '{$name}',
                       taxSize = '{$taxSize}',
                       taxRate = '{$taxRate}',
                       description = '{$description}',
                       payMen = '{$payMen}',
                       taxationBase = '{$taxationBase}',
                       taxType = '{$taxType}'
            ";
        } else { // Update existing record
            $sql = "
                update se_tax
                   set name = '{$name}',
                       taxSize = '{$taxSize}',
                       taxRate = '{$taxRate}',
                       description = '{$description}',
                       payMen = '{$payMen}',
                       taxationBase = '{$taxationBase}',
                       taxType = '{$taxType}'
                   where id = {$id}
            ";
        }

        return $this->db->query($sql);
    }
    public function getTaxList(){
        $sql = 'select * from se_tax';
        $result = $this->db->query($sql);
        if(isset($result)){
           return $result;
        }
    }
    public function getTaxbyID($id){
        $sql = "select * from se_tax WHERE id='{$id}'";
        $result = $this->db->query($sql);
        if(isset($result[0])){
            return $result[0];
        }
    }

    public function editPartner($data,$id,$mL){

        $id = (int)$id;
        $fName = $this->db->escape($data['fName']);
        $sName = $this->db->escape($data['sName']);
        $mName = $this->db->escape($data['mName']);
        $birthdayDate = $this->db->escape($data['birthdayDate']);
        $citizenship = $this->db->escape($data['citizenship']);
        $passportInfo = $this->db->escape($data['passportInfo']);
        $passportIB = $this->db->escape($data['passportIB']);
        $PassportDate = $this->db->escape($data['PassportDate']);
        $residence = $this->db->escape($data['residence']);
        $INN = $this->db->escape($data['INN']);
        $marriage = $this->db->escape($data['marriage']);

        if (!$id) { // Add new record
            $sql = "
                insert into common_user
                   set fName = '{$fName}',
                       sName = '{$sName}',
                       mName = '{$mName}',
                       birthdayDate = '{$birthdayDate}',
                       citizenship = '{$citizenship}',
                       passportInfo = '{$passportInfo}',
                       passportIB = '{$passportIB}',
                       PassportDate = '{$PassportDate}',
                       residence = '{$residence}',
                       INN = '{$INN}',
                       marriage = '{$marriage}',
                       marriageLink = '{$mL}'
            ";
        } else { // Update existing record
            $sql = "
                update common_user
                   set fName = '{$fName}',
                       sName = '{$sName}',
                       mName = '{$mName}',
                       birthdayDate = '{$birthdayDate}',
                       citizenship = '{$citizenship}',
                       passportInfo = '{$passportInfo}',
                       passportIB = '{$passportIB}',
                       PassportDate = '{$PassportDate}',
                       residence = '{$residence}',
                       INN = '{$INN}',
                       marriage = '{$marriage}'
                   where marriageLink = {$mL}
            ";
        }

        return $this->db->query($sql);
    }
    public function getListsTax(){
        $sql = "select * from se_tax";
        $result = $this->db->query($sql);
        if($result){
            return $result;
        }
    }

    public function addStax($data,$id){
        $additID = $this->db->escape($data['addStax']);
        if(!$additID){
            return false;
        }
        $sqlCheckNotary = "select * from `se_sTax` where taxID = '{$additID}' AND servicesID = '{$id}' limit 1";
        $result = $this->db->query($sqlCheckNotary);
        if (isset($result[0]) ){
            return false;
        }
        $sql = "insert into `se_sTax`
                   set taxID = '{$additID}',
                    servicesID = '{$id}'
                  ";
        $result = $this->db->query($sql);
        if ( isset($result) ){
            return $result;
        }
    }
    public function getLTS($id){
        $sql = "select se_tax.id, se_tax.name from se_tax
                 INNER JOIN se_sTax ON se_tax.id = se_sTax.taxID  where servicesID='{$id}'";
        $result = $this->db->query($sql);
        if ( isset($result) ){
            return $result;
        }
    }
    public function sTaxDell($data,$id){
        $additID = $this->db->escape($data['sTaxDell']);
        if(!$additID){
            return false;
        }
        $sql = "delete from `se_sTax` WHERE taxID = $additID AND servicesID = $id";
        $result = $this->db->query($sql);
        if ( isset($result) ){
            return $result;
        }
    }
    public function getAService($id){
        $sql = "select * from se_additional_service WHERE id='{$id}'";
        $result = $this->db->query($sql);
        if ($result[0]){
            return $result[0];
        }
    }
    public function addAStax($data,$id){
        $additID = $this->db->escape($data['addAStax']);
        if(!$additID){
            return false;
        }
        $sqlCheckNotary = "select * from `se_asTax` where taxID = '{$additID}' AND servicesID = '{$id}' limit 1";
        $result = $this->db->query($sqlCheckNotary);
        if (isset($result[0]) ){
            return false;
        }
        $sql = "insert into `se_asTax`
                   set taxID = '{$additID}',
                    servicesID = '{$id}'
                  ";
        $result = $this->db->query($sql);
        if ( isset($result) ){
            return $result;
        }
    }
    public function getLTAS($id){
        $sql = "select se_tax.id, se_tax.name from se_tax
                 INNER JOIN se_asTax ON se_tax.id = se_asTax.taxID  where servicesID='{$id}'";
        $result = $this->db->query($sql);
        if ( isset($result) ){
            return $result;
        }
    }
    public function sTaxADell($data,$id){
        $additID = $this->db->escape($data['sTaxADell']);
        if(!$additID){
            return false;
        }
        $sql = "delete from `se_asTax` WHERE taxID = $additID AND servicesID = $id";
        $result = $this->db->query($sql);
        if ( isset($result) ){
            return $result;
        }
    }
    public function addCity($data){
        $sql = "insert into `lo_city`
                   set cityName = '{$data['cityName']}'
                  ";
        $result = $this->db->query($sql);
        if ( isset($result) ){
            return $result;
        }
    }
    public function getCitys(){
        $sql = "select cityName,id from `lo_city`";
        $result = $this->db->query($sql);
        if ( isset($result) ){
            return $result;
        }
    }
    public function updCity($data){
        $sql = "
                update `lo_city`
                   set cityName = '{$data['cityName']}'
                   WHERE id='{$data['updCity']}'";
        $result = $this->db->query($sql);
        if ( isset($result) ){
            return $result;
        }
    }
    /**/
    public function addArea($data){
        $sql = "insert into `lo_area`
                   set areaName = '{$data['areaName']}'
                  ";
        $result = $this->db->query($sql);
        if ( isset($result) ){
            return $result;
        }
    }
    public function getArea(){
        $sql = "select areaName,id from `lo_area`";
        $result = $this->db->query($sql);
        if ( isset($result) ){
            return $result;
        }
    }
    public function updArea($data){
        $sql = "
                update `lo_area`
                   set areaName = '{$data['areaName']}'
                   WHERE id='{$data['updCity']}'";
        $result = $this->db->query($sql);
        if ( isset($result) ){
            return $result;
        }
    }
    /**/
    public function addmArea($data){
        $sql = "insert into `lo_mArea`
                   set mAreaName = '{$data['mAreaName']}'
                  ";
        $result = $this->db->query($sql);
        if ( isset($result) ){
            return $result;
        }
    }
    public function getmArea(){
        $sql = "select mAreaName,id from `lo_mArea`";
        $result = $this->db->query($sql);
        if ( isset($result) ){
            return $result;
        }
    }
    public function updmArea($data){
        $sql = "
                update `lo_mArea`
                   set mAreaName = '{$data['mAreaName']}'
                   WHERE id='{$data['upmArea']}'";
        $result = $this->db->query($sql);
        if ( isset($result) ){
            return $result;
        }
    }
    /**/
    public function addmMetro($data){
        $sql = "insert into `lo_metro`
                   set metroName = '{$data['metroName']}'
                  ";
        $result = $this->db->query($sql);
        if ( isset($result) ){
            return $result;
        }
    }
    public function getMetro(){
        $sql = "select metroName,id from `lo_metro`";
        $result = $this->db->query($sql);
        if ( isset($result) ){
            return $result;
        }
    }
    public function upmMetro($data){
        $sql = "
                update `lo_metro`
                   set metroName = '{$data['metroName']}'
                   WHERE id='{$data['upmMetro']}'";
        $result = $this->db->query($sql);
        if ( isset($result) ){
            return $result;
        }
    }
    /**/
    public function areaItemAdd($data,$id){
        $repitCheck = "select * from `lo_city_area_link` WHERE (areaID='{$data['areaItemAdd']}' AND cityID='{$id}') ";
        $rcs = $this->db->query($repitCheck);
        if (!empty($rcs) ){
            //return $rcs;
        }
        else{
            $sql = "insert into `lo_city_area_link`
                   set cityID ='{$id}',
                   areaID='{$data['areaItemAdd']}'
                  ";
            $result = $this->db->query($sql);
            if ( isset($result) ){
                return $result;
            }
        }
    }
    public function getArfrCt($id){
        $sql = "select lo_city_area_link.areaID, lo_area.areaName  
                from `lo_city_area_link`
                INNER JOIN `lo_area` ON lo_city_area_link.areaID=lo_area.id
                WHERE lo_city_area_link.cityID='{$id}'";
        $result = $this->db->query($sql);
        if ( isset($result) ){
            return $result;
        }
    }
    public function areaDellfCt($data,$id){
        $sql = "delete from `lo_city_area_link` WHERE (areaID='{$data['areaDellfCt']}' AND cityID='{$id}')";
        $result = $this->db->query($sql);
        if ( isset($result) ){
            return $result;
        }
    }
    /**/
    public function mAreaItemAdd($data,$id){
        $repitCheck = "select * from `lo_city_mArea_link` WHERE (mAreaID='{$data['mAreaItemAdd']}' AND cityID='{$id}') ";
        $rcs = $this->db->query($repitCheck);
        if (!empty($rcs) ){
            //return $rcs;
        }
        else{
            $sql = "insert into `lo_city_mArea_link`
                   set cityID ='{$id}',
                   mAreaID='{$data['mAreaItemAdd']}'
                  ";
            $result = $this->db->query($sql);
            if ( isset($result) ){
                return $result;
            }
        }
    }
    public function getmArfrCt($id){
        $sql = "select lo_city_mArea_link.mAreaID, lo_mArea.mAreaName  from `lo_city_mArea_link` 
                INNER JOIN `lo_mArea` ON lo_city_mArea_link.mAreaID=lo_mArea.id
                WHERE lo_city_mArea_link.cityID='{$id}'";
        $result = $this->db->query($sql);
        if ( isset($result) ){
            return $result;
        }
    }
    public function mAreaDellfCt($data,$id){
        $sql = "delete from `lo_city_mArea_link` WHERE (mAreaID='{$data['mAreaDellfCt']}' AND cityID='{$id}')";
        $result = $this->db->query($sql);
        if ( isset($result) ){
            return $result;
        }
    }
    /**/
    public function metroItemAdd($data,$id){
        $repitCheck = "select * from `lo_city_metro_link` WHERE (metroID='{$data['metroItemAdd']}' AND cityID='{$id}') ";
        $rcs = $this->db->query($repitCheck);
        if (!empty($rcs) ){
            //return $rcs;
        }
        else{
            $sql = "insert into `lo_city_metro_link`
                   set cityID ='{$id}',
                   metroID='{$data['metroItemAdd']}'
                  ";
            $result = $this->db->query($sql);
            if ( isset($result) ){
                return $result;
            }
        }
    }
    public function getMfrCt($id){
        $sql = "select lo_city_metro_link.metroID, lo_metro.metroName  from `lo_city_metro_link` 
                INNER JOIN `lo_metro` ON lo_city_metro_link.metroID=lo_metro.id
                WHERE lo_city_metro_link.cityID='{$id}'";
        $result = $this->db->query($sql);
        if ( isset($result) ){
            return $result;
        }
    }
    public function metroDellfCt($data,$id){
        $sql = "delete from `lo_city_metro_link` WHERE (metroID='{$data['metroDellfCt']}' AND cityID='{$id}')";
        $result = $this->db->query($sql);
        if ( isset($result) ){
            return $result;
        }
    }
    /**/
    public function getCityfromNotaty($id){
        $sql = "select cityName from lo_city WHERE  id='{$id}'";
        $result = $this->db->query($sql);
        if ( isset($result) ){
            return $result[0];
        }
    }
}