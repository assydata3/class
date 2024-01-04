<?php 

require_once __DIR__.'/../00_connect/connect.php'; 
require_once __DIR__ .'/shift_data.php';
require_once __DIR__ .'/holiday.php';
use Connect\connect\conn_db ; 


class hr_data{
   
   public function list_shift_summary($date){
      $shift_summary = array() ; 
      $shift = new shift_data ; 
      $holiday = new holiday_data ; 
      $n = $shift->shift_date_total($date,'N') ; 
      $s1 = $shift->shift_date_total($date,'S1') ; 
      $s2 = $shift->shift_date_total($date,'S2') ; 
      $s3 = $shift->shift_date_total($date,'S3') ; 

      $n_hol   = $holiday->holiday_date_total($date,'N'); 
      $s1_hol  = $holiday->holiday_date_total($date,'S1'); 
      $s2_hol  = $holiday->holiday_date_total($date,'S2'); 
      $s3_hol  = $holiday->holiday_date_total($date,'S3'); 
       
      $sang =  $n + $s1 ; 
      $chieu = $n + $s2 ; 
      $toi   = $s3 ; 
      $sum   = $n + $s1 + $s2 + $s3 ; 
      
      $sang_hol = $n_hol + $s1_hol ; 
      $chieu_hol = $n_hol + $s2_hol ;
      $toi_hol = $s3_hol ; 

      $sum_co = $n + $s1 + $s2 + $s3 -$n_hol -$s1_hol -$s2_hol - $s3_hol  ;
      $sum_rate = round($sum_co/$sum*100) ; 
    
      if ($n == 0)  {$n_rate = 0 ; }  else {$n_rate  = round(($n -$n_hol)/$n*100 ) ;  }
      if ($s1 == 0) {$s1_rate = 0 ; } else {$s1_rate = round(($s1-$s1_hol)/$s1*100 ) ;   }
      if ($s2 == 0) {$s2_rate = 0 ; } else {$s2_rate = round(($s2-$s2_hol)/$s2*100 ) ;  }
      if ($s3 == 0) {$s3_rate = 0 ; } else { $s3_rate = round(($s3-$s3_hol)/$s3*100 ) ;  }
      
      $sang_rate  = round(($sang-$sang_hol)/$sang*100 ) ;
      $chieu_rate = round(($chieu-$chieu_hol)/$chieu*100 ) ;
      $toi_rate   = round(($toi-$toi_hol)/$toi*100 ) ;


      $shift_summary['N']['title']     = 'Ca N' ; 
      $shift_summary['S1']['title']    = 'Ca 1' ; 
      $shift_summary['S2']['title']    = 'Ca 2'; 
      $shift_summary['S3']['title']    = 'Ca 3' ; 
      $shift_summary['sum']['title']   = 'Tổng' ; 
      $shift_summary['sang']['title']  = 'Nhân sự buổi sáng' ; 
      $shift_summary['chieu']['title'] = 'Nhân sự buổi chiều' ; 
      $shift_summary['toi']['title']   = 'Nhân sự buổi đêm'  ;

      $shift_summary['N']['total']  = $n ; 
      $shift_summary['S1']['total'] = $s1 ; 
      $shift_summary['S2']['total'] = $s2 ; 
      $shift_summary['S3']['total'] = $s3 ; 
      $shift_summary['sum']['total'] = $sum ; 
      $shift_summary['sang']['total']  = $sang ; 
      $shift_summary['chieu']['total'] = $chieu ; 
      $shift_summary['toi']['total']   = $toi  ;

      $shift_summary['N']['co']  = $n - $n_hol ; 
      $shift_summary['S1']['co'] = $s1 - $s1_hol; 
      $shift_summary['S2']['co'] = $s2 - $s2_hol ; 
      $shift_summary['S3']['co'] = $s3 - $s3_hol; 
      $shift_summary['sum']['co'] = $sum_co  ; 
      $shift_summary['sang']['co']  = $sang - $sang_hol; 
      $shift_summary['chieu']['co'] = $chieu - $chieu_hol; 
      $shift_summary['toi']['co']   = $toi - $toi_hol ;


      $shift_summary['N']['rate']   = $n_rate ; 
      $shift_summary['S1']['rate']  = $s1_rate; 
      $shift_summary['S2']['rate']  = $s2_rate ; 
      $shift_summary['S3']['rate']  = $s3_rate; 
      $shift_summary['sum']['rate']  = $sum_rate ;  
      $shift_summary['sang']['rate']  = $sang_rate; 
      $shift_summary['chieu']['rate'] = $chieu_rate; 
      $shift_summary['toi']['rate']   = $toi_rate ;

      return $shift_summary ;
   }
  
   
   #### List danh sách theo các đặc thù nhân sự . 
   public function list_hr_type($type,$search){
      $connection = new conn_db ; 
      $connect = $connection->conn_hr();
      $list = array() ; $k = 0 ; 

      $sql = "SELECT * 
      FROM common_info 
      INNER JOIN line_info ON common_info.code = line_info.code 
      INNER JOIN personal_info ON common_info.code = personal_info.code 
      WHERE (common_info.working_status like '$type' 
      and (line_info.area like '%$search%' or line_info.pro_sys_name like '%$search%' or common_info.code like '%$search%' or common_info.fullname like '%$search%')) 
      order by stt asc" ; 
      

      ### kiểm tra các điều kiện
      if($type == "After born")     { $start_feild = "baby_hol_from" ; $end_feild = "baby_hol_to";  }
      if($type == "maternity leave"){ $start_feild = "baby_hol_from" ; $end_feild = "baby_hol_to";  }
      if($type == "pregnant"){ $start_feild = "baby_hol_from" ; $end_feild = "baby_hol_to";  }

      $result = mysqli_query($connect,$sql); 
      while($row=mysqli_fetch_array($result)){
         $k++; 
         $list[$k]['area']  = $row['area']  ; 
         $list[$k]['line']  = $row['line']  ; 
         $list[$k]['code']  = $row['code']  ; 
         $list[$k]['name']  = $row['fullname']  ; 
         $list[$k]['start'] = $row[$start_feild]  ; 
         $list[$k]['end']   = $row[$end_feild]  ; 
         
         
      }
      $list['count']['value'] = $k ; 
      return $list ;


   }



   public function list_hr($search){
      $connection = new conn_db ;   
      $connect = $connection->conn_hr();
      $hr_list = array() ; $k = 0  ; 
    
      $sql="SELECT * FROM common_info 
       INNER JOIN line_info ON common_info.code = line_info.code 
       INNER JOIN personal_info ON common_info.code = personal_info.code 
       where ((common_info.code like'%$search%' or common_info.fullname like'%$search%' or line_info.pro_sys_name like'%$search%' or personal_info.degree like'%$search%' or common_info.working_status like'%$search%' or common_info.old_code like'%$search%' or common_info.position like'%$search%' or line_info.line like'%$search%') and (common_info.working_status <> 'Leave Job')) 
       order by stt asc";

      $result = mysqli_query($connect, $sql);
      while($row=mysqli_fetch_array($result)){
         $k++ ; 
         $hr_list[$k]["code"]        = $row["code"] ;
         $hr_list[$k]["name"]        = $row["fullname"] ;
         $hr_list[$k]["position"]    = $row["position"] ;
         $hr_list[$k]["wk_status"]   = $row["working_status"] ;
         $hr_list[$k]["old_code"]    = $row["old_code"] ;
         $hr_list[$k]["degree"]      = $row["degree"] ;
         $hr_list[$k]["birthday"]    = $row["birthday"] ;
         $hr_list[$k]["line"]        = $row["line"] ;

      }
      $hr_list["count"]["value"] = $k ;   
      return $hr_list ;
   }
   


   
   
}


// $hr = new hr_data ;
// $data = $hr->list_shift_summary("2023-12-28") ;

// print_r($data);

?>