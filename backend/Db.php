<?php class Db {

    private function __construct(){

    }


    private static $_instance = null;


    public function getInstance(){
        if(is_null(self::$_instance)) {

            try {
                self::$_instance = new PDO('mysql:host=localhost;dbname=firstApp_1', 'root','');
                self::$_instance -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch(PDOException $e) {
                echo "Error" . $e-> getMessage();
            }
        } 
        return self::$_instance;
    }
}