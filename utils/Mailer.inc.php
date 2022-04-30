<?php
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    use PHPMailer\PHPMailer\SMTP;    

    require_once 'vendor/autoload.php';

    class Mailer {
        private $iniFile;
        static $_instance;

        private function __construct() {
            $this->iniFile = parse_ini_file('model/Config.ini', true);
        }

        public static function getInstance() {
            if (!(self::$_instance instanceof self)) self::$_instance = new self();
            return self::$_instance;
        }

        public function generateMail($name, $account, $text, $theme) {
            $mail = new PHPMailer(true);
    
            try {
                // Server Site
                $mail->isSMTP();
                // $mail->SMTPDebug    = 2;
                $mail->SMTPAuth     = true;
                $mail->SMTPSecure   = 'tls';
                $mail->Host         = $this->iniFile["SMTP"]["host"];
                $mail->Port         = 587;
                $mail->Username     = $this->iniFile["SMTP"]["username"];
                $mail->Password     = $this->iniFile["SMTP"]["password"];

                // Recipient
                $mail->setFrom($account, $name);
                $mail->addAddress($this->iniFile["SMTP"]["toAddress"], 'Contact Us');
                $mail->addReplyTo($account, $name);
                
                // Content
                $mail->isHTML(true);
                $mail->Subject      = $theme;
                $mail->Body         = $text;

                $mail->send();

                return [
                    "result" => [
                        "message" => "Mensaje enviado, revise el correo",
                        "code" => 200
                    ]
                ];
            } catch (Exception $e) {
                echo "Message could not be sent. Mail error: {$mail->ErrorInfo}";
            }
        }
    }

?>