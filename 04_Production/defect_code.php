<?php 

require_once __DIR__.'/../00_connect/connect.php' ; 
use Connect\connect\conn_db;

class defect_code{
    public function defect_infor($defect_code_index){
        $connection = new conn_db ; 
        $connect = $connection->conn_assy() ; 
        $defect_code = array() ; 
        $sql = "SELECT * FROM defect_code WHERE defect_code = '$defect_code_index' " ; 
        $result = mysqli_query($connect, $sql) ; 
        $row = mysqli_fetch_array($result) ; 
        $defect_code['df_code']    = $row['defect_code'] ; 
        $defect_code['defect_name']= $row['defect_name'] ; 
        $defect_code['jp_name']    = $row['jp_name'] ; 
        $defect_code['area']       = $row['area'] ; 
        $defect_code['line']       = $row['line'] ; 
        $defect_code['process']    = $row['process'] ; 
        $defect_code['rank']       = $row['rank'] ;
        $defect_code['process_no'] = $row['process_no'] ;
        $defect_code['defect_no']  = $row['defect_no'] ;
        $defect_code['pchart']     = $row['pchart'] ;
        $defect_code['no_count']   = $row['no_count'] ;
        $defect_code['price']      = $row['price'] ;
        
        return $defect_code ;


    }
}

?>