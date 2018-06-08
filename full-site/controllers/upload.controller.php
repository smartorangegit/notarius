<?php
header('Content-type:application/json;charset=utf-8');
class UploadController extends Controller
{

    public function __construct($data = array())
    {
        parent::__construct($data);
        $this->model = new Upload();

    }

    public function upload()
    {
     $typeFull = explode('/',$_FILES['image']['type'][0]);
     $this->data['main'] = $this->user->uploadFiles($_FILES,$_SESSION['login'],$typeFull[1]);
    }
}