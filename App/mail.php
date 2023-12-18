<?php 

//Load Composer's autoloader
require_once  __DIR__.'/Phpmailer/vendor/autoload.php';
require_once __DIR__.'/../00_connect/connect.php' ; 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


use Connect\connect\conn_db;


class mailer {


   #### Setting 
    
    #### 2.MAS-V Mail 
    // private $mail_system_secure    = 'tls';
    // private $mail_system_host      = 'smtp.office365.com';                   //Company : 'smtp.office365.com'          // Gmail : 'smtp.gmail.com'
    // private $mail_system_auth      =  true;
    // private $mail_system_address   = 'assydata@minebea-as.com';           // Company : 'assydata@minebea-as.com';   // Gmail : 'assydata.mas@gmail.com'
    // private $mail_system_password  = 'hondalock@123';                 // Company : 'hondalock@123';             // Gmail : 'dnxbaalemsabfqxm'
    // private $mail_system_port      =  587;                               // Company :  25                          // Gmail :  587
    // private $mail_system_set_name  = 'assydata@minebea-as.com';           // Company : 'assydata@minebea-as.com'    // Gmail : 'assydata.mas@gmail.com'
    // private $mail_system_set_title = 'Assy_data System' ;

    ### 1.GMAIL 
    private $mail_system_secure    = 'tls';
    private $mail_system_host      = 'smtp.gmail.com';                   //Company : 'smtp.office365.com'          // Gmail : 'smtp.gmail.com'
    private $mail_system_auth      =  true;
    private $mail_system_address   = 'assydata.mas@gmail.com';           // Company : 'assydata@minebea-as.com';   // Gmail : 'assydata.mas@gmail.com'
    private $mail_system_password  = 'dnxbaalemsabfqxm';                 // Company : 'hondalock@123';             // Gmail : 'dnxbaalemsabfqxm'
    private $mail_system_port      =  587;                               // Company :  25                          // Gmail :  587
    private $mail_system_set_name  = 'assydata.mas@gmail.com';           // Company : 'assydata@minebea-as.com'    // Gmail : 'assydata.mas@gmail.com'
    private $mail_system_set_title = 'Assy_data System' ;
    
    ##### Portalbe option 
    public $footer_system_1 = "<br>==========================================================<br>
    Đây là mail được gửi từ hệ thống mail tự động - thuộc hệ thống quản lý dữ liệu của bộ phận Assy, nên không cần reply<br>
    Nếu mail này có làm phiền đến công việc của bạn, xin vui lòng liên lạc cho ban quản trị hệ thống, để chúng tôi điều chỉnh trong những lần tiếp theo<br>
    Phùng Mạnh Tùng - tung_phung@minebea-as.com<br>Vũ Minh Đức - duc_vu@minebea-as.com<br>Xin cảm ơn - Thân Ái";


    public $footer_system_2 = "<br>=========================================================<br>
    đây là mail gửi tử hệ thống quản lý dữ liệu Assy<br>
    Nếu có thông tin gì cần góp ý thì vui lòng gửi lại cho ban quản trị hệ thống để chúng tôi tiếp tục cải tiến";
  

    public $footer_kaizen = "<br>=======================================================<br>
    Đây là mail tự động của ủy ban kaizen<br>
    Nếu có comment gì anh, chị vui lòng gửi lại cho các thành viên ủy ban ở bộ phận mình để ủy ban tổng hợp ý kiến và tiếp tục cải tiến các lần tiếp theo."; 




    #### 3.Special User
    private $mail_admin_1      = 'tung_phung@minebea-as.com' ; 
    private $mail_admin_2      = 'duc_vu@minebea-as.com' ; 
    private $mail_admin_hr     = 'vanltt@minebea-as.com' ;

    private $mail_factory_gm_1 = 'akio_ueda@minebea-as.com';
    private $mail_vn_gm        = 'thangdk@minebea-as.com';
    private $mail_quality_gm   = 'kenji_nakamura@minebea-as.com';
    private $mail_quality_mgr  = 'tran_quy_cuong@minebea-as.com';
    private $mail_oee_pic      = 'thanhld@minebea-as.com';

   
    #### LIST AND UPDATE LIST EMAIL CONTROL 
    public function  list_email_control(){
        $connection  = new conn_db() ; 
        $connect = $connection->conn_test() ; 
        $list_email = array(); 
        $e = 0 ; 
        $sql_list_all = "SELECT * FROM `email_control` order by `dept`,`name`"; 
        $result_list_all = mysqli_query($connect,$sql_list_all); 
        while($row_list_all = mysqli_fetch_assoc($result_list_all)){
        $e++; 
        $list_email[$e]['no']          = $row_list_all['no']; 	
        $list_email[$e]['name']        = $row_list_all['name']; 
        $list_email[$e]['dept']        = $row_list_all['dept']; 
        $list_email[$e]['email']       = $row_list_all['email']; 
        $list_email[$e]['day_birth']   = $row_list_all['day_birth']; 	
        $list_email[$e]['month_birth'] = $row_list_all['month_birth']; 
        $list_email[$e]['year_birth']  = $row_list_all['year_birth']; 
        $list_email[$e]['remark']      = $row_list_all['remark']; 			
        }
        return $list_email ; 
    }

    
    public function update_email_list_control($name,$dept,$email,$remark,$day,$month,$year,$no){
        $connection  = new conn_db() ; 
        $connect = $connection->conn_test() ; 
        $sql_update_data = "UPDATE `email_control` SET `name`= '$name',`dept`= '$dept',`email`= '$email',`remark`= '$remark', `day_birth`= '$day',`month_birth`= '$month',`year_birth`= '$year' WHERE (no like '$no')"; 
		//echo $sql_update_data.'<br>'; 
		$result_update_data = mysqli_query($connect,$sql_update_data); 
    }

    public function insert_email_list($name,$dept,$email,$remark){
        $connection  = new conn_db() ; 
        $conn = $connection->conn_test() ; 
        $sql_email_input = "INSERT INTO `email_control`(`no`, `name`, `dept`, `email`, `remark`) VALUES (NULL,'$name','$dept','$email','$remark')";
        mysqli_query($conn,$sql_email_input);
   }



   public function list_emailer($find){
     $connection  = new conn_db() ; 
     $conn = $connection->conn_test() ; 
     
     $list_email = array() ; 
     $k = 0 ; 
     $sql_find  = "SELECT email FROM email_control where (remark like '%$find%')" ; 
     $result    = mysqli_query($conn,$sql_find) ; 
     while($row = mysqli_fetch_array($result)){
         $k++ ; 
         $mail = $row['email'] ; 
         $list_email[$k] = $mail ; 
     }
     return $list_email ;
   }


   

   #### MAIN FUNCTION 
   public function  sent_mail($title,$content,$list_to,$list_cc,$list_bcc,$list_attach){
    $mail = new PHPMailer(true);
    try {
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
        $mail->isSMTP();                                            //Send using SMTP
            
        // $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;        //Enable implicit TLS encryption
        $mail->SMTPSecure = $this->mail_system_secure;
        $mail->Host       = $this->mail_system_host ;              //Set the SMTP server to send through
        $mail->SMTPAuth   = $this->mail_system_auth ;              //Enable SMTP authentication
        $mail->Username   = $this->mail_system_address;            //SMTP username
        $mail->Password   = $this->mail_system_password ;          //SMTP password
        $mail->Port       = $this->mail_system_port;                                   //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
    
        //Recipients
        $mail->setFrom($this->mail_system_set_name, $this->mail_system_set_title);
        

        #### Sent to 
        foreach($list_to as $k => $v){
        $mail->addAddress($v);     //Add a recipient
        }

        #### CC
        foreach($list_cc as $cc_list => $cc){
            $mail->addCC($cc);
        }
        // $mail->addAddress('ellen@example.com');               //Name is optional
        // $mail->addReplyTo('info@example.com', 'Information');
        
        #### BCC 
        foreach($list_bcc as $bcc_list => $bcc){
            $mail->addBCC($bcc);
            // $mail->addBCC('bcc@example.com');
        }
        
    
        ###Attachments
        foreach($list_attach as $attach_list => $attach_file){
           $mail->addAttachment($attach_file);         //Add attachments
           // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name
        }
        
    
        //Content
        $mail->CharSet = 'utf-8';
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = $title;
        $mail->Body    = $content;
        // $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
    
        $mail->send();
        echo 'Message has been sent';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
   }
}



// $mail = new mailer() ; 
// $mail->sent_mail() ; 
?>