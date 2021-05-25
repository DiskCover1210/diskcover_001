<?php 
/**
 * 
 */

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
require 'vendor/autoload.php';

class enviar_emails
{
	// private $mail;
	function __construct()
	{
		
	}


	function enviar_email($archivos=false,$to_correo,$cuerpo_correo,$titulo_correo,$correo_apooyo,$nombre,$EMAIL_CONEXION,$EMAIL_CONTRASEÑA,$HTML=false,$gmial=0)
	{
		$to =explode(',', $to_correo);
     foreach ($to as $key => $value) {
  		 $mail = new PHPMailer();
         //Server settings
         $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
         $mail->isSMTP();                                            //Send using SMTP
         $mail->Host       = 'mail.diskcoversystem.com';                     //Set the SMTP server to send through
         $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
         // $mail->Username   = 'matriculas@diskcoversystem.com';                     //SMTP username
         // $mail->Password   = 'DiskCover1210';                               //SMTP password
         $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
         $mail->SMTPSecure = 'ssl';
         $mail->Port       = 465;                                    //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
	     $mail->Username = $EMAIL_CONEXION;  //EMAIL_CONEXION DE TABLA EMPRESA
	     $mail->Password = $EMAIL_CONTRASEÑA; //EMAIL_CONTRASEÑA DE LA TABLA EMPRESA
	     $mail->setFrom($correo_apooyo,$nombre);

         $mail->addAddress($value);
         $mail->Subject = $titulo_correo;
         if($HTML)
         {
          $mail->isHTML(true);
         }
         $mail->Body = $cuerpo_correo; // Mensaje a enviar


         if($archivos)
         {
          foreach ($archivos as $key => $value) {
           if(file_exists('../../php/vista/TEMP/'.$value))
            {
          //		print_r('../vista/TEMP/'.$value);
          
         	  $mail->AddAttachment('../../php/vista/TEMP/'.$value);
             }          
          }         
        }
          if (!$mail->send()) 
          {
          	$respuesta = false;
     	    }
    }

 
 
  }

  function enviar_credenciales($archivos=false,$to_correo,$cuerpo_correo,$titulo_correo,$correo_apooyo,$nombre,$EMAIL_CONEXION,$EMAIL_CONTRASEÑA,$HTML=false,$gmail=0)
  {
    $to =explode(',', $to_correo);
     foreach ($to as $key => $value) {
       $mail = new PHPMailer();
       $mail->SMTPDebug = SMTP::DEBUG_SERVER;  
       $mail->isSMTP();                                            //Send using SMTP
       if($gmail)
       {
         //Server settings
         // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //respuesta del servidor
         $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
         $mail->SMTPAuth   = true;
         $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
         $mail->Port       = 587;                          //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
          }else
       {

         $mail->Host       = 'mail.diskcoversystem.com';                     //Set the SMTP server to send through
         $mail->SMTPAuth   = true;
         $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
         $mail->Port       = 587;                          //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
       }
        $mail->setFrom($correo_apooyo,$nombre);
        $mail->addAddress($value);
        $mail->Subject = $titulo_correo;
        $mail->Username = $EMAIL_CONEXION;  //EMAIL_CONEXION DE TABLA EMPRESA
        $mail->Password = $EMAIL_CONTRASEÑA; //EMAIL_CONTRASEÑA DE LA TABLA EMPRESA
        




         if($HTML)
         {
          $mail->isHTML(true);
         }
         $mail->Body = $cuerpo_correo; // Mensaje a enviar


         if($archivos)
         {
          foreach ($archivos as $key => $value) {
           if(file_exists('../../php/vista/TEMP/'.$value))
            {
          //    print_r('../vista/TEMP/'.$value);
          
            $mail->AddAttachment('../../php/vista/TEMP/'.$value);
             }          
          }         
        }
           if (!$mail->send()) 
          {
            return -1;
          }else
          {
            return 1;
          }
    }
  }


  function recuperar_clave($archivos=false,$to_correo,$cuerpo_correo,$titulo_correo,$correo_apooyo,$nombre,$EMAIL_CONEXION,$EMAIL_CONTRASEÑA)
  {
    $to =explode(',', $to_correo);
     foreach ($to as $key => $value) {
       $mail = new PHPMailer();
         //Server settings
         // $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
         $mail->isSMTP();                                            //Send using SMTP
         $mail->Host       = 'mail.diskcoversystem.com';                     //Set the SMTP server to send through
         $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
         // $mail->Username   = 'matriculas@diskcoversystem.com';                     //SMTP username
         // $mail->Password   = 'DiskCover1210';                               //SMTP password
         $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
         $mail->SMTPSecure = 'ssl';
         $mail->Port       = 465;                                    //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
       $mail->Username = $EMAIL_CONEXION;  //EMAIL_CONEXION DE TABLA EMPRESA
       $mail->Password = $EMAIL_CONTRASEÑA; //EMAIL_CONTRASEÑA DE LA TABLA EMPRESA
       $mail->setFrom($correo_apooyo,$nombre);

         $mail->addAddress($value);
         $mail->Subject = $titulo_correo;
         $mail->Body = $cuerpo_correo; // Mensaje a enviar


         if($archivos)
         {
          foreach ($archivos as $key => $value) {
           if(file_exists('../../php/vista/TEMP/'.$value))
            {
          //    print_r('../vista/TEMP/'.$value);
          
            $mail->AddAttachment('../../php/vista/TEMP/'.$value);
             }          
          }         
        }
          if (!$mail->send()) 
          {
            return -1;
          }else
          {
            return 1;
          }
    }
  }


  

}
?>