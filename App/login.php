<?php 

require_once __DIR__.'/../00_connect/connect.php' ; 

use Connect\connect\conn_db ; 


class login{

   public function  require_login($url_login,$level_check){
           
      $host = $_SERVER['HTTP_HOST'] ; 
      $current_domain = "http://$host";
      $infor = array() ;
      $path_name = $_SERVER['REQUEST_URI']; 

      ob_start();
      if(!isset($_SESSION)){session_start();}


      if(!isset($_SESSION['is_login'])){
         header("Location: $url_login?path=$path_name");  
      }
      else{
        
         $user = $_SESSION['user_login'];
         $infor['user'] = $user ; 

         if(isset($_SESSION['level'])) {$user_level = $_SESSION['level'];} else {$user_level = '' ; }
         $infor['level'] = $user_level ; 

         if($level_check !== ''){
            if($user_level > $level_check ){
               header("Location: $current_domain/deny_access.php") ; 
            }
         }
      }
      return $infor ; 
   }


   // public function  require_login_level($url_login,$level){
   //    $host = $_SERVER['HTTP_HOST'] ; 
   //    $current_domain = "http://$host";
   //    $infor = array() ; 


   //    ob_start();
   //    session_start();
   //    $path_name   = $_SERVER['REQUEST_URI']; 
   //    if(!isset($_SESSION['is_login'])){
   //       header("Location: $url_login?path=$path_name");  
   //    }
   //    else{
   //       $user = $_SESSION['user_login'];
   //       $user_level = $_SESSION['level'];
   //       if($user_level > $level ){
   //          header("Location: $current_domain/deny_access.php") ; 
   //       }
   //    }
   //    return $infor ; 
   // }


   
    public function check_login($user,$pass){
       $current_host = $_SERVER['HTTP_HOST'];
       ob_start();
       session_start();
       ### Xóa dữ liệu Session cũ 
       unset($_SESSION['is_login']);
	    unset($_SESSION['user_login']);
       unset($_SESSION['level']);
       
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
          $pass_find  = $row['password'] ; 
          $user_level = $row['level'] ; 
          if($pass_find == $pass){
              $status  = True ; 
              $message = '' ; 
              $_SESSION['is_login'] = TRUE;
			     $_SESSION['user_login'] = $user; 
              $_SESSION['level'] = (int)$user_level; 
          }
          else {
             $message   = "Tên đăng nhập hoặc mật khẩu không chính xác" ; 
             $status    = false ; 
          }
       }
       
       $result = array() ; 
       $result['status'] = $status  ;
       $result['message'] = $message;
       return $result ;
    }




    public function check_login_kaizen($user,$pass){
      $current_host = $_SERVER['HTTP_HOST'];
      ob_start();
      session_start();
      ### Xóa dữ liệu Session cũ 
      unset($_SESSION['is_login']);
      unset($_SESSION['user_login']);
      unset($_SESSION['level']);
      
      $connection = new conn_db ;
      $connect = $connection->conn_kaizen() ; 
      
      if($user == ''){
         $message = "Không được để trống tên đăng nhập" ; 
         $status = false ; 
      }
      else if($pass == ''){
       $message = "Không được để trống mật khẩu" ; 
         $status = false ; 
      }
      else {
         $sql = "SELECT * FROM user_tb WHERE(user like '$user')";
         $result = mysqli_query($connect, $sql);
         $row = mysqli_fetch_assoc($result);
         $pass_find  = $row['password'] ; 
         $user_level = $row['level'] ; 

         if($pass_find == $pass){
             $status  = True ; 
             $message = '' ; 
             $_SESSION['is_login'] = TRUE;
             $_SESSION['user_login'] = $user; 
             $_SESSION['level'] = (int)$user_level; 

         }
         else {
            $message   = "Tên đăng nhập hoặc mật khẩu không chính xác" ; 
            $status    = false ; 
         }
      }
      
      $result = array() ; 
      $result['status'] = $status  ;
      $result['message'] = $message;
      return $result ;
   }



    
}


?>