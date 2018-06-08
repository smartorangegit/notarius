<?php
class LiqPay
{
    const CURRENCY_EUR = 'EUR';
    const CURRENCY_USD = 'USD';
    const CURRENCY_UAH = 'UAH';
    const CURRENCY_RUB = 'RUB';
    const CURRENCY_RUR = 'RUR';

    private $_api_url = 'https://www.liqpay.ua/api/';
    private $_checkout_url = 'https://www.liqpay.ua/api/3/checkout';
    protected $_supportedCurrencies = array(
        self::CURRENCY_EUR,
        self::CURRENCY_USD,
        self::CURRENCY_UAH,
        self::CURRENCY_RUB,
        self::CURRENCY_RUR,
    );
    private $_public_key;
    private $_private_key;
    private $_server_response_code = null;

    /**
     * Constructor.
     *
     * @param string $public_key
     * @param string $private_key
     *
     * @throws InvalidArgumentException
     */
    public function __construct($public_key, $private_key)
    {
        if (empty($public_key)) {
            throw new InvalidArgumentException('public_key is empty');
        }

        if (empty($private_key)) {
            throw new InvalidArgumentException('private_key is empty');
        }

        $this->_public_key = $public_key;
        $this->_private_key = $private_key;
    }

    /**
     * Call API
     *
     * @param string $path
     * @param array $params
     * @param int $timeout
     *
     * @return string
     */
    public function api($path, $params = array(), $timeout = 5)
    {
        if (!isset($params['version'])) {
            throw new InvalidArgumentException('version is null');
        }
        $url         = $this->_api_url . $path;
        $public_key  = $this->_public_key;
        $private_key = $this->_private_key;
        $data        = $this->encode_params(array_merge(compact('public_key'), $params));
        $signature   = $this->str_to_sign($private_key.$data.$private_key);
        $postfields  = http_build_query(array(
            'data'  => $data,
            'signature' => $signature
        ));

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true); // Avoid MITM vulnerability http://phpsecurity.readthedocs.io/en/latest/Input-Validation.html#validation-of-input-sources
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);    // Check the existence of a common name and also verify that it matches the hostname provided
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT ,$timeout);   // The number of seconds to wait while trying to connect
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);          // The maximum number of seconds to allow cURL functions to execute
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $server_output = curl_exec($ch);
        $this->_server_response_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return json_decode($server_output);
    }

    /**
     * Return last api response http code
     *
     * @return string|null
     */
    public function get_response_code()
    {
        return $this->_server_response_code;
    }

    /**
     * cnb_form
     *
     * @param array $params
     *
     * @return string
     *
     * @throws InvalidArgumentException
     */
    public function cnb_form($params)
    {
        $language = 'ru';
        if (isset($params['language']) && $params['language'] == 'en') {
            $language = 'en';
        }

        $params    = $this->cnb_params($params);
        $data      = $this->encode_params($params);
        $signature = $this->cnb_signature($params);

        return sprintf('
            <form method="POST" action="%s" accept-charset="utf-8">
                %s
                %s
                <input  type="image" src="//static.liqpay.ua/buttons/p1%s.radius.png" name="btn_text" />
            </form>
            ',
            $this->_checkout_url,
            sprintf('<input type="hidden" name="%s" value="%s" />', 'data', $data),
            sprintf('<input type="hidden" name="%s" value="%s" />', 'signature', $signature),
            $language
        );
    }

    /**
     * cnb_signature
     *
     * @param array $params
     *
     * @return string
     */
    public function cnb_signature($params)
    {
        $params      = $this->cnb_params($params);
        $private_key = $this->_private_key;

        $json      = $this->encode_params($params);
        $signature = $this->str_to_sign($private_key . $json . $private_key);

        return $signature;
    }

    /**
     * cnb_params
     *
     * @param array $params
     *
     * @return array $params
     */
    private function cnb_params($params)
    {
        $params['public_key'] = $this->_public_key;

        if (!isset($params['version'])) {
            throw new InvalidArgumentException('version is null');
        }
        if (!isset($params['amount'])) {
            throw new InvalidArgumentException('amount is null');
        }
        if (!isset($params['currency'])) {
            throw new InvalidArgumentException('currency is null');
        }
        if (!in_array($params['currency'], $this->_supportedCurrencies)) {
            throw new InvalidArgumentException('currency is not supported');
        }
        if ($params['currency'] == self::CURRENCY_RUR) {
            $params['currency'] = self::CURRENCY_RUB;
        }
        if (!isset($params['description'])) {
            throw new InvalidArgumentException('description is null');
        }

        return $params;
    }

    /**
     * encode_params
     *
     * @param array $params
     * @return string
     */
    private function encode_params($params)
    {
        return base64_encode(json_encode($params));
    }

    /**
     * decode_params
     *
     * @param string $params
     * @return array
     */
    public function decode_params($params)
    {
        return json_decode(base64_decode($params), true);
    }

    /**
     * str_to_sign
     *
     * @param string $str
     *
     * @return string
     */
    public function str_to_sign($str)
    {
        $signature = base64_encode(sha1($str, 1));

        return $signature;
    }
}

class DashboardController extends Controller
{

    public function __construct($data = array())
    {
        parent::__construct($data);
        $this->model = new Board();
        $this->main = new Main();
    }

    public function admin_index()
    {
      $this->data['notaryGroups'] = $this->model->getGroups();
      //print_r($_POST);
    }

    public function user_index(){
        $layout = App::getRouter()->getRoute();
        if($layout == 'user' && Session::get('role') != 'user') {
            Router::redirect('/user/');
        }
        $this->data['servicesList'] = $this->model->getServList();
        $userID = $this->model->getID($_SESSION['login']);
        if($_POST){
            $this->data['zayavka'] = $this->model->Zayavka($_POST,$userID[0]['userID']);
            $result = $this->data['zayavka'];
            if(!empty($result)){
                $this->data['smsInfo'] = $this->main->smsInfo($_SESSION['login']);
//                Router::redirect('/user/dashboard/');
                Session::setFlash('Заявка отправлена!');
            }
        }
        $this->data['userDeal'] = $this->model->getUserDeal($userID[0]['userID']);
        $this->data['userAll'] = $this->model->getUserAll($userID[0]['userID']);


        if($_GET['ajax_data']){
            //print_r($_GET);
            $this->data['updPay'] = $this->model->updPayStat($_GET['id'],$_GET['cost']);
            $this->data['addPayment'] = $this->model->addPayment($_GET);
            //print_r($this->data['updPay']);
        }

        //print_r($this->data['userDeal']);
    }

    public function notary_index(){
        $layout = App::getRouter()->getRoute();
        if($layout == 'notary' && Session::get('role') != 'notary') {
            Router::redirect('/notary/');
        }
        $this->data['notary'] = $this->model->getNotary($_SESSION['login']);
        $this->data['groups'] = $this->model->getGroup($this->data['notary']);

        $this->data['notaryL'] = $this->model->getNotaryList();

        foreach ($this->data['groups'] as $itemG){
            $grMass[] = $itemG['groupID'];
        }
        foreach ($this->data['notaryL'] as $itemnL){
            if(!empty($itemnL['notaryList'])){
                $a[] = $itemnL['notaryList'];
            }
        }

        $nlMass = explode(',',$this->data['notaryL']['notaryList']);
        $this->data['deal'] = $this->model->getDeal($this->data['notary'],$grMass,$a);

       //print_r($_POST);
        if($_POST){
            $this->data['takeD'] = $this->model->takeDeal($this->data['notary']['id'],$_POST['deID']);
            if($this->data['takeD']!=1) {
                Session::setFlash($this->data['takeD']);

                $this->data['check'] = $this->model->checkDeal($_POST['deID']);
                if(empty($this->data['check'])){
                    Router::redirect('/notary/dashboard/');
                }
                //print_r($this->data['takeD']);
            }
            else{
                if($_POST['homeCheck']==0) {
                    $place = 'В офисе';
                }
                elseif($_POST['homeCheck']==1){
                    $place = 'С выездом на дом';
                }
                Session::setFlash('Вы приняли заказ №'.$_POST['deID']);
                ?>
                <script type="text/javascript">
                    var CLIENT_ID = '777080281097-vjo87rsuqa0sf6p7bfiu8m8t5qmq7hoh.apps.googleusercontent.com';
                    var API_KEY = 'AIzaSyCAIvPJBfcjK9KofUiSPmH0ig-QrWCNvlQ';
                    var DISCOVERY_DOCS = ["https://www.googleapis.com/discovery/v1/apis/calendar/v3/rest"];
                    var SCOPES = "https://www.googleapis.com/auth/calendar";
                    function handleClientLoad() {
                        gapi.load('client:auth2', initClient);
                    }
                    function initClient() {
                        gapi.client.init({
                            apiKey: API_KEY,
                            clientId: CLIENT_ID,
                            discoveryDocs: DISCOVERY_DOCS,
                            scope: SCOPES
                        }).then(function () {});
                    }
                    var test = {
                        eventName: '<?php echo $_POST['servicesName'];?>',
                        location: '<?php echo $place?>',
                        description: 'Сделка с клиентом',
                        start: {
                            dateTime: '<?echo $_POST['date'];?>T<?echo $_POST['time'];?>'
                        },
                        end: {
                            dateTime: '<?echo $_POST['date'];?>T<?echo $_POST['time'];?>'
                        }
                    }
                    function sendCustomEvent(data) {
                        var event = {
                            'summary': data.eventName,
                            'location': data.location,
                            'description': data.description,
                            'start': {
                                'dateTime': data.start.dateTime,
                                'timeZone': 'Europe/Kiev'
                            },
                            'end': {
                                'dateTime':  data.end.dateTime,
                                'timeZone':  'Europe/Kiev'
                            },
                            'reminders': {
                                'useDefault': false,
                                'overrides': [
                                    {'method': 'email', 'minutes': 24 * 60},
                                    {'method': 'popup', 'minutes': 10}
                                ]
                            }
                        };

                        var request = gapi.client.calendar.events.insert({
                            'calendarId': 'primary',
                            'resource': event
                        });

                        request.execute(function(event) {
                            //console.log(event)
                            setInterval(function() {location.reload();}, 1000);
                        });

                    }
                    setTimeout(function(){
                        sendCustomEvent(test);
                    }, 2000);
                </script>
                <script async defer src="https://apis.google.com/js/api.js"
                        onload="this.onload=function(){};handleClientLoad()"
                        onreadystatechange="if (this.readyState === 'complete') this.onload()">
                </script>
                <?
            }
        }

        $this->data['getDeal'] = $this->model->getDealForNotary($this->data['notary']['id']);

        $this->data["notaryDash"]['notaryID'] = $this->data['notary'];
        $this->data["notaryDash"]['grMass'] = $grMass;
        $this->data["notaryDash"]['notaryList'] = $a;
        //print_r($this->data['notary']);
        //print_r($a);

//        echo "<pre>";
//        print_r($this->data['deal']);
//        echo "</pre>";
    }

    public function notary_deal(){
        $param = App::getRouter()->getParams();
        $this->data['getDealByID'] = $this->model->getDealByID($param[0]);
        $this->data['getDealsByNotary'] = $this->model->getDealsByNotary(Session::get('login'));
        //print_r($this->data['getDealsByNotary']);
    }
    public function user_deal(){
        $param = App::getRouter()->getParams();
        $this->data['getDealByID'] = $this->model->getDealByID($param[0]);

        $this->data['listOfDoneDeals'] = $this->model->getListDoneDeal(Session::get('login'));
      if($_POST['rating'] || $_POST['userComment']){
          $edit = $this->model->editDealRating($_POST,$param[0]);
          //print_r($edit);
          if(!empty($edit)){
              Session::setFlash('Ваш отзыв принят!');
                ?><script>setInterval(function() {location.replace("/user/dashboard/deal/");}, 2000);</script><?
          }
      }

        $this->data['currentDoneDeal'] = $this->model->getCurDoneDeal($param[0]);
    }
    public function admin_all(){
        $param = App::getRouter()->getParams();
        if(isset($param)){
            $this->data['jdm'] = $this->model->judasCheck($param[0]);
            if($this->data['jdm']['notaryID']==0) {
                $this->data['getDealByID'] = $this->model->getDealByID($param[0]);
            }
            else{
                $this->data['getDealByID'] = $this->model->getDealByIDall($param[0]);
            }
            //print_r($this->data['getDealByID']);
            //print_r($_POST);
            if($_POST){
                $succes = $this->model->updStatDeal($_POST,$param[0]);
                if($succes){
                    Router::redirect('/admin/dashboard/all/'.$param[0].'/');
                }
            }

            $this->data['state'] = $this->model->getStateDealbyID($param[0]);
        }

    }
    public function admin_new(){
        $param = App::getRouter()->getParams();
        $this->data['notaryGroups'] = $this->model->getGroups();
        if(isset($param)){
            $this->data['getDealByID'] = $this->model->getDealByID($param[0]);
            //print_r($this->data['getDealByID']);
        }
    }
    public function admin_canceled(){
        $param = App::getRouter()->getParams();
        $this->data['notaryGroups'] = $this->model->getGroups();
        if(isset($param)){
            $this->data['getDealByID'] = $this->model->getDealByID($param[0]);

            $this->data['state'] = $this->model->getStateDealbyID($param[0]);
        }
    }
    public function admin_done(){
        $param = App::getRouter()->getParams();
        if(isset($param)){
            $this->data['getDealByID'] = $this->model->getDealByID($param[0]);

            $this->data['state'] = $this->model->getStateDealbyID($param[0]);
        }
    }
    public function admin_appointed(){
        $param = App::getRouter()->getParams();
        if(isset($param)){
            $this->data['getDealByID'] = $this->model->getDealByID($param[0]);

            $this->data['state'] = $this->model->getStateDealbyID($param[0]);
        }
    }
    public function admin_processed(){
        $param = App::getRouter()->getParams();
        if(isset($param)){
            $this->data['getDealByID'] = $this->model->getDealByID($param[0]);

            $this->data['state'] = $this->model->getStateDealbyID($param[0]);
        }
    }
    
}