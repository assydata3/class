<?php 

require_once __DIR__.'/../00_connect/connect.php'; 
use Connect\connect\conn_db ; 

class shift_data{
   
    /// LIST DATA 
    public function shift_date_total($date,$shift){
        $day_value   = (int) date('d',strtotime($date));
        $day_column = 'd'.$day_value ;
        $month_value = date('Y-m',strtotime($date));
        
        $connection = new conn_db ; 
        $connect = $connection->conn_assy2_2() ; 
        $sql = "SELECT COUNT(stt) FROM `shift_working_assy` WHERE(month like '$month_value' and d$day_value like '$shift')";
		$result = mysqli_query($connect, $sql);
        if($result){
            $row = mysqli_fetch_assoc($result);
            $out = $row['COUNT(stt)'];
        }
		else $out = 0 ; 
		return $out;
    }


    public function shift_change_list($date_check){
        $connection = new conn_db ; 
        $connect = $connection->conn_hr() ; 
        
        $shift_list = array() ; $k = 0 ; 
        $sql_shift = "SELECT * FROM list_change_shift 
        WHERE(start_date <= '$date_check' and end_date >= '$date_check' and note <> 'Đổi ca theo list') 
        ORDER BY note,check_time DESC";

        $result_shift = mysqli_query($connect, $sql_shift);
        if($result_shift){
            while($row_shift = mysqli_fetch_assoc($result_shift)){
                 $k ++ ; 
                 $shift_list[$k]['code']  = $row_shift['code']; 
                 $shift_list[$k]['name' ] = $row_shift['fullname'];
                 $shift_list[$k]['shift_current'] = $row_shift['shift_current']; 
                 $shift_list[$k]['shift_change']  = $row_shift['shift_change']; 
                 $shift_list[$k]['start']         = $row_shift['start_date']; 
                 $shift_list[$k]['end']           = $row_shift['end_date']; 
                 $shift_list[$k]['total_day']     = $row_shift['total_day']; 
                 $shift_list[$k]['end']           = $row_shift['end_date']; 
                 $shift_list[$k]['note']          = $row_shift['note']; 
                 $shift_list[$k]['color']         = '' ; 

            }
        }
        $shift_list['count']['value'] = $k ; 
        return $shift_list ;          
    }
    

}



?>