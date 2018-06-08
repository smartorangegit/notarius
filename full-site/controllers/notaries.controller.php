<?php
include(ROOT.DS.'models'.DS.'notaries.php');
include(ROOT.DS.'models'.DS.'rating.php');
include(ROOT.DS.'models'.DS.'main.php');
include(ROOT.DS.'models'.DS.'services.php');

include(ROOT.DS.'lib'.DS.'pagination.class.php');
include(ROOT.DS.'lib'.DS.'button.class.php');
class NotariesController extends Controller
{

    public function __construct($data = array())
    {
        parent::__construct($data);
        $this->model = new Notaries();
        $this->rating = new Rating();
        $this->main = new Main();
        $this->services = new Service();
        $this->user = new User();
    }
    public function index(){
        $listArray = '';
        $this->data['servicesList'] = $this->model->getListOfServices();
        foreach ($this->data['servicesList'] as $item){
            $listArray = $listArray.','.$item['serviceID'];
        }
        $this->data['metros'] = $this->model->getListOfMetros();
        $this->data['areas'] = $this->model->getListOfAreas();

        $dataLisrNotary = substr($listArray,1);
        $this->data['getServicesInfo'] = $this->model->getServicesInfo($dataLisrNotary);

        $no = array('id'=>"",'name'=>'Не выбрана');
        array_unshift($this->data['getServicesInfo'] ,$no);

        $noArea = array('id'=>"",'areaName'=>'Не выбран');
        array_unshift($this->data['areas'] ,$noArea);

        $noMetro = array('id'=>"",'metroName'=>'Не выбрано');
        array_unshift($this->data['metros'] ,$noMetro);


        $this->data['paginationNum'] = $this->model->getContPages($_GET);
        //print_r($this->data['paginationNum']);

//            print_r($this->data['paginationNum']);
//            echo "<br>";
//            print_r($_GET);

        $this->data['cords'] = $this->model->getNotariesCords();
        $this->data['notaries'] = $this->model->getNotariesList();

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
            $this->data['userInfo'] = $this->main->GetUserByLogin($login);
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
        $this->data['metroList'] = $this->model->getMetro();
    }
    public function profile(){
        $param = App::getRouter()->getParams();
        //выборка инфы по нотариусу с notary/users
        $this->data['notaryInfo'] = $this->model->getNotaryByID($param[0]);

       // print_r($param);
        //количество законченых сделок этого нотариуса
        $this->data['countFD']= $this->model->getCountFD($this->data['notaryInfo']['id']);

        //количество отзывов об этом нотариусе и средняя оценка
        $this->data['countRW'] = $this->model->getCountRW($this->data['notaryInfo']['id']);
        $this->data['midRW'] = $this->model->getMidCountRW($this->data['notaryInfo']['id']);

        //выборка списка услуг
        $this->data['notaryServices'] = $this->model->getServicesByNotaryID($param[0]);
        $this->data['notaryComments'] = $this->model->getCommentsByNotaryID($param[0]);

        $this->data['ListCat'] = $this->model->getListCat();
        $this->data['getListServicesIn'] = $this->main->getListServices($param[0]);

        //выборка списка отзывов о текущем нотариусе
        $this->data['rating'] = $this->rating->getRatingList($param[0]);

        //для формы быстрой сделки
        $this->data['servicesListTOP'] = $this->services->getServListTop();

        //заказ услуги
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
            $this->data['userInfo'] = $this->main->GetUserByLogin($login);
            if(isset($this->data['userInfo']))
            {
                $this->data['fastDeal'] = $this->main->fastDeal($login,$_POST);
                if($this->data['fastDeal']){
                    $this->data['smsInfo'] = $this->main->smsInfo($login);
                    //Router::redirect('/');
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
    }
    public function search(){

    }
}