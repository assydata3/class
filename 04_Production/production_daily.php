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
            (SELECT jp_name from assy.defect_code WHERE defect_code like db.defect_code) as df_jp_name, 
            dense_rank() over(order by area , line , model DESC) as group_data
            FROM daily_defect_report as db 
            WHERE 
            date = '$pro_date'
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
                $total[$m1]['data'][$m2]['df_jp_name']  = $row['df_jp_name'] ; 
                $total[$m1]['data'][$m2]['ng_qty']      = $row['ng_qty'] ; 
                $total[$m1]['data'][$m2]['ok_return']   = $row['ok_return'] ; 
                $total[$m1]['data'][$m2]['result']      = $row['result'] ; 
                $total[$m1]['data'][$m2]['sanluong']    = $row['sanluong'] ; 
                $total[$m1]['data'][$m2]['ng_rate']     = $row['ng_rate'] ; 
                $check_show = $row['check_show'] ; 
                if($check_show == 'Show') {$def_color = 'red'; } else {$def_color = '' ; }
                $total[$m1]['data'][$m2]['def_color']  = $def_color ; 
                
                $total[$m1]['count'] = $m2 ; 
              

        }

        
        return $total ;
       
    }

    


    ### lisst data to adjust before metting 
    public function list_defect_daily_adjust($date_check){
        $connection = new conn_db() ; 
        $connect = $connection->conn_report() ; 
        $data = array () ; 
        $k = 0 ; 

        $sql = "WITH CTE AS (
            select no, date , line , record_no, model , defect_name , defect_code , ng_qty , ok_return , after_check, result, db.rank , 
            (select sum(actual) from daily_production_report WHERE pro_date = '$date_check' and line = db.line and area = db.area and model = db.model ) as sanluong , 
            check_show, no_count , confirm , saiken, saiken_qty 
            from daily_defect_report as db
            WHERE date = '$date_check'
            ORDER BY area , line , no_1 DESC ) 
            
            SELECT * , concat(round(result/sanluong*100,2) , '%') as ng_rate 
            FROM CTE " ; 

        $result = mysqli_query($connect,$sql) ; 
        
        while ($row = mysqli_fetch_array($result)) {
            $k ++ ; 
            $data[$k]['no']    = $row['no'] ; 
            $data[$k]['date']  = $row['date'] ; 
            $data[$k]['line']  = $row['line'] ; 
            $data[$k]['model'] = $row['model'] ; 
            $data[$k]['record_no']   = $row['record_no'] ; 
            $data[$k]['defect_name'] = $row['defect_name'] ; 
            $data[$k]['defect_code'] = $row['defect_code'] ; 
            $data[$k]['ng_qty']      = $row['ng_qty'] ; 
            $data[$k]['ok_return']   = $row['ok_return'] ; 
            $data[$k]['after_check'] = $row['after_check'] ; 
            $data[$k]['result']      = $row['result'] ; 
            $data[$k]['sanluong']    = $row['sanluong'] ; 
            $data[$k]['ng_rate']     = $row['ng_rate'] ; 
            $data[$k]['show']        = $row['check_show'] ; 
            $data[$k]['no_count']    = $row['no_count'] ; 
            $data[$k]['confirm']     = $row['confirm'] ; 
            $data[$k]['saiken']      = $row['saiken'] ; 
            $data[$k]['saiken_qty']  = $row['saiken_qty'] ; 
            $data[$k]['rank']        = $row['rank'] ; 

             
        }
        $data['count']['value'] = $k ; 
        return $data ;

    } 

    public function scan_show_defect($date_check){
        $connection = new conn_db() ; 
        $connect   = $connection->conn_report_2() ; 
        $conn_assy = $connection->conn_assy() ; 
        
        $sql_list_all_data    = "SELECT * FROM daily_defect_report WHERE(date like '$date_check')" ; 
		$result_list_all_data = mysqli_query($connect,$sql_list_all_data); 
		while($row_list_all = mysqli_fetch_assoc($result_list_all_data)){
			$no_find        = $row_list_all['no']; 
			$defect_code_find = $row_list_all['defect_code']; 
			$defect_result    = $row_list_all['result']; 
			
			//Check Rank 
			$sql_check_rank   = "SELECT `rank` FROM `defect_code` WHERE (`defect_code` like '$defect_code_find')" ; 
			$resul_check_rank = mysqli_query($conn_assy,$sql_check_rank); 
			$row_check_rank   = mysqli_fetch_assoc($resul_check_rank) ;
			$rank_confirm     = $row_check_rank['rank']; 
			if($rank_confirm == 'A'){ if($defect_result > 0){$result_show = 'Show'; } else { $result_show = ''; } } 
			if($rank_confirm == 'B'){ if($defect_result >= 3){$result_show = 'Show'; } else { $result_show = ''; } } 
			if($rank_confirm == 'C'){ if($defect_result >= 5){$result_show = 'Show'; } else { $result_show = ''; } } 
			if($rank_confirm == ''){ $result_show = 'Show';  } 

			//Update data 
			$sql_update_rankshow  = "UPDATE `daily_defect_report` SET `rank`= '$rank_confirm',`check_show`= '$result_show' WHERE(`no` like '$no_find')"; 
			$result_update_rankshow = mysqli_query($connect, $sql_update_rankshow); 
		} 

    }

    
    public function  max_calc_2($tb_name, $field){
        $connection = new conn_db ; 
        $connect_report_server = $connection->conn_report(); 
        $sql_max = "SELECT MAX($field) FROM $tb_name";
        $result_max = mysqli_query($connect_report_server, $sql_max);
        if($result_max){
            while($row_max = mysqli_fetch_assoc($result_max)){
                $max = $row_max["MAX($field)"] + 1;
            }
        } else $max = 1;
        
        return $max;
    }


    #### DATABASE RELATE 
    public function update_daily_defect_report($no,$check_show,$confirm,$saiken,$saiken_qty,$no_count,$no_report){
        $connection = new conn_db() ;
        $connect   = $connection->conn_report() ; 
        $sql = "UPDATE `daily_defect_report` SET check_show='$check_show', confirm='$confirm', saiken='$saiken', saiken_qty='$saiken_qty', no_count='$no_count', no_report='$no_report' WHERE(`no` like '$no')";
        // echo $sql.'<br>'; 
        mysqli_query($connect,$sql) ; 

    }

    public function update_kpi_sum_defect($record_no,$defect_code,$no_count){
        $connection = new conn_db() ;
        $connect   = $connection->conn_report_2() ; 
        $sql = "UPDATE `kpi_sum_defect` SET `no_count`='$no_count' WHERE(`record_no` like '$record_no' and `defect_code` like '$defect_code')";
        mysqli_query($connect,$sql) ; 

    }
    

    public function clear_record_no($record_no){
        $connection = new conn_db() ;
        $connect   = $connection->conn_report() ; 
        $sql_del_production_daily = "DELETE FROM `daily_production_report` WHERE(`record_no` like '$record_no')"; 
        mysqli_query($connect, $sql_del_production_daily); 
        $sql_del_defect_daily = "DELETE FROM `daily_defect_report` WHERE(`record_no` like '$record_no')"; 
        mysqli_query($connect, $sql_del_defect_daily); 
    }
}


?>