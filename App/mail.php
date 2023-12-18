<?php 

//Load Composer's autoloader
require_once  __DIR__.'/Phpmailer/vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;


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
    


    #### 3.Special User
    private $mail_admin_1      = 'tung_phung@minebea-as.com' ; 
    private $mail_admin_2      = 'duc_vu@minebea-as.com' ; 
    private $mail_admin_hr     = 'vanltt@minebea-as.com' ;

    private $mail_factory_gm_1 = 'akio_ueda@minebea-as.com';
    private $mail_vn_gm        = 'thangdk@minebea-as.com';
    private $mail_quality_gm   = 'kenji_nakamura@minebea-as.com';
    private $mail_quality_mgr  = 'tran_quy_cuong@minebea-as.com';
    private $mail_oee_pic      = 'thanhld@minebea-as.com';




   public function  sent_mail(){
    $mail = new PHPMailer(true);

    $subject_sent = 'test email'; 
    
    $body_sent =  'test';
    
    
    
    
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
        $mail->addAddress('tung_phung@minebea-as.com', 'Joe User');     //Add a recipient
        // $mail->addAddress('ellen@example.com');               //Name is optional
        // $mail->addReplyTo('info@example.com', 'Information');
        // $mail->addCC('cc@example.com');
        // $mail->addBCC('bcc@example.com');
    
        ###Attachments
        // $mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
        // $mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name
    
        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'Here is the subject';
        $mail->Body    =  $body_sent;
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