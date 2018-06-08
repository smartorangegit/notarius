<?php
include(ROOT.DS.'models'.DS.'main.php');
include(ROOT.DS.'models'.DS.'user.php');
include(ROOT.DS.'models'.DS.'services.php');

include(ROOT.DS.'models'.DS.'page.php');

class MainController extends Controller{

    public function __construct($data = array()){
        parent::__construct($data);
        $this->model = new Main();
        $this->user = new User();
        $this->services = new Service();
        $this->page = new Page();

    }
    public function index(){
        $alias = 'FAQ';
        $this->data['FAQ'] = $this->page->getByAlias($alias);


        $this->data['notaryCount'] = $this->model->getNotariesCount();
        $this->data['serviceCount'] = $this->model->getServicesCount();
        $this->data['getServices'] = $this->model->getServices();

        $limit = 9;
        $this->data['getServicesFull'] = $this->services->getServList($limit);

        $this->data['serviceCatList'] = $this->services->getCatList();

        $this->data['notaryList'] = $this->model->getNotaryList();


        if($_POST['regNum']){
            function clear_phone($login){
                $login = str_replace(' ','',$login );
                $login = str_replace('-','',$login );
                $login = str_replace('+','',$login );
                $login = str_replace('(','',$login );
                $login = str_replace(')','',$login );
                return $login;
            }
            $login= clear_phone($_POST['regNum']);
            if(strlen($login)<12){
                //return false;
            }
            $this->data['userInfo'] = $this->model->GetUserByLogin($login);
            if(isset($this->data['userInfo']))
            {
                $this->data['fastDeal'] = $this->model->fastDeal($login,$_POST);
                if($this->data['fastDeal']){
                    $this->data['smsInfo'] = $this->model->smsInfo($login);
//                    Router::redirect('/');
                    Session::setFlash('Ваша заявка принята!');
                }
            }
            else{
                $hash = md5(Config::get('salt') . $_POST['regNum']);
                $this->data['mainFastReg'] = $this->model->mainFastRegUser($login,$hash);
                if ($this->data['mainFastReg']) {
                    $this->data['fastDeal'] = $this->model->fastDeal($login,$_POST);

                    $password = $_POST['regNum'];
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
                        $text = iconv('utf-8', 'utf-8', "Ваш логин -  " . $login . " Ваш пароль - " . $password .
                            ' http://notarius.smarto.com.ua/user/users/login/?token='.$hash);
                        $sms = [
                            'sender' => 'zavireno24',
                            'destination' => $login,
                            'text' => $text
                        ];
                        $result = $client->SendSMS($sms);
                        Router::redirect('/');
                       // print_r($result);
                    } catch (Exception $e) {
                        //echo 'Ошибка: ' . $e->getMessage() . PHP_EOL;
                        Session::setFlash('Ошибка отправки смс с доступом.');
                    }
                    if($this->data['fastDeal']){
                        $this->data['smsInfo'] = $this->model->smsInfo($login);
                    }
                }
                else {
                    Session::setFlash('Такой номер существует или данные указаны неверно!');
                }
            }

        }

        //login on main page by user-type
        if($_POST['type-user'] == 'user') {
            $type = 'user';
            if ($_POST && isset($_POST['login']) && isset($_POST['password'])) {
                $login = str_replace(' ','',$_POST['login']);
                $user = $this->user->getByLogin($login,$type);
                $hash = md5(Config::get('salt') . $_POST['password']);
                if ($user && $user['is_active'] && $hash == $user['password']) {
                    Session::set('login', $user['login']);
                    Session::set('role', $user['role']);
                    Session::set('name', $user['fName']);
                    Router::redirect('/user/');
                } else {
                    Session::setFlash('Wrong password or login!');
                    echo '<form style="position: absolute; margin: 16px 0 0 228px;" method="post" action="/notary/users/recovery">
                          <div class="form-group">
                            <button type="submit" class="btn btn-danger">Забыл пароль - ноариус</button>
                           </div>
                          </form>';
                    echo '<form style="position: absolute; margin: 16px 0 0 0px;" method="post" action="/user/users/recovery">
                          <div class="form-group">
                            <button type="submit" class="btn btn-danger">Забыл пароль - пользователь</button>
                           </div>
                          </form>';
                }
            }
        }

        elseif ($_POST['type-user'] == 'notary'){
            $type = 'notary';
            if ( $_POST && isset($_POST['login']) && isset($_POST['password']) ){
                $login = str_replace(' ','',$_POST['login']);
                $user = $this->user->getByLogin($login,$type);
                $hash = md5(Config::get('salt').$_POST['password']);
                //print_r($user);
                if ( $user  && $hash == $user['password'] ){
                    Session::set('login', $user['login']);
                    Session::set('role', $user['role']);
                    Session::set('name', $user['fName']);

                    Router::redirect('/notary/');
                }
                else{
                    print_r($user);
                    Session::setFlash('Wrong password or login!');
                }
            }
        }
        if($_POST['fast-reg'] == 1){
            $login = $_POST['login'];
            $_POST['password'] =  str_replace(' ','',$_POST['login']);
            $password = $_POST['login'];
            $hash = md5(Config::get('salt') . $_POST['login']);

            $id = isset($_POST['id']) ? $_POST['id'] : null;
            $result = $this->user->registerNotary($_POST, $id, $hash, 'user');
            if ($result) {
                Session::setFlash('U are registered');
                Router::redirect('/user/users/login/');
                $mass = "Login - " . $login . "\nPassword - " . $password . '<br>' . 'http://notarius.smarto.com.ua/user/users/login/?token=' . $hash;
                $to = $login;
                $subject = "Request from Notarius";
                $txt = $mass;
                $headers = "Content-type:   text/html; charset=UTF-8; \r\n";
                $headers .= "From: Notarius" . "\r\n";
                mail($to, $subject, $txt, $headers);

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
                    $text = iconv('utf-8', 'utf-8', "Ваш логин -  " . $login . " Ваш пароль - " . $password .
                        ' http://notarius.smarto.com.ua/user/users/login/?token='.$hash);
                    $sms = [
                        'sender' => 'zavireno24',
                        'destination' => $login,
                        'text' => $text
                    ];
                    $result = $client->SendSMS($sms);
                    //print_r($result);
                } catch (Exception $e) {
                    //echo 'Ошибка: ' . $e->getMessage() . PHP_EOL;
                    Session::setFlash('Ошибка отправки смс с доступом.');
                }
            }
            else {
                Session::setFlash('Такой номер существует или данные указаны неверно!');
            }
        }


    }
    public function admin_index(){
        Router::redirect('/admin/dashboard/');
//        $this->data['main'] = $this->model->getList();
//
//        if ( $_POST ){
//            $result = $this->model->addGroup($_POST);
//            if ( $result ){
//                Session::setFlash('Group added!');
//                Router::redirect('/admin/');
//
//            }
//            else{
//                Session::setFlash('Error!');
//            }
//        }
    }
    public function user_index()
    {
        $Route = App::getRouter()->getRoute();
        if($Route != Session::get('role')){
            Router::redirect('/'.Session::get('role').'/');
        }
        if(Session::get('login')) {
            if($_SESSION['login']=='admin'){
                Router::redirect('/admin/');
            }
            //print_r($_POST);
            if ($_POST['editUserPass']) {
                //print_r($_POST);
                $this->data['main'] = $this->user->test($_POST);
                Session::set('recMess', '');
            }
            if ($_POST['editUserData']) {
                $this->data['editUserData'] = $this->user->editUserData($_POST,$_SESSION['login']);
                Session::set('recMess', '');
            }
            $this->data['userInfo'] = $this->model->GetUserByLogin($_SESSION['login']);

            if (!empty($_FILES)) {
//                print_r($_FILES);
                $this->data['main'] = $this->user->uploadFiles($_FILES, $_SESSION['login']);
                if ($this->data['main']) {
                    Session::setFlash('Файл добавлен!');
                    //Router::redirect('/user/');
                }
            }
            $name = $_POST['dellName'];
            $log = $_SESSION['login'];
            $dirR = ROOT . DS . 'webroot/uploads/' . $log . '/';
            unlink($dirR . $name);

            $this->data['myNotary'] = $this->model->getMyNotary($this->data['userInfo']['userID']);
        }else{
                Router::redirect('/user/users/login');
            }

    }
    public function notary_index(){
       $Route = App::getRouter()->getRoute();
       if($Route != Session::get('role')){
           Router::redirect('/'.Session::get('role').'/');
       }
        $this->data['main'] = $this->model->getList();
        if(Session::get('login')) {
            if($_SESSION['login']=='admin'){
                Router::redirect('/admin/');
            }

            if ($_POST['editUserPass']) {
                $this->data['main'] = $this->user->test($_POST);
                //Session::set('recMess', '');
            }

            $this->data['notaryInfo'] = $this->model->GetNotaryByLogin($_SESSION['login']);

            if ($_POST['updNotaryInfo']) {
                $this->data['updNotaryInfo'] = $this->model->updNotaryInfo($_POST, $_SESSION['login']);
                if ($this->data['updNotaryInfo']) {
                    Session::set('recMess', '');
                    Router::redirect('/notary/');
                }
            }
            $this->data['getListServices'] = $this->user->getListServices();
            if ($_POST['addServNotary']) {

               // print_r($_POST);

                $this->data['addServNotary'] = $this->model->addServNotary($_POST, $this->data['notaryInfo']['userID']);
                if ($this->data['addServNotary']) {
                    $this->data['addServicesList'] = $this->model->addServicesList($this->data['addServNotary']);
                    Router::redirect('/notary/');
                }
                print_r($this->data['addServicesList']);
            }
            if ($_POST['dellID']) {
                $this->data['dellID'] = $this->model->dellID($_POST['dellID']);
                if ($this->data['dellID']) {
                    // Router::redirect('/notary/');
                }
            }

            $this->data['getListServicesIn'] = $this->model->getListServices($this->data['notaryInfo']['userID']);

            if (!empty($_FILES)) {
                $this->data['main'] = $this->user->uploadFiles($_FILES, $_SESSION['login'],$_POST);
                if ($this->data['main']) {
                    Router::redirect('/notary/');
                    Session::setFlash('Файл добавлен!');
                }
            }
            $name = $_POST['dellName'];
            $log = $_SESSION['login'];
            $dirR = ROOT . DS . 'webroot/uploads/' . $log . '/';
            unlink($dirR . $name);
        }
        else{
            Router::redirect('/notary/users/login');
        }
    }




}