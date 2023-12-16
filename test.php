<?php 

require_once __DIR__.'/00_connect/connect.php'; 
use Connect\connect\conn_db;

$connection = new conn_db() ; 

class test {
   
    var $te = '123' ; 

    var $test2 = '123456'.'afdkjfd'; 

  

    public function show_test(){
        $test_thu = $this->te; 
        echo "gia tri tesst la : $test_thu<br>" ; 
    }

    public function show_test2(){
        $test_thu2 = $this->test2; 
        $this -> show_test() ; 
        echo "gia tri tesst la : $test_thu2<br>" ; 
    }
}

$t = new test ; 
$t->show_test();
$t->show_test2();


?>