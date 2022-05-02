<?php
    class AuthDAO {
        static $_instance;

        private function __construct() {}

        public static function getInstance() {
            if (!(self::$_instance instanceof self)) self::$_instance = new self();
            return self::$_instance;
        }

        function account_register(Connection $db, $userInfo) {
            $sql = "SELECT * 
                    FROM users 
                    WHERE username 
                    LIKE " . "'" . $userInfo['username'] . "' 
                    OR email LIKE "  . "'" . $userInfo["email"] . "';";
            
            $stmt = $db->select($sql);

            if (mysqli_num_rows($stmt)) {
                return json_encode([
                    "result" => [
                        "message" => "Nomber de Usuario o Email ya utilizado.",
                        "code" => 4
                    ]
                ]);
            } else {
                $query = "INSERT INTO users(username, password, email, avatar) 
                VALUES (" . "'" . $userInfo['username'] . "', 
                '" . password_hash($userInfo['password'], PASSWORD_DEFAULT, ["cost" => 12]) . 
                "', " . "'" . $userInfo['email'] . "', '"  . $userInfo['avatar'] . "')";

                $stmt_register = $db->select($query);
                if ($stmt_register) {
                    return json_encode([
                        "result" => [
                            "message" => "Usuario creado correctamente",
                            "code" => 23
                        ]
                    ]);
                } else {
                    return json_encode([
                        "result" => [
                            "message" => "Error al crear el usuario",
                            "code" => 56
                        ]
                    ]);
                }
            }
        } 
    }
?>