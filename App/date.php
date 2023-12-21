<?php 
require_once __DIR__.'/../00_connect/connect.php' ; 

use Connect\connect\conn_db;

class date_data{
    public function fist_day($date){
        $fist_day = date_create($date)
        ->modify('first day of this month')
        ->format('Y-m-d');

        return $fist_day;
    }


    public function last_day($date){
        $fist_day = date_create($date)
        ->modify('last day of this month')
        ->format('Y-m-d');
        return $fist_day;
    }


    public function current_datetime() {
        date_default_timezone_set('Asia/ho_chi_minh');
        $date_full = date('Y-m-d H:i:s');
        $today = date('Y-m-d'); 
        $current_time = date('H:i:s') ; 

        $date_array = array() ; 
        $date_value = getdate() ; 

        $fist_day_month = $this->fist_day($today); 
        $last_day_month = $this->last_day($today); 

        $date_array['today_full']   = $date_full ; 
        $date_array['today']        = $today ; 
        $date_array['time']         = $current_time ; 
        $date_array['week_day']     = $date_value['weekday'] ; 
        $date_array['wday']         = $date_value['wday'] ;
        $date_array['week_day']     = $date_value['weekday'] ; 
        $date_array['time_total']   = $date_value['0'] ; 
        $date_array['day_in_year']  = $date_value['yday'] ; 
        $date_array['month']        = $date_value['month'] ; 
        $date_array['mon']          = $date_value['mon'] ;
        $date_array['fist_d_mo']    = $fist_day_month ;
        $date_array['last_d_mo']    = $last_day_month ;

        return $date_array ; 
    }


    public function date_company_index($date_check){
      $connection = new conn_db() ; 
      $connect = $connection->conn_test() ; 
      $sql = "SELECT * FROM calenda WHERE start = '$date_check' " ; 
      $result = mysqli_query($connect, $sql) ;  
      $row = mysqli_fetch_assoc($result); 
      $index_one = $row['index_one'] ; 
      $index_all = $row['index_all'] ; 
      if($index_one == 1){$status = 'working' ; }
      if($index_one == 0){$status = 'holiday' ; }
      $note = $row['note'] ; 
      
      $index = array() ; 
      $index['date_check'] = $date_check ; 
      $index['index_one']  = $index_one ; 
      $index['index_all']  = $index_all  ; 
      $index['status']     = $status ; 
      $index['note']       = $note ; 

      return $index ;   
  
    } 

    public function index2date($index_all){  
        $connection = new conn_db() ;
        $connect = $connection->conn_test() ;
        $sql = "SELECT * FROM calenda WHERE index_all =  '$index_all'" ; 
        $result = mysqli_query($connect, $sql) ;
        $row = mysqli_fetch_assoc($result);
        $date_find = $row['start'] ; 

        return $date_find ;
    }

    ### tim ngay cach so ngay hien tai index gia tri 
    public function date_plus($date,$index){
       $date_infor = $this->date_company_index($date) ;
       $index_find  =  $date_infor['index_all'] ; 
       $index_diff  = $index_find + $index ; 
       $date_diff   = $this->index2date($index_diff) ;
       return $date_diff ;
    }
   
} 


// $data = new date_data ; 
// $current = $data->current_datetime() ; 
// print_r($current); 

// print_r($data->date_company_index('2023-12-21')) ; 
// print_r($data->index2date(1052)) ; 

// print_r($data->date_plus('2023-12-21',-10)) ; 

// echo date_create('2023-12-21')
// // ->modify('first day of this month')
// ->format('d/M/Y');
?>