<?php 
require_once __DIR__.'/../00_connect/connect.php' ; 

use Connect\connect\conn_db;

class daily_report{
    public function pro_daily_data($date_start,$date_current){
        $connection = new conn_db() ; 
        $connect = $connection->conn_report() ; 

        $sql = "SELECT no_all,no_1,area,line,model,pro_date,plan,actual, 
        (SELECT sum(sub_total_plan) FROM daily_production_report WHERE( (pro_date BETWEEN '$date_start' and '$date_current') and area = db.area  and area =  db.area and model = db.model )   ) as sub_total_plan, 
        (SELECT sum(sub_total_actual) FROM daily_production_report WHERE( (pro_date BETWEEN '$date_start' and '$date_current') and area = db.area  and area =  db.area and model = db.model )   ) as sub_total_actual, 
        plan-actual as diff_plan,
        sub_total_plan - sub_total_actual as diff_sub_plan, 
        plan/actual*100  as plan_finish , 
        sub_total_plan/sub_total_actual*100  as sub_plan_finish,
        date_compensate, 
        time_plan_1,qty_plan_1,time_actual_1,qty_actual_1,qty_plan_1/qty_actual_1*100 as plan_1_finish,
        time_plan_2,qty_plan_2,time_actual_2,qty_actual_2,qty_plan_2/qty_actual_2*100 as plan_2_finish,
        time_plan_3,qty_plan_3,time_actual_3,qty_actual_3,qty_plan_3/qty_actual_3*100 as plan_3_finish,
        process_code,change_point, abnormal,reason,countermeasure,pctp,pic,dead_line
        FROM daily_production_report as db
        WHERE pro_date like '$date_current'
        ORDER BY area , line , 'no_1'" ; 

        $result = mysqli_query($connect,$sql); 
        $data = array() ; $k = 0 ; 
        while( $row = mysqli_fetch_array($result) ){
            $k++ ; 
            $data[$k]['no_all'] = $row['no_all'] ;

            $no_1 = $row['no_1'] ;
            if(($no_1 >= 901) && ($no_1 <= 999)) $line_color = '#53d5d3';
			if(($no_1 >= 801) && ($no_1 <= 899)) $line_color = '#55d553';
			if(($no_1 >= 701) && ($no_1 <= 799)) $line_color = '#FFEB3B';
			if(($no_1 >= 601) && ($no_1 <= 699)) $line_color = '#f16ff3ab';
			if(($no_1 >= 501) && ($no_1 <= 599)) $line_color = '#b3b9ffab';

            $data[$k]['line_color']       = $line_color ;
            $data[$k]['no_1']             = $no_1;
            $data[$k]['area']             = $row['area'] ;
            $data[$k]['line']             = $row['line'] ;
            $data[$k]['model']            = $row['model'];
            $data[$k]['plan']             = $row['plan'] ;
            $data[$k]['actual']           = $row['actual'] ;
            $data[$k]['sub_total_plan']   = $row['sub_total_plan'] ;
            $data[$k]['sub_total_actual'] = $row['sub_total_actual'] ;
            
            $data[$k]['diff_plan']            = $row['diff_plan'] ;
            $data[$k]['diff_sub_plan']        = $row['diff_sub_plan'] ;
            $data[$k]['plan_finish']          = $row['plan_finish'] ;
            $data[$k]['sub_plan_finish']      = $row['sub_plan_finish'] ;
            $data[$k]['date_compensate']      = $row['date_compensate'] ;

            $data[$k]['time_plan_1']      = $row['time_plan_1'] ;
            $data[$k]['qty_plan_1']       = $row['qty_plan_1'] ;
            $data[$k]['time_actual_1']    = $row['time_actual_1'] ;
            $data[$k]['qty_actual_1']     = $row['qty_actual_1'] ;
            $data[$k]['plan_1_finish']    = $row['plan_1_finish'] ;

            $data[$k]['time_plan_2']      = $row['time_plan_2'] ;
            $data[$k]['qty_plan_2']       = $row['qty_plan_2'] ;
            $data[$k]['time_actual_2']    = $row['time_actual_2'] ;
            $data[$k]['qty_actual_2']     = $row['qty_actual_2'] ;
            $data[$k]['plan_2_finish']    = $row['plan_2_finish'] ;

            $data[$k]['time_plan_3']      = $row['time_plan_3'] ;
            $data[$k]['qty_plan_3']       = $row['qty_plan_3'] ;
            $data[$k]['time_actual_3']    = $row['time_actual_3'] ;
            $data[$k]['qty_actual_3']     = $row['qty_actual_3'] ;
            $data[$k]['plan_3_finish']    = $row['plan_3_finish'] ;

            $data[$k]['process_code']     = $row['process_code'] ;
            $data[$k]['change_point']     = $row['change_point'] ;
            $data[$k]['abnormal']         = $row['abnormal'] ;
            $data[$k]['reason']           = $row['reason'] ;
            $data[$k]['countermeasure']   = $row['countermeasure'] ;
            $data[$k]['pctp']             = $row['pctp'] ;
            $data[$k]['pic']              = $row['pic'] ;
            $data[$k]['dead_line']        = $row['dead_line'] ;




        }
        $data['count']['value'] = $k ; 
        return $data ;
    }



    public function defect_daily_data($pro_date){
        $connection = new conn_db() ; 
        $connect = $connection->conn_report() ; 


        $sql = "WITH CTE_DATA AS (
            SELECT date , no_1, area , line , model , code , defect_code, defect_name,ng_qty , ok_return , result, check_show,
            (SELECT SUM(actual) FROM daily_production_report WHERE area = db.area and  line = db.line and model = db.model and pro_date = '$pro_date') as sanluong , 
            (SELECT SUM(result) FROM daily_defect_report WHERE area = db.area and  line = db.line and model = db.model and date = '$pro_date') as tong_ng ,
            dense_rank() over(order by area , line , model DESC) as group_data
            FROM daily_defect_report as db 
            WHERE 
            date = '2023-12-18'
            and no_count = ''
            ORDER BY area , line , no_1) 
            
            SELECT * , 
            round(result / sanluong * 100 ,3) as ng_rate , 
            
            row_number() OVER(partition by group_data order by model) as stt
            FROM CTE_DATA " ; 

        $result = mysqli_query($connect,$sql); 
        $total = $temp = $temp2 = $info = $infor_temp = array()  ; $m1 = $m2 = 0 ; 
        while( $row = mysqli_fetch_array($result) ){
                
                $m1 = $row['group_data'] ; 
                $m2 = $row['stt'] ; 
                
                if($m2 == 1){
                  
                    $infor = array() ; 

                    $no1_show_1 = $row['no_1'] ; 
                    if(($no1_show_1 >= 901) && ($no1_show_1 <= 999)) $line_color = '#53d5d3';
                    if(($no1_show_1 >= 801) && ($no1_show_1 <= 899)) $line_color = '#55d553';
                    if(($no1_show_1 >= 701) && ($no1_show_1 <= 799)) $line_color = '#FFEB3B';
                    if(($no1_show_1 >= 601) && ($no1_show_1 <= 699)) $line_color = '#f16ff3ab';
                    if(($no1_show_1 >= 501) && ($no1_show_1 <= 599)) $line_color = '#b3b9ffab';
                
                    $infor['area']    = $row['area'] ; 
                    $infor['line']    = $row['line'] ;
                    $infor['model']   = $row['model'] ;
                    $infor['tong_ng'] = $row['tong_ng'] ;
                    $infor['color']   = $line_color ; 
                    
                    ### Add Infor Array 
                    $total[$m1]['infor']  = $infor  ; 
                }    
                    
                $total[$m1]['data'][$m2]['defect_code'] = $row['defect_code'] ; 
                $total[$m1]['data'][$m2]['defect_name'] = $row['defect_name'] ; 
                $total[$m1]['data'][$m2]['ng_qty']      = $row['ng_qty'] ; 
                $total[$m1]['data'][$m2]['ok_return']   = $row['ok_return'] ; 
                $total[$m1]['data'][$m2]['result']      = $row['result'] ; 
                $total[$m1]['data'][$m2]['sanluong']    = $row['sanluong'] ; 
                $total[$m1]['data'][$m2]['ng_rate']     = $row['ng_rate'] ; 
                $check_show = $row['check_show'] ; 
                if($check_show == 'Show') {$def_color = 'red'; } else {$def_color = '' ; }
                $total[$m1]['data'][$m2]['def_color']  = $def_color ; 
                
                $total[$m1]['count'] = $m2 ; 
              

             

                
                

                
             
                

                // if($m1_temp <> $m1 ){
                    
                //     $total[$m1]['data']   = $temp ; 
                //     $total[$m1]['count']  = $m2 ; 
                //     $m1 = $m1_temp ;
                //     $temp = $infor = array() ; 
                // }
                 
                // if($m2_temp <>$m2 ){$m2 = $m2_temp ; }

               

        }
        // $m1 = $m1_temp ;
        // $total[$m1]['infor']  = $infor  ; 
        // $total[$m1]['data']   = $temp ; 
        // $total[$m1]['count']  = $m2_temp ; 
        
        return $total ;
       
    }
    
}


?>