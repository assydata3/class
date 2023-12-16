<?php 

class date_convert{
    public $date;

    function __construct($date){
        $this -> date   = $date; 
        $this -> con_test = mysqli_connect('localhost','root','','test');

        $sql_check         = "SELECT * FROM `calenda` WHERE (`start` like '$date')" ; 
        $result_check      =  mysqli_query($this->con_test,$sql_check); 
        $row_check         =  mysqli_fetch_assoc($result_check); 
        $this -> index     = $row_check['index_one']; 
        $this -> index_all = $row_check['index_all']; 
    }


    function holiday_check(){
        return $this -> index ; 
    }

    function date_no(){
        return $this -> index_all ; 
    }


    function next_date($no){
        $no_after = $no + $this->index_all ; 
        $sql_find_date = "SELECT * FROM `calenda` WHERE (`index_all` like '$no_after')" ; 
        $result_find   = mysqli_query($this->con_test,$sql_find_date);
        $row_find      = mysqli_fetch_assoc($result_find);
        $date_next     = $row_find['start']; 
        return $date_next ; 
    }

}





// $a = new date_convert('2020-01-17'); 
// echo $a->holiday_check().'<br>'; 
// echo $a->date_no().'<br>'; 
// echo $a->next_date(-3).'<br>'; 
?>