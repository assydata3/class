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
  

   


   
   
}


// $hr = new hr_data ;
// $data = $hr->list_shift_summary("2023-12-28") ;

// print_r($data);

?>