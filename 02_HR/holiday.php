<?php 

require_once __DIR__.'/../00_connect/connect.php'; 

use Connect\connect\conn_db ; 

class holiday_data{
   
    /// LIST DATA 
    public function holiday_date_total($date,$shift){
        $connection = new conn_db ; 
        $connect = $connection->conn_hr() ; 
        $sql = "SELECT count(no) FROM `list_day_off` WHERE(start_date <= '$date' and end_date >= '$date' and `note` like '$shift')";
		$result = mysqli_query($connect, $sql);
		if($result){
            $row = mysqli_fetch_assoc($result);
            $count = $row['count(no)'];
        }
        else $count = 0 ;  
		
		return $count;
    }

   
    public function nghi_sinh_date($date_check){
        $connection = new conn_db ; 
        $connect = $connection->conn_hr() ; 
        $nghisinh = array() ; $k = 0 ; 
   
        $sql  = "SELECT  line.area , line.pro_sys_name ,  com.code , com.fullname , per.baby_hol_from , per.baby_hol_to , com.position 
        FROM hr_control.common_info as com
        INNER JOIN hr_control.line_info as line ON com.code = line.code 
        INNER JOIN hr_control.personal_info as per ON com.code = per.code 
        WHERE(com.working_status like 'maternity leave' and per.baby_hol_from <= '$date_check' and per.baby_hol_to >= '$date_check') 
        ORDER BY baby_hol_from ASC   " ; 
        
        $result = mysqli_query($connect, $sql) ;
        while($row=mysqli_fetch_array($result)){
           $k ++ ; 
           $nghisinh[$k]['area']         = $row['area'] ;
           $nghisinh[$k]['pro_sys_name'] = $row['pro_sys_name'] ;
           $nghisinh[$k]['code']         = $row['code'] ;
           $nghisinh[$k]['name']         = $row['fullname'] ;
           $bb_hol_from                  = $row['baby_hol_from'] ;
   
           $nghisinh[$k]['bb_hol_from']  = $bb_hol_from ; 
           if($bb_hol_from==$date_check){$color = 'red' ; } else {$color = '' ; }
   
           $nghisinh[$k]['bb_hol_to']    = $row['baby_hol_to'] ;
           $nghisinh[$k]['position']     = $row['position'] ;
           $nghisinh[$k]['color']        = $color ; 
   
        }
        $nghisinh['count']['value'] = $k ; 
        return $nghisinh ;
      }





   
    public function holiday_list_group($date_check){
        $connection = new conn_db ; 
        $connect = $connection->conn_hr() ;
        $holiday = array() ; 
        
        #### Nghi sinh 
        $nghisinh = $this->nghi_sinh_date($date_check) ; 
        $nghisinh_count = $nghisinh['count']['value'] ; 
        
        $holiday[1]['title'] = 'Maternity leave' ; 
        $holiday[1]['count']  = $nghisinh_count ; 
        
        for($i=1;$i<=$nghisinh_count;$i++){
            $holiday[1]['data'][$i]['code']       = $nghisinh[$i]['code'] ; 
            $holiday[1]['data'][$i]['name']       = $nghisinh[$i]['name'] ; 
            $holiday[1]['data'][$i]['position']   = $nghisinh[$i]['position'] ; 
            $holiday[1]['data'][$i]['type_off']   = 'P' ; 
            $holiday[1]['data'][$i]['content']    = 'Maternity leave' ; 
            $holiday[1]['data'][$i]['start']      = $nghisinh[$i]['bb_hol_from'] ; 
            $holiday[1]['data'][$i]['end']        = $nghisinh[$i]['bb_hol_to'] ; 
            $holiday[1]['data'][$i]['total_day']  = '' ; 
            $holiday[1]['data'][$i]['note']       = '' ; 
            $holiday[1]['data'][$i]['color']      = $nghisinh[$i]['color'] ; 

        }
        

        ### Nghi phep cac loai 

        $holiday[2]['title'] = 'Worker offical' ; 
        $holiday[3]['title'] = 'Worker OS' ; 
        $holiday[4]['title']  = 'LD up' ; 
        $holiday[5]['title']  = 'Leave Job' ; 
        
        $wk_os_count = $wk_off_count = $ld_count = $leave_count = 0; 

        $sql = "SELECT temp.code , temp.fullname, temp.position , type_off, content , start_date , end_date , total_day , note , check_time   , type 
        FROM day_off_temp as temp
        INNER JOIN common_info as com ON temp.code = com.code 
        WHERE start_date  = '$date_check'  and temp.status <> 'Phê duyệt' and temp.status <> 'hủy'
        UNION 
        SELECT off.code , off.fullname, off.position , type_off, content , start_date , end_date , total_day , note , check_time   , type 
        FROM list_day_off as off 
        INNER JOIN common_info as com ON off.code = com.code 
        WHERE start_date  = '$date_check' ";
        
        $result = mysqli_query($connect, $sql);
        if($result){
            while($row = mysqli_fetch_array($result)){
                $arr_temp = array() ; 
                $position = $row['position'] ; 
                $check_time = $row['check_time'] ; 
                $arr_temp['code']       = $row['code'] ;
                $arr_temp['name']       = $row['fullname'] ;
                $arr_temp['position']   = $position ;
                $arr_temp['type_off']   = $row['type_off'] ;
                
                $content  = $row['content'] ;
                $start    = $row['start_date'] ;
                $end      = $row['end_date'] ;

                $start_s = date('d/m',strtotime($start)) ;
                $end_s  = date('d/m',strtotime($end)) ;
                $content_full = "$content($start_s->$end_s)" ; 

                $arr_temp["content"] = $content_full ; 
                $arr_temp["start"]   = $start ; 
                $arr_temp["end"]     = $end ; 
                $arr_temp["total_day"]     = $row['total_day'] ; 
                $arr_temp["note"]     = $row['note'] ; 
                if($check_time=='Ca chiều')        $arr_temp['color'] = 'red' ;  else $arr_temp['color'] = '' ; 
              


                $wk_lv  = ['Worker','Line support','Sub Leader'] ; 
                $type_level = $row['type'] ; 
                if(in_array($position,$wk_lv)){
                     if($type_level == 'Offical'){
                        $wk_off_count++ ; 
                        $holiday[2]['data'][$wk_off_count] = $arr_temp ; 
                     }
                     else {
                        $wk_os_count ++ ;
                        $holiday[3]['data'][$wk_os_count] = $arr_temp ; 
                     }
                }
                else {
                    $ld_count ++ ; 
                    $holiday[4]['data'][$ld_count] = $arr_temp ; 
                }
               
               
                
            }

            $holiday[2]['count']  = $wk_off_count ; 
            $holiday[3]['count']  = $wk_os_count ; 
            $holiday[4]['count']  = $ld_count ; 
         
         $date_query = date('Y-m',strtotime($date_check)).'%' ; 
         $sql_nghiviec = "SELECT com.code , com.fullname, com.position , line.line , per.resign_date , per.resign_reason 
         FROM common_info as com
         INNER JOIN line_info as line ON com.code = line.code 
         INNER JOIN personal_info as per ON com.code = per.code 
         WHERE (com.working_status like 'Leave Job' and per.resign_date like '$date_query' and per.resign_date <= '$date_check') 
         ORDER BY resign_date ASC" ; 
         
        //  echo $sql_nghiviec ;
         $result_nghiviec = mysqli_query($connect,$sql_nghiviec) ;
         while($row_nv = mysqli_fetch_assoc($result_nghiviec)){
            $leave_count++; 
            $holiday[5]['data'][$leave_count]['code']      = $row_nv['code'];
            $holiday[5]['data'][$leave_count]['name']      = $row_nv['fullname'];
            $holiday[5]['data'][$leave_count]['position']  = $row_nv['position'];
            $holiday[5]['data'][$leave_count]['type_off']  = $row_nv['line'];
            $holiday[5]['data'][$leave_count]['content']   = $row_nv['resign_reason'];
            $resign_date                                   = $row_nv['resign_date'] ; 
            $holiday[5]['data'][$leave_count]['start']     = $resign_date ;    
            $holiday[5]['data'][$leave_count]['end']       = '-';    
            $holiday[5]['data'][$leave_count]['total_day'] = '-';   
            $holiday[5]['data'][$leave_count]['note']      = date('d/m/Y',strtotime($resign_date));  
            if($resign_date == $date_check) {$color_nv = 'red' ; }  else { $color_nv = '' ; } 
            $holiday[5]['data'][$leave_count]['color']      = $color_nv ;  

        }

        
         $holiday[5]['count']  = $leave_count ; 
        }
        
       return $holiday ;

    }
    

}


// require_once __DIR__.'/../00_connect/01_tool.php'; 
// $tool = new tool_support ; 

// $holiday = new holiday_data ; 
// $data_hl = $holiday->holiday_list_group('2023-12-27') ; 
// $tool->show_array($data_hl) ;

?>