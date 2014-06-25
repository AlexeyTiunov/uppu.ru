<?php
class Controller{
    protected $f3;
    protected $db;

    function beforeroute(){

    }

    function afterroute(){
        echo Template::instance()->render('layout.htm');
    }

    function __construct(){
        $f3=Base::instance();

        $this->f3 = $f3;
        $this->db = \Registry::get('DB');   
    }
}