<?php 

require_once __DIR__.'/../00_connect/connect.php' ; 

use Connect\connect\conn_db ; 


class login{

   public function  require_login($url_login){
      $infor = array() ; 
      ob_start();
      session_start();
      $path_name   = $_SERVER['REQUEST_URI']; 
      if(!isset($_SESSION['is_login'])){
         header("Location: $url_login?path=$path_name");  
      }
      else{
         $user = $_SESSION['user_login'];
         $infor['user'] = $user ; 
      }
      return $infor ; 
   }


   
    public function check_login($user,$pass){
       $current_host = $_SERVER['HTTP_HOST'];
       ob_start();
       session_start();
       ### Xóa dữ liệu Session cũ 
       unset($_SESSION['is_login']);
	    unset($_SESSION['user_login']);
       
       $connection = new conn_db ;
       $connect = $connection->conn_assy2() ; 
       
       if($user == ''){
          $message = "Không được để trống tên đăng nhập" ; 
          $status = false ; 
       }
       else if($pass == ''){
        $message = "Không được để trống mật khẩu" ; 
          $status = false ; 
       }
       else {
          $sql = "SELECT * FROM user_table WHERE(user like '$user')";
          $result = mysqli_query($connect, $sql);
          $row = mysqli_fetch_assoc($result);
          $pass_find = $row['password'] ; 
          if($pass_find == $pass){
              $status  = True ; 
              $message = '' ; 
              $_SESSION['is_login'] = TRUE;
			  $_SESSION['user_login'] = $user; 
          }
          else {
             $message   = "Tên đăng nhập hoặc mật khẩu không chính xác" ; 
             $status    = false ; 
          }
       }
       
       $result = array() ; 
       $result['status']       = $status  ;
       $result['message']      = $message;
       return $result ;
    }



    
}


?>