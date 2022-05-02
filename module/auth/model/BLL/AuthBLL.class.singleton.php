<?php
    class AuthBLL {
        private $authDAO;
        private $db;
        private $mailer;
        static $_instance;

        function __construct() {
            $this->mailer = Mailer::getInstance();
            $this->authDAO = AuthDAO::getInstance();
            $this->db = Connection::getInstance();
        }

        public static function getInstance() {
            if (!(self::$_instance instanceof self)) self::$_instance = new self();
            return self::$_instance;
        }

        public function registerUser($args) {
            $return = $this->authDAO->account_register($this->db, $args);

            if (json_decode($return)->result->code == 23) {
                $this->mailer->generateVerificationMail($args["username"], $args["email"], "https://www.google.com");
                return $return;
            }

            return [
                "result" => [
                    "message" => "Error al crear el usuario",
                    "code" => 404
                ]
            ];
        }
    }
?>