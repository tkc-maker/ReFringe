<?php
require $_SERVER['DOCUMENT_ROOT'] . '/TEST/vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

function emailMessage($destination, $subject, $body, $altBody, $returnJson=false){   
    $returnValue = false;
    $mail = new PHPMailer(true);    
    try {
        //Server settings
        $mail->SMTPDebug = 0;                                       // Enable no debug output
        $mail->isSMTP();                                            // Set mailer to use SMTP
        $mail->Host       = 'mail.refringe.org';                    // Specify main and backup SMTP servers
        $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $mail->Username   = 'info@refringe.org';                    // SMTP username
        $mail->Password   = 'R1e8F2r2I5nGe';                        // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            // Enable TLS encryption, `PHPMailer::ENCRYPTION_SMTPS` also accepted
        $mail->Port       = 465;                                    // TCP port to connect to

        //Recipients
        $mail->setFrom('info@refringe.org', 'Mailer');
        $mail->addAddress($destination, 'Recipient');    // Add a recipient
        if($subject === 'Confirmation of Proposal Submission to ReFringe'){
            $mail->addBCC('steelcrazybooking@gmail.com', $subject);
            $mail->addBCC('dgadamczyk@gmail.com', $subject);
        }
        $mail->addReplyTo('info@refringe.org', 'Information');

        // Content
        $mail->isHTML(true);                                        // Set email format to HTML
        $mail->Subject = $subject;
        $mail->Body    = $body;
        $mail->AltBody = $altBody;

        $mail->send();
        if($returnJson){
            return json_encode(["status" => "true", 
                "errMsg" => "A message containing your $subject has been sent to the email we have on file."]);
        }else{
            echo "<script>alert('A message containing your ".$subject." has been sent to the email we have on file.');</script>";
            $returnValue = true;
        }
        
    }catch (Exception $e){
        if($returnJson){
            return json_encode(["status" => "false", 
                "errMsg" => "Message could not be sent. Mailer Error: {$mail->ErrorInfo}."]);
        }else{
            echo "<script>alert('Message could not be sent. Mailer Error: {$mail->ErrorInfo}');</script>";
            $returnValue = false;
        }
    }
    return $returnValue;
}
