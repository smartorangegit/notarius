<?php
include(ROOT.DS.'models'.DS.'paymant.php');
class PaymentsController extends Controller
{

    public function __construct($data = array())
    {
        parent::__construct($data);
        $this->model = new Payments();
    }

    public function index()
    {

    }
    public function admin_index()
    {
        $this->data['listPayments'] = $this->model->getListPayments();
    }
}
