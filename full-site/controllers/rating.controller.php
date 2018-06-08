<?php
include(ROOT.DS.'models'.DS.'rating.php');
class RatingController extends Controller
{

    public function __construct($data = array())
    {
        parent::__construct($data);
        $this->model = new Rating();
    }
    public function admin_index(){
        $this->data = $this->model->getRatingList();
    }
}