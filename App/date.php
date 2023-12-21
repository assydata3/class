<?php 


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
}


$data = new date_data ; 
$current = $data->current_datetime() ; 
print_r($current); 



// echo date_create('2023-12-21')
// // ->modify('first day of this month')
// ->format('d/M/Y');
?>