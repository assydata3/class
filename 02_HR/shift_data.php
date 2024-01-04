<?php 

require_once __DIR__.'/../00_connect/connect.php'; 
use Connect\connect\conn_db ; 

class shift_data{
   
    /// LIST DATA 
    #### list tổng hợp số lượng người đi các ca trong ngày , dùng cho trang 
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
    

   public function check_shift_person($per_code,$month_check){
    $shift_person = array() ; $k = 0 ; 
    $month_current = date('Y-m') ; 
    if($month_check == ''){$month_check = $month_current ; }
    $connection = new conn_db ; 
    $connect = $connection->conn_assy2_2() ; 
    
    if($per_code == '') {
        $sql = "SELECT * FROM shift_working_assy as shift   
                INNER JOIN hr_control.line_info as line     
                ON shift.code = line.code WHERE shift.month = '$month_check' " ;   }


    else {
        $sql = "SELECT * 
        FROM shift_working_assy as shift   
        INNER JOIN hr_control.line_info as line     
        ON shift.code = line.code 
        WHERE shift.month = '$month_check'  and shift.code = '$per_code'" ;   }

    $result = mysqli_query($connect,$sql) ; 
    if($result){
        while($row = mysqli_fetch_array($result)){
            $k++  ; 
            $shift_person[$k]['code'] = $row['code'] ; 
            $shift_person[$k]['name'] = $row['fullname'] ; 
            $shift_person[$k]['type'] = $row['type'] ; 
            $shift_person[$k]['area'] = $row['area'] ; 
            for($i=1;$i<=31;$i++){
                $shift_temp = $row["d$i"] ; 
                if($shift_temp == 'null') $shift_temp = '' ; 
                $shift_person[$k]["d$i"] = $shift_temp ; 
            }
        } 
    }
    $shift_person["count"]["value"] = $k ;
    return $shift_person ;
   }
    
   
   public function update_shift_person($code,$month,$shift_array){
    $connection = new conn_db ; 
    $connect = $connection->conn_assy2_2() ;
    $require = "code=$code";  
    for($i=1;$i<=31;$i++){
        $data = $shift_array[$i] ; 
        if($data!==''){ $require = $require.",d$i='$data'" ;  }
    } 
    $sql = "UPDATE shift_working_assy SET $require WHERE (code = '$code' and month = '$month')" ; 
    mysqli_query($connect, $sql) ;

   }

   
}


// $shift = new shift_data ; 
// $update_array = array();
// for($i=1;$i<=31;$i++){$update_array[$i] = $i ; }
// print_r($update_array);
// $shift->update_shift_person('','',$update_array);  
?>