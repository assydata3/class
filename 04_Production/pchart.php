<?php 

require_once __DIR__.'/../00_connect/connect.php' ; 
use Connect\connect\conn_db;

class pchart{

    //Tinh toan Result ng cua tung ngay 
    public function result_ng($date,$line,$model,$df_code){
        $connection = new conn_db(); 
        $connect = $connection ->conn_report() ; 
        $sql = "SELECT sum(`result`) as sum FROM `daily_defect_report` WHERE (`date` like '$date' and `model` like '$model' and `line` like '$line' and `defect_code` like '$df_code')"; 
        $result= mysqli_query($connect,$sql); 
        $row = mysqli_fetch_assoc($result); 
        $def_qty = $row['sum']; 

        return $def_qty ;
    }


    // Update Pchart all day 
    public function pchart_daily_data($pro_date){
        $connection = new conn_db();
        $connect = $connection -> conn_pchart() ;
        $sql = "WITH CTE_DATA3 AS 
        (
        WITH CTE_DATA_2 AS 
        (
        WITH CTE_DATA AS (
        SELECT pro_date , area , line , model , sum(actual) as sanluong  , 
        (SELECT COALESCE(SUM(result),0) FROM report.daily_defect_report WHERE date = db.pro_date and area = db.area and line = db.line and model = db.model and defect_code in (SELECT defect_code FROM pchart_list_defect ) ) as ng_qty , 
        (SELECT no FROM pchart_last_month_data WHERE area = db.area and line = db.line and model = db.model ORDER BY no DESC LIMIT 1) as no_lastmonth ,
        (SELECT rate  FROM pchart_last_month_data WHERE area = db.area and line = db.line and model = db.model ORDER BY no DESC LIMIT 1) as lastmonth 
        FROM report.daily_production_report as db
        WHERE 
        pro_date = '$pro_date' and 
        line IN (SELECT line FROM line_pchart_control )
        GROUP BY pro_date , area , line , model 
        ) 
        
        SELECT pro_date , area , line , model , sanluong , ng_qty ,
        if(no_lastmonth is null, '',no_lastmonth) as no_lastmonth, 
        if(lastmonth is null, 0 , lastmonth) as p_tb, 
        round(ng_qty/sanluong*100,8)  as CL
        FROM CTE_DATA
        ) 
        
        SELECT * , 
        round(p_tb*(1-p_tb)/sanluong,8) as hai_data , 
        round(sqrt(p_tb*(1-p_tb)/sanluong),8) as ba_data, 
        round((sqrt(p_tb*(1-p_tb)/sanluong))*3,8) as bon_data
        FROM CTE_DATA_2 
        ) 
        
        SELECT pro_date, area , line , model , sanluong , ng_qty , CL, no_lastmonth , p_tb  , hai_data, ba_data, bon_data,
        p_tb + bon_data as UCL , 
        if(p_tb > bon_data , p_tb - bon_data , 0) as LCL
        FROM CTE_DATA3
        " ; 
        
        $data = array() ; 
        $k = 0 ;                        
        $result = mysqli_query($connect,$sql);
        while($row = mysqli_fetch_assoc($result)){
            $k++ ; 
            $data[$k]['date']     = $row['pro_date']; 
            $data[$k]['area']     = $row['area']; 
            $data[$k]['line']     = $row['line'];
            $data[$k]['model']    = $row['model'];
            $data[$k]['sanluong'] = $row['sanluong'];
            $data[$k]['ng_qty']   = $row['ng_qty'];
            $data[$k]['cl']       = $row['CL'];
            $data[$k]['no_lastmonth']  = $row['no_lastmonth'];
            $data[$k]['ptb']           = $row['p_tb'];
            $data[$k]['hai_data']      = $row['hai_data'];
            $data[$k]['ba_data']       = $row['ba_data'];
            $data[$k]['bon_data']      = $row['bon_data'];
            $data[$k]['ucl']           = $row['UCL'];
            $data[$k]['lcl']           = $row['LCL'];

        }
        $data['count']['value'] = $k ; 

        return $data ;

    }




    //// INPUT daily update 
    public function pchart_input_daily_update(){
        $connection      = new conn_db();
        $connect_pchart  = $connection -> conn_pchart() ;
        $connect_report  = $connection -> conn_report() ;
        
      
        $list_day = array() ; $l = 0 ; 
        $sql_list_day = "SELECT DISTINCT(pro_date) FROM `daily_production_report` WHERE (`pchart_update`like '' or `pchart_update` is NULL ) ORDER BY pro_date " ; 
        $result_list = mysqli_query($connect_report,$sql_list_day); 
        while($row_list = mysqli_fetch_array($result_list)){
            $l++ ; 
            $list_day[$l] = $row_list["pro_date"];
        }

        for($i=1;$i<=$l;$i++){
            $date_check = $list_day[$i] ; 
            echo $date_check.'<br>' ; 
            $data_pchart = $this->pchart_daily_data($date_check) ; 
            $count = $data_pchart['count']['value']; 
            
            ####1.Detele data pchart_daily_update 
            $sql_delete = "DELETE * FROM pchart_daily_update  WHERE date = '$date_check'" ; 
            mysqli_query($connect_pchart,$sql_delete);
            

            #### 2.Update Daily Pchart Update 
            for($u=1;$u<=$count;$u++){
                $area_input          = $data_pchart[$u]['area'] ; 
                $line_input          = $data_pchart[$u]['line'] ;
                $model_input         = $data_pchart[$u]['model'] ;
                $pro_qty_input       = $data_pchart[$u]['sanluong'] ;
                $def_qty_input       = $data_pchart[$u]['ng_qty'] ;
                $rate_input          = $data_pchart[$u]['cl'] ;
                $lastmonth_no_input  = $data_pchart[$u]['no_lastmonth']; 
                $last_month_input    = $data_pchart[$u]['ptb'];
                $hai_data_input      = $data_pchart[$u]['hai_data'] ; 
                $ba_data_input       = $data_pchart[$u]['ba_data'] ; 
                $bon_data_input      = $data_pchart[$u]['bon_data'] ;
                $ucl_input           = $data_pchart[$u]['ucl']; 
                $lcl_input           = $data_pchart[$u]['lcl']; 

                $sql_insert_data = "INSERT INTO pchart_daily_update(date,area,line,model,pro_qty,def_qty,rate,last_month_no,last_month,ucl,lcl,hai_data,ba_data,bon_data,remark) 
                VALUES('$date_check','$area_input','$line_input','$model_input','$pro_qty_input','$def_qty_input','$rate_input ','$lastmonth_no_input ','$last_month_input','$ucl_input','$lcl_input','$hai_data_input','$ba_data_input','$bon_data_input','')" ; 
                mysqli_query($connect_pchart,$sql_insert_data); 

            }
            

        ### update Daily production 
            $sql_update_production = "UPDATE `daily_production_report` SET `pchart_update` = '1' WHERE pro_date = '$date_check' ";
            mysqli_query($connect_report,$sql_update_production) ; 
        }
    }
   
}







?>