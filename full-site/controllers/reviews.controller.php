<?php
include(ROOT.DS.'models'.DS.'reviews.php');
include(ROOT.DS.'models'.DS.'board.php');
class ReviewsController extends Controller
{

    public function __construct($data = array())
    {
        parent::__construct($data);
        $this->model = new Review();
        $this->dashboard = new Board();
    }

    public function user_index()
    {
        if (Session::get('login')){
            $this->data['listOfDoneDeals'] = $this->dashboard->getListDoneDeal(Session::get('login'));

            $this->model->getComments($this->data['listOfDoneDeals']);

            $this->data['list'] = $this->model->getList($this->model->getComments($this->data['listOfDoneDeals']));
        }
        else{
            Router::redirect('/user/users/login/');
        }
    }
}