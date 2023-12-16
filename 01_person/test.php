<?php 
namespace Test\test ; 

// require_once __DIR__.'/../connect/connect.php'; 
use Connect\connect\conn_db ; 


class test_db{
    public function print_data(){
        
        $connect = new conn_db(); 
        $con_hr = $connect->conn_hr(); 
        $sql_list = "SELECT * FROM `line_info`"; 
        $result_list = mysqli_query($con_hr,$sql_list); 
        while($row_list = mysqli_fetch_assoc($result_list)){
            $code_find  = $row_list['code']; 
            echo "code : $code_find <br>" ; 
        }
    }
    
}



?>