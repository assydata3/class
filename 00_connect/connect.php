<?php 
namespace Connect\connect ;


class conn_db{
   const  host_local  = 'localhost' ; 
   const  local_admin = 'root' ; 
   const  local_pass  = '' ; 
   const host_assy3 = 'assydata3' ; 
   const assy3_user = 'SuperAdmin' ; 
   const assy3_pass = '123456'; 



   public function conn_assy()
   {
      $host = self::host_assy3 ; 
      $user = self::assy3_user ; 
      $pass = self::assy3_pass ;
      $db = 'assy' ; 
      $connect = mysqli_connect($host ,$user,$pass,$db); 
      return $connect ; 
   }



   public function conn_assy2(){
      $host = self::host_assy3 ; 
      $user = self::assy3_user ; 
      $pass = self::assy3_pass ;
      $db = 'assy_2' ; 
      $connect = mysqli_connect($host ,$user,$pass,$db); 
      return $connect ; 
   }

   public function conn_budget(){
      $connect = mysqli_connect('localhost','root','','budget'); 
      return $connect ; 
   }

   public function conn_chatapp(){
      $connect = mysqli_connect('localhost','root','','chatapp'); 
      return $connect ; 
   }

   public function conn_convert(){
      $connect = mysqli_connect('localhost','root','','convert'); 
      return $connect ; 
   }

   public function conn_ct(){
      $connect = mysqli_connect('localhost','root','','ct'); 
      return $connect ; 
   }


   public function conn_document(){
      $connect = mysqli_connect('localhost','root','','document'); 
      return $connect ; 
   }

   public function conn_edp(){
      $connect = mysqli_connect('localhost','root','','edp'); 
      return $connect ; 
   }

    
   public function conn_edp_data()
   {
      $host = self::host_assy3 ; 
      $user = self::assy3_user ; 
      $pass = self::assy3_pass ;
      $db = 'edp_data' ; 
      $connect = mysqli_connect($host ,$user,$pass,$db); 
      return $connect ; 
   }

   public function conn_equipment(){
      $connect = mysqli_connect('localhost','root','','equipment'); 
      return $connect ; 
   }

   public function conn_export_excel(){
      $connect = mysqli_connect('localhost','root','','export_excel'); 
      return $connect ; 
   }


   public function conn_fob_id(){
      $connect = mysqli_connect('localhost','root','','fob_id'); 
      return $connect ; 
   }


   public function conn_gk(){
      $connect = mysqli_connect('localhost','root','','gk'); 
      return $connect ; 
   }

   public function conn_hr()
   {
      $host = self::host_local ; 
      $user = self::local_admin ; 
      $pass = self::local_pass ;
      $db = 'hr_control' ; 
      $connect = mysqli_connect($host ,$user,$pass,$db); 
      return $connect ; 
   }

   public function conn_iso(){
      $connect = mysqli_connect('localhost','root','','iso'); 
      return $connect ; 
   }


   public function conn_kaizen(){
      $connect = mysqli_connect('localhost','root','','kaizen'); 
      return $connect ; 
   }


   public function conn_lucky(){
      $connect = mysqli_connect('localhost','root','','lucky'); 
      return $connect ; 
   }

   public function conn_mp_app(){
      $connect = mysqli_connect('localhost','root','','mp_app'); 
      return $connect ; 
   }

   public function conn_new_model(){
      $connect = mysqli_connect('localhost','root','','new_model'); 
      return $connect ; 
   }


   public function conn_pcb(){
      $connect = mysqli_connect('localhost','root','','pcb'); 
      return $connect ; 
   }

   
   public function conn_pchart(){
      $connect = mysqli_connect('localhost','root','','pchart'); 
      return $connect ; 
   }

   public function conn_pe(){
      $connect = mysqli_connect('localhost','root','','pe'); 
      return $connect ; 
   }


   public function conn_report(){
      ## new 
      $host = self::host_assy3 ; 
      $user = self::assy3_user ; 
      $pass = self::assy3_pass ;
      $db = 'report' ; 
      $connect = mysqli_connect($host ,$user,$pass,$db); 
      return $connect ; 

   }

   public function conn_report_2(){
      ## new 
      $host = self::host_local ; 
      $user = self::local_admin ; 
      $pass = self::local_pass ;
      $db = 'report' ; 
      $connect = mysqli_connect($host ,$user,$pass,$db); 
      return $connect ; 
 
   }

   public function conn_test(){
      $host = self::host_assy3 ; 
      $user = self::assy3_user ; 
      $pass = self::assy3_pass ;
      $db = 'test' ; 
      $connect = mysqli_connect($host ,$user,$pass,$db); 

      return $connect ; 
   }


   public function conn_test8(){
      $connect = mysqli_connect('localhost','root','','test8'); 
      return $connect ; 
   }

   public function conn_trainning(){
      $connect = mysqli_connect('localhost','root','','trainning'); 
      return $connect ; 
   }
}



?>