<?php 

class person{
    public $code;
    
    function __construct($code){
       $this -> code   = $code; 
       $this -> con_hr = mysqli_connect('localhost','root','','hr_control');

       
    }
 
    function common_data(){
        $detail = array(); 
        $sql_data = "SELECT * FROM common_info INNER JOIN line_info ON common_info.code = line_info.code INNER JOIN personal_info ON common_info.code = personal_info.code where (common_info.code like '{$this -> code}') order by stt asc"; 
        // echo $sql_data ; 
        $result_data = mysqli_query($this->con_hr,$sql_data); 
        $row_data    = mysqli_fetch_assoc($result_data); 
        
        $fullname =
        $position = $row_data['position']; 

        $detail['code']            =  $row_data['code']; 
        $detail['fullname']        =  $row_data['fullname']; 
        $detail['position']        =  $row_data['position']; 
        $detail['wk_status']       =  $row_data['working_status']; 
        $detail['gender']          =  $row_data['gender']; 


        $old_code_find = $row_data['old_code']; 
        if($old_code_find=='null'){$old_code_find = '';}
        $detail['old_code']        =  $old_code_find; 


        $detail['join_date']       =  $row_data['join_date']; 
        $detail['type']            =  $row_data['type']; 
        $detail['image']           =  $row_data['image']; 
        $detail['leader']          =  $row_data['leader']; 
        $detail['line']            =  $row_data['line']; 
        $detail['area']            =  $row_data['line']; 

        $pro_sys_name = $row_data['pro_sys_name']; 
        if($pro_sys_name =='null'){$pro_sys_name = '';}
        $detail['pro_sys_name']    =  $pro_sys_name; 

        $detail['main_process']    =  $row_data['main_process']; 

        $baby_from = $row_data['baby_hol_from']; 
        if($baby_from=='0000-00-00'){$baby_from = ''; }
        $detail['baby_hol_from']   =  $baby_from;

        $baby_to = $row_data['baby_hol_to']; 
        if($baby_to=='0000-00-00'){$baby_to = ''; }
        $detail['baby_hol_to']   =  $baby_to;

        $resign_date = $row_data['resign_date']; 
        if($resign_date=='0000-00-00'){$resign_date = '';}
        $detail['resign_date']     = $resign_date;
        
        $request_op = $row_data['request_op_date']; 
        if($request_op=='0000-00-00'){$request_op = '';}
        $detail['request_op_date'] =  $request_op;
        
        $reason = $row_data['resign_reason']; 
        if($reason =='null'){$reason = '';}
        $detail['resign_reason']   =  $reason;
         
        $detail['married_status']  =  $row_data['married_status']; 
        $detail['degree']          =  $row_data['degree']; 
        $detail['birthday']        =  $row_data['birthday']; 
        $detail['tel']             =  $row_data['tel']; 
        $detail['adress']          =  $row_data['adress']; 
      
        return $detail ; 
    }


}

// $a = new person('1222'); 
// $arr = $a -> common_data(); 

// $b = new person('1048'); 
// $arr2 = $b -> common_data(); 

// require('../data.php'); 
// show_array($arr); 
// show_array($arr2);
?>
