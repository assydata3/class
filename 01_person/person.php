<?php 
require_once  __DIR__.'/../00_connect/connect.php'; 
require_once  __DIR__.'/../00_connect/00_const.php'; 
use Connect\connect\conn_db;

class person{
   
    public function check_name($code){
        $connection  = new conn_db() ; 
        $conn = $connection -> conn_hr() ; 
        $sql_find_name = "SELECT * FROM `common_info` WHERE (`code` like '$code')"; 
		$result_find_name = mysqli_query($conn,$sql_find_name); 
		$row_find_name = mysqli_fetch_assoc($result_find_name); 
        $name_check = $row_find_name['fullname'] ; 
        return $name_check; 
    }
}





?>