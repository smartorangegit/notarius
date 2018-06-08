<?php
include(ROOT.DS.'models'.DS.'services.php');
include(ROOT.DS.'models'.DS.'main.php');
include(ROOT.DS.'models'.DS.'user.php');
include(ROOT.DS.'models'.DS.'discounts.php');

class PagesController extends Controller{

    public function __construct($data = array()){
        parent::__construct($data);
        $this->model = new Page();
        $this->main = new Main();
        $this->user = new User();
        $this->service = new Service();
        $this->discount = new Discount();
    }

    public function index(){
        $this->data['pages'] = $this->model->getList(true);
    }

    public function view(){
        if($_POST['discountForm']) {
            //print_r($_POST);
            //echo Session::get('login');
            //Session::setFlash('Данные для заказа акции попали в контроллер!');

            function clear_phone($login)
            {
                $login = str_replace(' ', '', $login);
                $login = str_replace('-', '', $login);
                $login = str_replace('+', '', $login);
                $login = str_replace('(', '', $login);
                $login = str_replace(')', '', $login);
                return $login;
            }

            $type = 'user';
            $login = clear_phone($_POST['fdNumber']);
            $user = $this->user->getByLogin($login, $type);

            if ($user) {
                if($this->main->actionDeal($_POST,$login)){
                    $this->main->smsInfo($login);
                    Session::setFlash('Ваша заявка выполнена!');
                }
            }
            else {
            $password = $login;
            $hash = md5(Config::get('salt') . $login);

            $id = isset($_POST['id']) ? $_POST['id'] : null;
            $result = $this->main->registerUser($login, $id, $hash, 'user');
            if ($result) {
                Session::setFlash('Регистрация успешна!');
                if($this->main->actionDeal($_POST,$login)){
                    $this->main->smsInfo($login);
                    Session::setFlash('Ваша заявка выполнена!');
                }
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
                    $result = $client->GetCreditBalance();
                    // Текст сообщения ОБЯЗАТЕЛЬНО отправлять в кодировке UTF-8
                    $text = iconv('utf-8', 'utf-8', "Ваш логин -  " . $login . " Ваш пароль - " . $password .
                        ' http://notarius.smarto.com.ua/user/users/login/?token=' . $hash);
                    $sms = [
                        'sender' => 'zavireno24',
                        'destination' => $login,
                        'text' => $text
                    ];
                    $result = $client->SendSMS($sms);
                } catch (Exception $e) {
                    Session::setFlash('Ошибка отправки смс с доступом.');
                }
            } else {
                Session::setFlash('Такой номер существует или данные указаны неверно!');
            }
          }
        }
/*
        if($_GET['ajax_data']){
            print_r($_GET);

            $this->data['countDiscounts'] = $this->discount->getCountOfDiscounts($_GET);
            $this->data['discountData'] = $this->discount->getDiscounts($_GET);

            print_r($this->data['countDiscounts'] );
            print_r($this->data['discountData'] );
        }
        else{
            $this->data['countDiscounts'] = $this->discount->getCountOfDiscounts();
            $this->data['discountData'] = $this->discount->getDiscounts();
        }
*/
        $this->data['countDiscounts'] = $this->discount->getCountOfDiscounts($_GET);
        $this->data['discountData'] = $this->discount->getDiscounts($_GET);

        $this->data['sList'] = $this->discount->getServList();
        $this->data['aList'] = $this->discount->getAreaList();

        //print_r($_GET);

        if($_POST['hiw-nr']){
            if(empty($_POST['login']) || empty($_POST['password']))
            {?>
<!--                <script>-->
<!--                    alert('Вы не заполнели все даныне!');-->
<!--                </script>-->
            <?}
            else{
                $login = $_POST['login'];
                $password = $_POST['password'];
                $hash = md5(Config::get('salt') . $_POST['login']);

                $id = isset($_POST['id']) ? $_POST['id'] : null;
                $result = $this->user->registerNotary($_POST, $id, $hash, 'notary');
                if ($result) {

                    ?>
<!--                    <script>-->
<!--                        alert('Регистрация успешна!');-->
<!--                    </script>-->
                    <?
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
                        $result = $client->GetCreditBalance();
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
                    Session::setFlash('Нотариус с такими данными, уже существует!');
                    ?>
<!--                    <script>-->
<!--                        alert('Нотариус с такими данными, уже существует!');-->
<!--                    </script>-->
                    <?
                    //Session::setFlash('Нотариус с такими данными, уже существует!');
                }
            }
        }

        $number = str_replace(" ","",$_POST['number']);
        if($_POST['number']) {
            if (strlen($number) == 16) {
                if ($this->model->addRecall($number)) {
                    Router::redirect('/pages/view/contacts/');
                }
            }
            else {
                Session::setFlash('Данные не верны, попробуйте еще раз!');
            }
        }


        $params = App::getRouter()->getParams();
        //print_r($params);
        if ( isset($params[0]) ){
            $alias = strtolower($params[0]);
            $this->data['page'] = $this->model->getByAlias($alias);
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
        if($params[0] == 'about-us'){
            $this->data['servicesListTOP'] = $this->service->getServListTop();
        }
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
            $type = $_POST['type-user'];
            $this->data['userInfo'] = $this->main->GetUserByLogin($login,$type);
            if(isset($this->data['userInfo']))
            {
                $this->data['fastDeal'] = $this->main->fastDeal($login,$_POST);
                if($this->data['fastDeal']){
                    $this->data['smsInfo'] = $this->main->smsInfo($login);
//                    Router::redirect('/');
                    Session::setFlash('Ваша заявка принята!');
                }
            }
            else{
                $hash = md5(Config::get('salt') . $_POST['regNum']);
                $this->data['mainFastReg'] = $this->main->mainFastRegUser($login,$hash);
                if ($this->data['mainFastReg']) {
                    $this->data['fastDeal'] = $this->main->fastDeal($login,$_POST);

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
                        $this->data['smsInfo'] = $this->main->smsInfo($login);
                    }
                }
                else {
                    Session::setFlash('Такой номер существует или данные указаны неверно!');
                }
            }

        }



    }

    public function admin_index(){
        $this->data['pages'] = $this->model->getList(false);
    }

    public function admin_add(){
        if ( $_POST ){
            $result = $this->model->save($_POST);
            if ( $result ){
                Session::setFlash('Page was saved.');
            } else {
                Session::setFlash('Error.');
            }
            Router::redirect('/admin/pages/');
        }
    }

    public function admin_edit(){
        if ( $_POST ){
            $id = isset($_POST['id']) ? $_POST['id'] : null;
            if($_POST['confirm']==1) {
                $result = $this->model->save($_POST, $id);
            }
            if ( $result ){
                Session::setFlash('Page was saved.');
            } else {
                Session::setFlash('Error.');
            }
            Router::redirect('/admin/pages/');
        }

        if ( isset($this->params[0]) ){
            $this->data['page'] = $this->model->getById($this->params[0]);
        } else {
            Session::setFlash('Wrong page id.');
            Router::redirect('/admin/pages/');
        }
    }

    public function admin_delete(){
        if ( isset($this->params[0]) ){
            $result = $this->model->delete($this->params[0]);
            if ( $result ){
                Session::setFlash('Page was deleted.');
            } else {
                Session::setFlash('Error.');
            }
        }
        Router::redirect('/admin/pages/');
    }

}