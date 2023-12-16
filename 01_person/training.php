<?php 


require_once  __DIR__.'/../00_connect/connect.php'; 
require_once  __DIR__.'/../00_connect/00_const.php'; 


use Connect\connect\conn_db;

class training { 
    
    // public function __construct($name,$code,$time){
    //     $this->name = $name ; 
    //     $this->code = $code ; 
    //     $this->time = $time ; 

    // }

    public function training_search_value($name1,$code1,$old_code,$time1){
        ### SETTING 
        $connection  = new conn_db() ; 
        $const       = new const_data() ; 
        $domain = $const::domain ; 
        $report_folder = $const::report_folder ;
        $pdf_reader     = $const::pdf_reader ; 


        $conn = $connection -> conn_assy() ; 
        $name_search = $name1 ; 
        $code_search = $code1 ; 
        $time_search = $time1 ;

        ### BEGIN  
        if($old_code == ''){
            $sql = "SELECT * FROM training where (name like'%$name_search%' and code like '%$code_search%' and time like  '%$time_search%'  ) order by no asc";
        }
        else {
            #### dung cho review person 
            $sql = "SELECT * FROM training where (code like '$code_search' or code like '$old_code') order by no asc";
        }
        $result = mysqli_query($conn, $sql);
        $data = array() ; 
        $c = 0 ; 
        while($row = mysqli_fetch_assoc($result)){
            $folder_1 = $row['folder_1'] ; 
            $folder_2 = $row['folder_2'] ;

            if($row['folder_3'] == 'null') $folder_3 = ''; else $folder_3 = $row['folder_3'];
            if($row['folder_4'] == 'null') $folder_4 = ''; else $folder_4 = $row['folder_4'];
            if($row['folder_5'] == 'null') $folder_5 = ''; else $folder_5 = $row['folder_5'];
            if($row['folder_6'] == 'null') $folder_6 = ''; else $folder_6 = $row['folder_6'];
            $file_data = $row['file'] ; 
            $time_data = $row['time'] ; 
            $code_data = $row['code'] ; 
            $name_data = $row['name'] ; 
            $date_data = $row['date'] ;
            $type_data = $row['type'] ; 
            $content_data = $row['content'] ; 
            $link1  = "$domain/$pdf_reader?path=$report_folder/$folder_1/$folder_2/$folder_3/$folder_4/$folder_5/$folder_6/$file_data" ; 
            $link2  = "$report_folder/$folder_1/$folder_2/$folder_3/$folder_4/$folder_5/$folder_6/$file_data" ; 

            $c++ ; 
            $data[$c]['time'] = $time_data ; 
            $data[$c]['code'] = $code_data ;
            $data[$c]['name'] = $name_data ;
            $data[$c]['date'] = $date_data ;
            $data[$c]['type'] = $type_data ;
            $data[$c]['content'] = $content_data ;
            $data[$c]['link1'] = $link1;
            $data[$c]['link2'] = $link2;
            

        }
        $data['count']['value'] = $c ; 
        return $data ; 
    }


    #### INSERT Training table 
    public function insert_training($time,$code,$name,$date,$type,$content,$fd1,$fd2, $fd3,$fd4,$fd5,$fd6,$file){
        $connection  = new conn_db() ; 
        $conn = $connection -> conn_assy() ; 
        #### Insert training table 
        $sql_insert_training = "INSERT INTO `training`(`no`, `time`, `code`, `name`, `date`, `type`, `content`, `folder_1`, `folder_2`, `folder_3`, `folder_4`, `folder_5`, `folder_6`, `file`) VALUES (NULL,'$time','$code','$name','$date','$type','$content','$fd1','$fd2','$fd3','$fd4','$fd5','$fd6','$file')"; 
        mysqli_query($conn,$sql_insert_training); 
       

    }


    #### update danh sách từ folder vào bảng tạm 
    public function update_temp_train($fd0,$fd1,$fd2,$fd3,$fd4,$fd5,$fd6){
        $connection  = new conn_db() ; 
        $conn = $connection -> conn_assy() ; 
        
        #### DELETE old data from tem  
        $sql_delete = "DELETE FROM `training_tem`" ; 
        mysqli_query($conn,$sql_delete);

        #1.xác nhận time hiện tại 
            $sql_find_time = "SELECT max(time) as max FROM `training` "; 
            $result_find_time = mysqli_query($conn,$sql_find_time); 
            $row_find_time = mysqli_fetch_assoc($result_find_time); 
            $time_check = $row_find_time['max']; 
        
        #2. List danh sách từ folder và update vào bảng tạm 
            $path_full = "$fd0/$fd1/$fd2/$fd3/$fd4/$fd5/$fd6"; 
            $files2 = scandir($path_full);
            $s= $k = 0 ; 
            $time_input = $time_check + 1 ; 
            while(isset($files2[$s])){
                $txt = $files2[$s];
                $path_check = $path_full.'/'.$txt;
                if(is_file($path_check) and ($txt <> '..') and ($txt <> '.')){
                    //echo $txt.'<br>';
                    //echo $txt.'<br>'; 
                    $sql_input = "INSERT INTO `training_tem`(`no`, `times`, `fd1`, `fd2`, `fd3`, `fd4`, `fd5`, `fd6`, `file`, `comment`) VALUES (NULL,'$time_input','$fd1','$fd2','$fd3','$fd4','$fd5','$fd6','$txt','')"; 
                    $result_input = mysqli_query($conn,$sql_input); 
         
                }
                
            $s++; 
            }
        
        

    }
  

    ### list để show dữ liệu từ bảng temp 
    public function list_train_temp($no){
        $connection  = new conn_db() ; 
        $conn = $connection -> conn_assy() ; 

        $const         = new const_data() ; 
        $domain        = $const::domain ; 
        $report_folder = $const::report_folder ;
        $pdf_reader    = $const::pdf_reader ; 


        #1. Lấy lại danh sách từ bảng tạm 
        if($no==''){ $sql_list = "SELECT * FROM `training_tem`";  }
        else { $sql_list = "SELECT * FROM `training_tem` WHERE (no = '$no')"; }

        $result_list = mysqli_query($conn,$sql_list); 
        $c = 0 ; 
        $list_data = array() ; 
        while($row_list = mysqli_fetch_assoc($result_list)){
            $no_show   = $row_list['no'];
            $time_show = $row_list['times'] ; 
            $fd1_show  = $row_list['fd1']; 
            $fd2_show  = $row_list['fd2']; 
            $fd3_show  = $row_list['fd3']; 
            $fd4_show  = $row_list['fd4']; 
            $fd5_show  = $row_list['fd5']; 
            $fd6_show  = $row_list['fd6']; 
            $file_show = $row_list['file']; 
            $pdf   = "$domain/$pdf_reader?path=$report_folder/$fd1_show/$fd2_show/$fd3_show/$fd4_show/$fd5_show/$fd6_show/$file_show" ; 

            $c++;
            $list_data[$c]['no']   = $no_show; 
            $list_data[$c]['time'] = $time_show; 
            $list_data[$c]['fd1']  = $fd1_show ; 
            $list_data[$c]['fd2']  = $fd2_show  ; 
            $list_data[$c]['fd3']  = $fd3_show  ; 
            $list_data[$c]['fd4']  = $fd4_show  ; 
            $list_data[$c]['fd5']  = $fd5_show  ; 
            $list_data[$c]['fd6']  = $fd6_show  ; 
            $list_data[$c]['file'] = $file_show  ; 
            $list_data[$c]['pdf']  = $pdf  ; 
            
        }
        $list_data['count']['value'] = $c ; 
       
    return $list_data ;

    }
    

    #### DELETE training temp 
    public function train_temp_delete($no){
        $connection  = new conn_db() ; 
        $conn = $connection -> conn_assy() ; 
        ##### DELETE training template 
        $sql_delete_template = "DELETE FROM `training_tem` WHERE (`no` like '$no')";
        mysqli_query($conn,$sql_delete_template); 
    }



    #### TRAINING STANDARD
    public function train_standard_list(){
        ## Setting 
        $connection  = new conn_db() ; 
        $conn = $connection -> conn_assy() ; 

        $const         = new const_data() ; 
        $domain        = $const::domain ; 
        $assy_folder   = $const::assy_private ;
        $pdf_reader    = $const::pdf_reader ; 

        ## list 
        $training_list = array(); 
        $sql_list_st = "SELECT * FROM training_standard" ; 
        $result_list_st = mysqli_query($conn, $sql_list_st);
        $r = 0 ; 
        if($result_list_st){
            while($row_list_st = mysqli_fetch_assoc($result_list_st)){
                $r++ ; 
                $no = $row_list_st['no'] ; 
                $train_sb = $row_list_st['subject'] ; 
                $fd_1 = $row_list_st['folder_1'] ; 
                $fd_2 = $row_list_st['folder_2'] ; 
                $fd_3 = $row_list_st['folder_3'] ; 
                $fd_4 = $row_list_st['folder_4'] ;
                $fd_5 = $row_list_st['folder_5'] ; 
                $fd_6 = $row_list_st['folder_6'] ; 
                $fd_7 = $row_list_st['folder_7'] ;  
                $file = $row_list_st['file_name'] ; 
                $link_file = "$domain/$pdf_reader?path=$assy_folder/$fd_1/$fd_2/$fd_3/$fd_4/$fd_5/$fd_6/$fd_7/$file" ; 
                
                
                $training_list[$r]['no'] = $no ; 
                $training_list[$r]['sb'] = $train_sb ; 
                $training_list[$r]['fd_1'] = $fd_1 ; 
                $training_list[$r]['fd_2'] = $fd_2 ; 
                $training_list[$r]['fd_3'] = $fd_3 ; 
                $training_list[$r]['fd_4'] = $fd_4 ; 
                $training_list[$r]['fd_5'] = $fd_5 ; 
                $training_list[$r]['fd_6'] = $fd_6 ; 
                $training_list[$r]['fd_7'] = $fd_7 ; 
                $training_list[$r]['file'] = $file ; 
                $training_list[$r]['link'] = $link_file ; 
            
            }
        }
        $training_list['count']['value'] = $r ; 
        return $training_list ;
    } 


    public function train_standard_update($sb,$fd1,$fd2,$fd3,$fd4,$fd5,$fd6,$fd7,$file,$no){
        $connection  = new conn_db() ; 
        $conn = $connection -> conn_assy() ; 
        $sql_update = "UPDATE `training_standard` SET `subject`= '$sb' , `folder_1`= '$fd1',`folder_2`='$fd2',`folder_3`='$fd3',`folder_4`='$fd4',`folder_5`='$fd5',`folder_6`='$fd6',`folder_7`='$fd7',`file_name`='$file' where no like '$no'" ; 
        $result_update = mysqli_query($conn, $sql_update);
    }


    public function train_standard_insert($r,$sb,$fd1,$fd2,$fd3,$fd4,$fd5,$fd6,$fd7,$file){
        $connection  = new conn_db() ; 
        $conn = $connection -> conn_assy() ; 
        $sql_input = "INSERT INTO `training_standard`(`no`, `subject`,`folder_1`, `folder_2`, `folder_3`, `folder_4`, `folder_5`, `folder_6`, `folder_7`, `file_name`) VALUES ('$r','$sb','$fd1','$fd2','$fd3','$fd4','$fd5','$fd6','$fd7','$file')"; 
    	$result_input = mysqli_query($conn, $sql_input);
        
    }
}




?>