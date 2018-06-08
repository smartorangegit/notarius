<?php
include(ROOT.DS.'models'.DS.'blog.php');
class BlogController extends Controller{

    public function __construct($data = array()){
        parent::__construct($data);
        $this->model = new Blog();
    }

    public function index(){
        if ( $_POST ){
             if ( $this->model->save($_POST) ){
                 Session::setFlash('Thank you! Your message was sent successfully!');
                 Router::redirect('/blog');
             }
         }
        $this->data['blog']= $this->model->qqq();


    }
    public function edit(){
        if ( $_POST ){
            $id = isset($_POST['id']) ? $_POST['id'] : null;
            $result = $this->model->save($_POST, $id);
            if ( $result ){
                Session::setFlash('Page was saved.');
            } else {
                Session::setFlash('Error.');
            }
            Router::redirect('/blog');
        }

        if ( isset($this->params[0]) ){
            $this->data['blog'] = $this->model->getById($this->params[0]);
        } else {
            Session::setFlash('Wrong page id.');
            Router::redirect('/blog');
        }
    }

    public function delete()
    {
        if (isset($this->params[0])) {
        $result = $this->model->blog_delete($this->params[0]);
        if ($result) {
            Session::setFlash('Message was deleted.');
        } else {
            Session::setFlash('Error.');
        }
    }

      Router::redirect('/blog');
    }
    public  function pic(){

        echo "PIC";
		

    }
	public  function upload(){
		
	}
	




}