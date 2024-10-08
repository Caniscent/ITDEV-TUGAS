<?php

class App {
    protected $controller = 'HomeController';
    protected $method = 'index';
    protected $params = [];
    public function __construct()
    {
        $url = $this->parseURL();
        //prefix cek data kosong
        // otomatis akan di isi dashboard
        if ($url == null){
            $url = ["Index"];
          
        }
        // controller
        if (file_exists("app/controllers/" . $url[0]. "Controller" . ".php")){
            $url[0] = $url[0] . "Controller";
            $this->controller = $url[0];
            unset($url[0]);
        }

        require_once "app/controllers/" . $this->controller . ".php";
        $this->controller = new $this->controller;


        // method
        if (isset($url[1])) {
            if (method_exists($this->controller, $url[1])){
                $this->method = $url[1];
                unset($url[1]);
            }
        }
        if( !empty($url)) {
            
            $this-> params = array_values($url);
          
        }
        // jika ada bagian url lain maka anggap sebagai params
        $this->params = $url ? array_values($url) : [];
        // jalankan controller & method, serta kirimkan params jika ada
        call_user_func_array([$this->controller, $this->method], $this->params);
        
    }
    // params
    public function parseURL(){
        $url = '';
        if (isset($_GET["url"])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
        }

        $urlParts = explode('/', $url);
        
        // Cek jika ada parameter di URL
        if (isset($_GET['id'])) {
            $urlParts[] = $_GET['id'];
        }

        return $urlParts;
    }

}