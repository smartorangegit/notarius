<?php
include(ROOT.DS.'models'.DS.'main.php');
header('Content-type: text/html; charset=utf-8');
class UsersController extends Controller{

    public function __construct($data = array()){
        parent::__construct($data);
        $this->model = new User();
        $this->main = new Main();
    }
    public function admin_notary_groups(){
        $this->data['main'] = $this->main->getList();

        if ( $_POST ){
            $result = $this->main->addGroup($_POST);
            if ( $result ){
                Session::setFlash('Group added!');
                Router::redirect('/admin/users/notary_groups/');

            }
            else{
                Session::setFlash('Error!');
            }
        }
    }

    public function admin_login(){
        if ( $_POST && isset($_POST['login']) && isset($_POST['password']) ){
            $type = 'admin';
            $user = $this->model->getByLogin($_POST['login'],$type);
            $hash = md5(Config::get('salt').$_POST['password']);
            if ( $user && $user['is_active'] && $hash == $user['password'] ){
                Session::set('role', $user['role']);
                Session::set('name', $user['fName']);
                Session::set('login', $user['login']);
                Session::set('role', $user['role']);
            }
            Router::redirect('/'.$user['role'].'/');
        }
    }
    public function admin_list(){ //функция вывода списков пользователей для админа ее убрать, т.к. создались 2 новые отдельные
//        $params = $this->getParams();
//        $this->data['list'] = $this->model->getUsersList($_POST['table']);
//
//            echo "<pre>";
//            print_r($_POST);
//            echo "</pre>";
//        if($params) {
//            $this->data['user'] = $this->model->getByID($params[0], $params[1]);
//            $this->data['params'] = $params[0];
//        }
    }
    public function admin_tax(){
        $params = $this->getParams();
    if($_POST){
        $id = isset($_POST['id']) ? $_POST['id'] : null;
        $this->data['editTax'] = $this->model->editTax($_POST,$id);
        if($this->data['editTax']){
            Router::redirect('/admin/users/tax/');
        }
    }
    $this->data['getTaxList'] = $this->model->getTaxList();
    $this->data['getTaxbyID'] = $this->model->getTaxbyID($params[0]);
    }
    public function admin_list_notary(){
        $params = $this->getParams();
        if($params) {
            $this->data['user'] = $this->model->getByID('notary',$params[0]);
            $this->data['location'] = $this->model->getLocationFor($params[0]);
            $this->data['cfn'] = $this->model->getCityfromNotaty($this->data['user']['city']);
            //print_r($this->data['cfn']);
            $no = array('id'=>"0",'cityName'=>'Не выбран');
            array_unshift($this->data['location'],$no);

        }
        $this->data['list1'] = $this->model->getNList();
        if ($_POST['updNotaryInfo']) {
            $this->data['updNotaryInfo'] = $this->main->updNotaryInfo($_POST, $this->data['user']['login']);
            if ($this->data['updNotaryInfo']) {
                Router::redirect('/admin/users/list_notary/'.$params[0].'/');
            }
        }
        if (!empty($_FILES)) {
            $this->data['main'] = $this->model->uploadFiles($_FILES, $this->data['user']['login'],$_POST);
            //print_r($this->data['main']);
            if ($this->data['main']) {
                Router::redirect('/admin/users/list_notary/'.$params[0].'/');
                Session::setFlash('Файл добавлен!');
            }
        }
        $name = $_POST['dellName'];
        $log = $this->data['user']['login'];
        $dirR = ROOT . DS . 'webroot/uploads/' . $log . '/';
        unlink($dirR . $name);
    }
    public function admin_list_c_users(){
        $params = $this->getParams();
        $this->data['list2'] = $this->model->getUList();
        if($params) {
            $this->data['user'] = $this->model->getByID('common_user',$params[0]);
        }
        if ($_POST['editUserData']) {
            $this->data['editUserData'] = $this->model->editUserData($_POST,$this->data['user']['mainPhone']);
            if ($this->data['editUserData']) {
                Router::redirect('/admin/users/list_c_users/'.$params[0].'/');
            }
        }
        if (!empty($_FILES)) {
            $this->data['main'] = $this->model->uploadFiles($_FILES, $this->data['user']['mainPhone']);
            if ($this->data['main']) {
                Session::setFlash('Файл добавлен!');
                Router::redirect('/admin/users/list_c_users/'.$params[0].'/');
            }
            //print_r($this->data['main']);
            //Router::redirect('/user/');
        }
        $name = $_POST['dellName'];
        $log = $this->data['user']['mainPhone'];
        $dirR = ROOT . DS . 'webroot/uploads/' . $log . '/';
        unlink($dirR . $name);
    }
    public function admin_c_users_partner(){
        $params = $this->getParams();
        //print_r($_POST);
        if($_POST){
            $id = isset($_POST['id']) ? $_POST['id'] : null;
            $this->data['editPartner'] = $this->model->editPartner($_POST,$id,$params[0]);
            if($this->data['editPartner']){
                //Router::redirect('/admin/users/c_users_partner/'.$params[0].'/');
            }
        }
        $this->data['user'] = $this->model->getByIDPartner($params[0]);
    }

    public function admin_groups(){
        $this->data['main'] = $this->main->getList();

        if ( $_POST ){
            $result = $this->main->addGroup($_POST);
            if ( $result ){
                Session::setFlash('Group added!');
                Router::redirect('/admin/users/groups/');

            }
            else{
                Session::setFlash('Error!');
            }
        }

        $idGroup = $this->getParams();
        $this->data['groupList'] = $this->model->getGroupList();
        $this->data['notaryGroupByID'] = $this->model->notaryGroupByID($idGroup[0]);

        $this->data['notaryList'] = $this->model->getNotaryList();
        if($_POST['notaryList']) {
            $this->data['addedNotaries'] = $this->model->addNotatyIntoGroup($_POST['notaryList'], $idGroup[0]);
        }
        if($_POST['notaryDell']){
            $this->data['deledNotaries'] = $this->model->delNotatyFromNotary($_POST['notaryDell']);
        }
        $this->data['notariesIn'] = $this->model->getGroup($idGroup[0]);
   // echo "<pre>"; print_r($_POST); echo "</pre>";
    }

    public function admin_transactionscat(){
        $this->data['params'] = $this->getParams();
        $param = $this->data['params'];
       if($_POST['addCatSer']){
           $this->data['addCatServ'] = $this->model->addCatServ($_POST);
           if($this->data['addCatServ']){
               Router::redirect('/admin/users/transactionscat/');
           }
       }
        if($_POST['updCatSer']){
            $this->data['updCatSer'] = $this->model->addCatServ($_POST,$param[0]);
            if($this->data['updCatSer']){
                Router::redirect('/admin/users/transactionscat/'.$param[0].'/');
            }
        }
       $this->data['getListCatServ'] = $this->model->getListCatServ();
        if($param[0]) {
            if($_POST['addServToCar']) {
                $this->data['addServToCar'] = $this->model->addServToCar($_POST['addServToCar'],$param[0]);
                if($this->data['addServToCar']){
                    Router::redirect('/admin/users/transactionscat/'.$param[0].'/');
                }
            }
            if($_POST['dellServToCar']) {
                $this->data['dellServToCar'] = $this->model->dellServToCar($_POST['dellServToCar'],$param[0]);
                if($this->data['dellServToCar']){
                    Router::redirect('/admin/users/transactionscat/'.$param[0].'/');
                }
            }
            $this->data['getCatServ'] = $this->model->getCatServ($param[0]);
            $this->data['getListServices'] = $this->model->getListServices();
            $this->data['getListServicesIn'] = $this->model->getListServicesIn($param[0]);
        }
    }

    public function admin_services(){
        $this->data['params'] = $this->getParams();
        $param = $this->data['params'];
        if($_POST['addServices']) {
            //print_r($_POST);
            $this->data['addServices'] = $this->model->addServices($_POST,$param[0]);
            if ($this->data['addServices']){
                Router::redirect('/admin/users/services/');
            }
        }if($_POST['updServices']) {
            $this->data['updServices'] = $this->model->addServices($_POST,$param[0]);
            if ($this->data['updServices']){
                Router::redirect('/admin/users/services/'.$param[0].'/');
            }
        }
        $this->data['getListServices'] = $this->model->getListServices();
        if(!empty($param )) {
            $this->data['getService'] = $this->model->getService($param[0]);
            $this->data['getListComplementary'] = $this->model->getListComplementary();
            //print_r($param[0]);
        }
        if($_POST['additionalSAdd']){
            $this->data['additionalSAdd'] = $this->model->additionalSAdd($_POST,$param[0]);
            if ($this->data['additionalSAdd']){
                Router::redirect('/admin/users/services/'.$param[0].'/');
            }
        }
        if($_POST['additionalSDell']){
            $this->data['additionalSDell'] = $this->model->additionalSDell($_POST,$param[0]);
            if ($this->data['additionalSDell']){
                Router::redirect('/admin/users/services/'.$param[0].'/');
            }
        }
        if($_POST['sTaxDell']){
            $this->data['sTaxDell'] = $this->model->sTaxDell($_POST,$param[0]);
            if ($this->data['sTaxDell']){
                Router::redirect('/admin/users/services/'.$param[0].'/');
            }
        }
        if($_POST['addStax']){
            $this->data['addStax'] = $this->model->addStax($_POST,$param[0]);
            if ($this->data['addStax']){
                Router::redirect('/admin/users/services/'.$param[0].'/');
            }
        }
        $this->data['getListsTax'] = $this->model->getListsTax();
        $this->data['getAdditionalS'] = $this->model->getAdditionalS($param[0]);
        $this->data['getLTS'] = $this->model->getLTS($param[0]);
    }

    public function admin_complementary(){
        $this->data['params'] = $this->getParams();
        $param = $this->data['params'];
        if($_POST['addComplementary']) {
            $this->data['addComplementary'] = $this->model->addComplementary($_POST,$param[0]);
            if ($this->data['addComplementary']){
                Router::redirect('/admin/users/complementary/');
            }
        }
        if($_POST['updComplementary']) {
            $this->data['updComplementary'] = $this->model->addComplementary($_POST,$param[0]);
            if ($this->data['updComplementary']){
                Router::redirect('/admin/users/complementary/'.$param[0].'/');
            }
        }

        if($_POST['sTaxADell']){
            $this->data['sTaxADell'] = $this->model->sTaxADell($_POST,$param[0]);
            if ($this->data['sTaxADell']){
                Router::redirect('/admin/users/complementary/'.$param[0].'/');
            }
        }
        if($_POST['addAStax']){
            $this->data['addAStax'] = $this->model->addAStax($_POST,$param[0]);
            if ($this->data['addAStax']){
                Router::redirect('/admin/users/complementary/'.$param[0].'/');
            }
        }
        $this->data['getListsTax'] = $this->model->getListsTax();
        $this->data['getAService'] = $this->model->getAService($param[0]);
        $this->data['getLTAS'] = $this->model->getLTAS($param[0]);
        $this->data['getListComplementary'] = $this->model->getListComplementary();
    }

    public function admin_locations(){
        $this->data['params'] = $this->getParams();
        $param = $this->data['params'];
        if($_POST['addCity']) {
            $this->data['addCity'] = $this->model->addCity($_POST);
            if ($this->data['addCity']){
                Router::redirect('/admin/users/locations/');
            }
        }
        if($_POST['updCity']){
            $this->data['updCity'] = $this->model->updCity($_POST);
            if ($this->data['updCity']){
                Router::redirect('/admin/users/locations/'.$param[0].'/');
            }
        }

        $this->data['getCitys']=$this->model->getCitys();
        $this->data['getArea']=$this->model->getArea();
        $this->data['getmArea']=$this->model->getmArea();
        $this->data['getMetro']=$this->model->getMetro();

        //print_r($_POST);

        if($_POST['areaItemAdd']){
           $this->model->areaItemAdd($_POST,$param[0]);
        }
        if($_POST['areaDellfCt']){
            $this->model->areaDellfCt($_POST,$param[0]);
        }
        /**/
        if($_POST['mAreaItemAdd']){
            $this->model->mAreaItemAdd($_POST,$param[0]);
        }
        if($_POST['mAreaDellfCt']){
            $this->model->mAreaDellfCt($_POST,$param[0]);
        }
        /**/
        if($_POST['metroItemAdd']){
            $this->model->metroItemAdd($_POST,$param[0]);
        }
        if($_POST['metroDellfCt']){
            $this->model->metroDellfCt($_POST,$param[0]);
        }
        $this->data['getMfrCt'] = $this->model->getMfrCt($param[0]);
        $this->data['getmArfrCt'] = $this->model->getmArfrCt($param[0]);
        $this->data['getArfrCt'] = $this->model->getArfrCt($param[0]);
        //print_r($this->data['getMfrCt']);
    }

    public function admin_area(){
        $this->data['params'] = $this->getParams();
        $param = $this->data['params'];
        if($_POST['addArea']) {
            $this->data['addArea'] = $this->model->addArea($_POST);
            if ($this->data['addArea']){
                Router::redirect('/admin/users/area/');
            }
        }
        if($_POST['updArea']){
            $this->data['updArea'] = $this->model->updArea($_POST);
            if ($this->data['updArea']){
                Router::redirect('/admin/users/area/'.$param[0].'/');
            }
        }
        $this->data['getArea']=$this->model->getArea();
    }

    public function admin_mikro_area(){
        $this->data['params'] = $this->getParams();
        $param = $this->data['params'];
        if($_POST['addmArea']) {
            $this->data['addmArea'] = $this->model->addmArea($_POST);
            if ($this->data['addmArea']){
                Router::redirect('/admin/users/mikro_area/');
            }
        }
        if($_POST['upmArea']){
            $this->data['upmArea'] = $this->model->updmArea($_POST);
            if ($this->data['upmArea']){
                Router::redirect('/admin/users/mikro_area/'.$param[0].'/');
            }
        }
        $this->data['getmArea']=$this->model->getmArea();
    }
    public function admin_metro(){
        $this->data['params'] = $this->getParams();
        $param = $this->data['params'];
        if($_POST['addmMetro']) {
            $this->data['addmMetro'] = $this->model->addmMetro($_POST);
            if ($this->data['addmMetro']){
                Router::redirect('/admin/users/metro/');
            }
        }
        if($_POST['upmMetro']){
            $this->data['upmMetro'] = $this->model->upmMetro($_POST);
            if ($this->data['upmMetro']){
                Router::redirect('/admin/users/metro/'.$param[0].'/');
            }
        }
        $this->data['getMetro']=$this->model->getMetro();
    }

    public function admin_logout(){
        Session::destroy();
        Router::redirect('/admin/');
    }

    public function user_login(){
        if ( $_POST && isset($_POST['login']) && isset($_POST['password']) ){
//            $_SESSION['z'] = $_POST['login'];
            $type = 'user';
            $user = $this->model->getByLogin($_POST['login'],$type);
            $hash = md5(Config::get('salt').$_POST['password']);
            if ( $user && $user['is_active'] && $hash == $user['password'] ){
                Session::set('login', $user['login']);
                Session::set('role', $user['role']);
                Session::set('name', $user['fName']);
                Session::set('recMess', '');
                Router::redirect('/'.$user['role'].'/');
            }
            else{
                Session::setFlash('Wrong password or login!');
            }
        }
        elseif(isset($_GET['token'])){
                echo $_GET['token'];
                $user = $this->model->getByToken($_GET['token']);
            if ( $user && $user['is_active'] && $user['passwordRecovery']==1){
                Session::set('login', $user['login']);
                Session::set('role', $user['role']);
                Session::set('recMess', 'Для дальнейшей работы, создйте новый пароль!');
                Router::redirect('/'.$user['role'].'/');
            }
        }
    }
    public function user_logout(){
        Session::destroy();
        Router::redirect('/');
    }



    public function user_register(){
        if ( $_POST ) {
            $login = str_replace(' ','',$_POST['login']);
            $password = $_POST['password'];
            $hash = md5(Config::get('salt') . $_POST['password']);

            $id = isset($_POST['id']) ? $_POST['id'] : null;
            $result = $this->model->registerNotary($_POST, $id, $hash, 'user');
            if ($result) {
                Session::setFlash('U are registered');
                //Router::redirect('/user/users/login/');
                /*$mass = "Login - " . $login . "\nPassword - " . $password . '<br>' . 'http://notarius.smarto.com.ua/user/users/login/?token=' . $hash;
                $to = $login;
                $subject = "Request from Notarius";
                $txt = $mass;
                $headers = "Content-type:   text/html; charset=UTF-8; \r\n";
                $headers .= "From: Notarius" . "\r\n";
                mail($to, $subject, $txt, $headers);*/

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
                    //echo $result->GetCreditBalanceResult . PHP_EOL;

                    // Текст сообщения ОБЯЗАТЕЛЬНО отправлять в кодировке UTF-8
                    $text = iconv('utf-8', 'utf-8', "Ваш логин -  " . $login . " Ваш пароль - " . $password .
                        ' http://notarius.smarto.com.ua/user/users/login/?token='.$hash);
                    $sms = [
                        'sender' => 'zavireno24',
                        'destination' => $login,
                        'text' => $text
                    ];
                    $result = $client->SendSMS($sms);
                    print_r($result);
                } catch (Exception $e) {
                    //echo 'Ошибка: ' . $e->getMessage() . PHP_EOL;
                    Session::setFlash('Error.');
                }
            }
            else {
                Session::setFlash('Были введены неверные данные или пользователь существует');
            }
        }
        }

    public function notary_register()
    {
        if ($_POST['login'] !='') {
            $login = $_POST['login'];
            $password = $_POST['password'];
            $hash = md5(Config::get('salt') . $_POST['login']);

            $id = isset($_POST['id']) ? $_POST['id'] : null;
            $result = $this->model->registerNotary($_POST, $id, $hash, 'notary');
            if ($result) {
                Session::setFlash('U are registered');
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
                    //echo $result->GetCreditBalanceResult . PHP_EOL;

                    // Текст сообщения ОБЯЗАТЕЛЬНО отправлять в кодировке UTF-8
                    $text = iconv('utf-8', 'utf-8', "Ваш логин -  " . $login . " Ваш пароль - " . $password .
                        ' http://notarius.smarto.com.ua/notary/users/login/?token='.$hash);
                    $sms = [
                        'sender' => 'zavireno24',
                        'destination' => $login,
                        'text' => $text
                    ];
                    $result = $client->SendSMS($sms);
                    //print_r($result);
                } catch (Exception $e) {
                    //echo 'Ошибка: ' . $e->getMessage() . PHP_EOL;
                    Session::setFlash('Error.');
                }

            } else {
                Session::setFlash('Были введены неверные данные или пользователь существует');
            }
        }

    }
    public function notary_login(){
        if ( $_POST && isset($_POST['login']) && isset($_POST['password']) ){
            $login = str_replace(' ','',$_POST['login']);
            $type = 'notary';
            $user = $this->model->getByLogin($login,$type);
            $hash = md5(Config::get('salt').$_POST['password']);
            //print_r($user);
            if ( $user  && $hash == $user['password'] ){
                Session::set('login', $user['login']);
                Session::set('role', $user['role']);
                Session::set('name', $user['fName']);
                Session::set('recMess', '');
                Router::redirect('/'.$user['role'].'/');
            }
            else{
                Session::setFlash('Wrong password or login!');
            }
        }
        elseif(isset($_GET['token'])){
            echo $_GET['token'];
            $user = $this->model->getByToken($_GET['token']);
            print_r($user);
            if ( $user && $user['is_active'] && $user['passwordRecovery']==1){
                Session::set('login', $user['login']);
                Session::set('role', $user['role']);
                Router::redirect('/'.$user['role'].'/');
                Session::set('recMess', 'Для дальнейшей работы, создйте новый пароль!');
            }
        }
    }
    public function notary_recovery(){
        //print_r($_POST);
        if($_POST['recoveryStr']){
            $login = str_replace(' ','',$_POST['recoveryStr']);
            $recovery = $this->model->recovery($login);
            if(!empty($recovery)) {
                if (is_numeric($login)) {
                    $smsRecovery = $this->model->smsRecovery($login, $recovery);
                    Session::setFlash('Ссыллка востановления выслана на ваш номер, после перехода, она станет не активной!');
                    //echo var_export($login, true) . " - число", PHP_EOL;
                } else {
                    $mailRecovery = $this->model->mailRecovery($login, $recovery);
                    Session::setFlash('Ссыллка востановления выслана на вашу почту, после перехода, она станет не активной!');
                    //echo var_export($login, true) . " - НЕ число", PHP_EOL;
                }
                //print_r($recovery);
            }
        }

    }
    public function user_recovery(){
        //print_r($_POST);
        if($_POST['recoveryStr']){
            $login = str_replace(' ','',$_POST['recoveryStr']);
            $recovery = $this->model->recovery($login);
            if(!empty($recovery)) {
                if (is_numeric($login)) {
                    $smsRecovery = $this->model->smsRecovery($login, $recovery);
                    Session::setFlash('Ссыллка востановления выслана на ваш номер, после перехода, она станет не активной!');
                    //echo var_export($login, true) . " - число", PHP_EOL;
                } else {
                    $mailRecovery = $this->model->mailRecovery($login, $recovery);
                    Session::setFlash('Ссыллка востановления выслана на вашу почту, после перехода, она станет не активной!');
                    //echo var_export($login, true) . " - НЕ число", PHP_EOL;
                }
                //print_r($recovery);
            }
        }

    }
    public function notary_services(){
        $this->data['params'] = $this->getParams();
        $param = $this->data['params'];

        $this->data['notaryInfo'] = $this->main->GetNotaryByLogin($_SESSION['login']);

        $this->data['getAdditionalS'] = $this->model->getAdditionalS($param[0]);

        if($_POST['updCoastAS']){
            $this->data['updCoastAS'] = $this->model->updCoastAS($_POST,$this->data['notaryInfo']['userID'],$param[0]);
        }
        if($_POST['updCoastA']){
            $this->data['updCoastA'] = $this->model->updCoastA($_POST['costS'],$this->data['notaryInfo']['userID'],$param[0]);
        }
        //print_r($this->data['getNotaryServbyID']);
        //print_r($_POST);
        $this->data['getNotaryServbyID'] = $this->model->getNotaryServbyID($this->data['notaryInfo']['userID'],$param[0]);
    }
    public function notary_logout(){
        Session::destroy();
        Router::redirect('/notary/users/login');
    }

}