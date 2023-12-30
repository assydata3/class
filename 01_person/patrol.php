<?php 
require_once  __DIR__.'/../00_connect/connect.php'; 
use Connect\connect\conn_db;

class patrol {
    #### LIST Patrol data 
    public function list_patrol(){
        $connection = new conn_db();
        $conn_test = $connection->conn_test();
        $patrol_list = array() ; $k = 0 ; 
        $sql = "SELECT * FROM patrol where result is NULL " ; 
        $result = mysqli_query($conn_test,$sql) ;  
        while($row = mysqli_fetch_array($result)){
            $k++ ; 
            $patrol_list[$k]['no']          = $row['no'] ; 
            $patrol_list[$k]['date_patrol'] = $row['date_patrol'] ; 
            $patrol_list[$k]['date_backup'] = $row['date_backup'] ; 
            $patrol_list[$k]['infor']       = $row['infor'] ; 
            $patrol_list[$k]['result']      = $row['result'] ; 
           
        }
        $patrol_list['count']['value'] = $k ; 
        return $patrol_list ;

    }


    ### UPDATE DATABASE 
    public function update_patrol_infor($no,$result){
        $connection = new conn_db();
        $conn_test = $connection->conn_test();
        $sql_update = "UPDATE `patrol` SET `infor`='v' ,  `result`= '$result' WHERE no = '$no' "; 
        mysqli_query($conn_test,$sql_update); 
     }

    public function update_patrol_date($date_patrol,$date_backup){
       $connection = new conn_db();
       $conn_test = $connection->conn_test();
       $sql_update = "UPDATE `patrol` SET `infor`='v' , `result`= '$date_backup' WHERE (date_patrol like '$date_patrol')"; 
   	   mysqli_query($conn_test,$sql_update); 
    }


    public function insert_patrol($date_patrol,$date_backup){
       $connection = new conn_db();
       $conn_test = $connection->conn_test();
       $sql = "INSERT INTO `patrol` (`no`, `date_patrol`, `date_backup`)  VALUES ( NULL, '$date_patrol', '$date_backup')"; 
   	   mysqli_query($conn_test,$sql); 
    }


}




?>