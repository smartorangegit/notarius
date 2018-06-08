<?php

class ContactsController extends Controller{

    public function __construct($data = array()){
        parent::__construct($data);
        $this->model = new Message();
    }

    public function index(){
        Router::redirect('/pages/view/contacts/');
        if ( $_POST ){
            if ( $this->model->save($_POST) ){
                Session::setFlash('Thank you! Your message was sent successfully!');
            }
        }
    }

    public function admin_index(){
        $this->data = $this->model->getList();
    }
    public function admin_info(){
        if($_POST) {
            $this->model->info($_POST);
        }
        $this->data['info'] = $this->model->getInfo();
    }

}