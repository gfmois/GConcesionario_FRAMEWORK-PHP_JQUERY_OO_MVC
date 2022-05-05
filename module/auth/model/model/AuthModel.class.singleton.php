<?php
    class AuthModel {
        private $bll;
        static $_instance;

        function __construct() {
            $this->bll = AuthBLL::getInstance();
        }

        public static function getInstance() {
            if (!(self::$_instance instanceof self)) self::$_instance = new self();
            return self::$_instance;
        }

        public function loadRegisterUser($args) {
            return $this->bll->registerUser($args);
        }

        public function loadVerification($args) {
            return $this->bll->verificationBLL($args);
        }
    }
?>