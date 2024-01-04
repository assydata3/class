<?php 

require_once  __DIR__.'/../00_connect/connect.php'; 
use Connect\connect\conn_db;

class overtime{
     
    #### tính tổng OT trong năm của 1 person theo mã code 
    public function overtime_person($per_code){
        $year_current = date('Y'); 
        $connection = new conn_db ; 
        $connect = $connection->conn_assy_sub()  ; 
        $sql = "SELECT sum(total_time) as sum FROM assy.overtime where (`code` like '$per_code' and `date` like '%$year_current%')" ;
        $result = mysqli_query($connect,$sql); 
        if($result){ $row = mysqli_fetch_array($result); $total_sum = $row['sum'] ; if($total_sum=='') {$total_sum = 0 ;}  } else  $total_sum = 0 ; 
        return $total_sum ;


    }
}


?>