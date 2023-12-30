<?php 
require_once  __DIR__.'/../00_connect/connect.php'; 
require_once  __DIR__.'/../00_connect/00_const.php'; 
use Connect\connect\conn_db;

class person{
   
    public function person_infor($code){
        $infor = array() ; 
        $connection  = new conn_db() ; 
        $conn = $connection -> conn_hr() ; 

        $sql = "SELECT * 
        FROM `personal_info` as info 
        INNER JOIN common_info as common ON info.code = common.code 
        INNER JOIN line_info as line ON info.code = line.code 
        WHERE (info.code = '$code')"; 

		$result = mysqli_query($conn,$sql); 
		$row = mysqli_fetch_assoc($result); 

        $infor['code']           = $row['code'] ;
        $infor['fullname']       = $row['fullname'] ; 
        $infor['position']       = $row['position'] ; 
        $infor['working_status'] = $row['working_status'] ; 
        $infor['gender']         = $row['gender'] ; 

        $old_code = $row['old_code'] ; 
        if($old_code == 'null') {$old_code = '' ; }

        $infor['old_code']       = $old_code; 
        $infor['join_date']      = $row['join_date'] ; 
        $infor['type']           = $row['type'] ; 
        $infor['image']          = $row['image'] ; 
        $infor['check_direct']   = $row['check_direct'] ; 
        $infor['baby_hol_from']  = $row['baby_hol_from'] ; 
        $infor['baby_hol_to']    = $row['baby_hol_to'] ; 
        $infor['resign_date']    = $row['resign_date'] ; 

        $resign_reason = $row['resign_reason'] ; 
        if($resign_reason == 'null') {$resign_reason = '' ; }
        $infor['resign_reason']  = $resign_reason ; 


        $infor['married_status'] = $row['married_status'] ; 

        $degree = $row['degree'] ; 
        if( $degree == 'null') { $degree = '' ; }
        $infor['degree']     =  $degree ; 

        $infor['birthday']   =  $row['birthday'] ; 
        $infor['tel']        =  $row['tel'] ; 
        $infor['address']    =  $row['adress'] ; 
        $infor['leader']     =  $row['leader'] ; 
        $infor['line']       =  $row['line'] ;
        $infor['area']       =  $row['area'] ;
        $infor['pro_sys_name'] =  $row['pro_sys_name'] ;
        $infor['main_process'] =  $row['main_process'] ;

        return $infor ;  
    }
}


// require '../../data.php' ; 
// $person = new person ; 
// $data = $person->person_infor('1048') ; 

// show_array($data)

?>