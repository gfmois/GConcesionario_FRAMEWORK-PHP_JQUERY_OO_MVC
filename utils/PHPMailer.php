<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    use PHPMailer\PHPMailer\SMTP;

    $path = $_SERVER["DOCUMENT_ROOT"] . '/GConcesionario_FRAMEWORK_JQUERY_OO_MVC/';
    $smtpInfo = parse_ini_file('../model/Config.ini', true);

    require $path . 'vendor/autoload.php';
    
    $mail = new PHPMailer(true);
    
    try {
        // Server Site
        $mail->isSMTP();
        $mail->SMTPDebug    = 2;
        $mail->SMTPAuth     = true;
        $mail->SMTPSecure   = 'tls';
        $mail->Host         = $smtpInfo["SMTP"]["host"];
        $mail->Port         = 587;
        $mail->Username     = $smtpInfo["SMTP"]["username"];
        $mail->Password     = $smtpInfo["SMTP"]["password"];

        // Recipient
        $mail->setFrom('contact@gconcesionario.com', 'Mailer');
        $mail->addAddress($smtpInfo["SMTP"]["toAddress"], 'Moisés Guerola');
        $mail->addReplyTo('contact@gconcesionario.com', "Contact US");
        
        // Content
        $mail->isHTML(true);
        $mail->Subject      = "Confirmation Account";
        $mail->Body         = "Your Account has been created";

        $mail->send();
        echo "Mensaje enviado";
    } catch (Exception $e) {
        echo "Message could not be sent. Mail error: {$mail->ErrorInfo}";
    }
?>