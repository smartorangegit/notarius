<?php

class App{

    protected static $router;

    public static $db;

    /**
     * @return mixed
     */
    public static function getRouter(){
        return self::$router;
    }

    public static function run($uri){
        self::$router = new Router($uri);

        self::$db = new DB(Config::get('db.host'), Config::get('db.user'), Config::get('db.password'), Config::get('db.db_name'));

        Lang::load(self::$router->getLanguage());

        $controller_class = ucfirst(self::$router->getController()).'Controller';
        $controller_method = strtolower(self::$router->getMethodPrefix().self::$router->getAction());

        $layout = self::$router->getRoute();
        if ( $layout == 'admin' && Session::get('role') != 'admin' ){
            if ( $controller_method != 'admin_login' ){
                Router::redirect('/admin/users/login');
            }
        }
        elseif ($layout == 'user' && Session::get('role') != 'user'){
            if ($controller_method != 'user_login'){
               //Router::redirect('/user/users/login');
            }
        }
        elseif ($layout == 'notary' && Session::get('role') != 'notary') {
            if ($controller_method != 'user_notary') {
                // Router::redirect('/user/users/login');
            }
        }

        // Calling controller's method
        $controller_object = new $controller_class();
        if ( method_exists($controller_object, $controller_method) ){
            // Controller's action may return a view path
            $view_path = $controller_object->$controller_method();
            $view_object = new View($controller_object->getData(), $view_path);
            $content = $view_object->render();
        } else {
            throw new Exception('Method '.$controller_method.' of class '.$controller_class.' does not exist.');
        }

        $layout_path = VIEWS_PATH.DS.$layout.'.php'; //задаем формат страниц default,admin etc
        $layout_view_object = new View(compact('content'), $layout_path);
        //echo $layout_view_object->render();

        //если мы хотим использовать js и передаем методом GET страница не будет ренедерить view
        //  и таким образом контролер вернет нам только js/json который мы можем вывести
        if($_GET['ajax_data']){
            //print_r($_GET);
        }
        else {
            echo $layout_view_object->render();
        }

    }

}