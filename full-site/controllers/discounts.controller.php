<?php
include(ROOT.DS.'models'.DS.'discounts.php');
class DiscountsController extends Controller
{

    public function __construct($data = array())
    {
        parent::__construct($data);
        $this->model = new Discount();
    }
    public function index()
    {
    }
    public function admin_index(){
        $this->data['nList'] = $this->model->getNotaryList();
        $this->data['sList'] = $this->model->getServiceList();
        //создаем добавление скидки-акции
        if($_POST['discount_add']){
            if($this->model->editDiscount($_POST,$_POST['id'])){
                Router::redirect('/admin/discounts/');
            }

        }
        //выводим список акций
        $this->data['listOfDiscounts'] = $this->model->getListOfDiscounts();

    }
    public function admin_offer(){
        $this->data['nList'] = $this->model->getNotaryList();
        $this->data['sList'] = $this->model->getServiceList();
        $this->data['info'] = App::getRouter()->getParams();

        if($_POST['discount_upd']){
            if($this->model->editDiscount($_POST,$_POST['id'])){
                Router::redirect('/admin/discounts/');
            }
        }
        if($_POST['discount_delete']){
            if($this->model->deleteDiscount($_POST['id'])){
                Router::redirect('/admin/discounts/');
            }
        }
        $this->data['listOfDiscounts'] = $this->model->getListOfDiscounts($this->data['info'][0]);
//        print_r($this->data['listOfDiscounts'] );
    }
}