<?php
    class AuthBLL {
        private $authDAO;
        private $db;
        private $mailer;
        private $jwt;
        private $parseIni;
        static $_instance;

        function __construct() {
            $this->mailer = Mailer::getInstance();
            $this->authDAO = AuthDAO::getInstance();
            $this->db = Connection::getInstance();
            $this->jwt = JWT::getInstance();
            $this->parseIni = parse_ini_file('model/Config.ini', true);
        }

        public static function getInstance() {
            if (!(self::$_instance instanceof self)) self::$_instance = new self();
            return self::$_instance;
        }

        public function registerUser($args) {
            $return = json_decode($this->authDAO->account_register($this->db, $args));

            if ($return->result->code == 23) {
                $this->mailer->generateVerificationMail($args["username"], $args["email"], "http://localhost/GConcesionario_FRAMEWORK_JQUERY_OO_MVC/auth/verification/" . $return->result->token);
                return [
                    "result" => [
                        "message" => $return->result->message,
                        "code" => $return->result->code
                    ]
                ];
            }

            return [
                "result" => [
                    "message" => "Error al crear el usuario",
                    "code" => 404
                ]
            ];
        }

        public function verificationBLL($args) {
            return $this->authDAO->validateUser($this->db, $args);
        }
    }
?>