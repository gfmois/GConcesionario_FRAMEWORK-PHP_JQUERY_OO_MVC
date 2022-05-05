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
                $tokenEmail = common::generate_token_secure(20);
                $query = "INSERT INTO users(uuid, verificated, username, password, email, token_email, avatar) 
                VALUES (" . "'" . common::generate_token_secure(20) . "', " . 0 . ", '" . $userInfo['username'] . "', 
                '" . password_hash($userInfo['password'], PASSWORD_DEFAULT, ["cost" => 12]) . 
                "', " . "'" . $userInfo['email'] . "', '" . $tokenEmail . "', '" . $userInfo['avatar'] . "')";

                $stmt_register = $db->select($query);
                if ($stmt_register) {
                    return json_encode([
                        "result" => [
                            "message" => "Usuario creado correctamente",
                            "code" => 23,
                            "token" => $tokenEmail
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

        public function validateUser(Connection $db, $emailToken) {
            $sql = "SELECT verificated FROM users WHERE token_email LIKE " . "'" . $emailToken . "'";
            
            $stmt = $db->select($sql);
            
            if (mysqli_num_rows($stmt) > 0) {
                if ($db->list($stmt)[0]["verificated"] == 0) {
                    $query = "UPDATE users SET verificated = 1 WHERE token_email LIKE " . "'" . $emailToken . "'";
                    $db->select($query);
    
                    return [
                        "result" => [
                            "message" => "Usuario verificado correctamente",
                            "code" => 823
                        ]
                    ];
                } else {
                    return [
                        "result" => [
                            "message" => "Usuario ya verificado",
                            "code" => 712
                        ]
                    ];
                }
            } else {
                return [
                    "result" => [
                        "message" => "Algo ha ido mal, porfavor solicite otro correo para verificar la cuenta.",
                        "code" => 322
                    ]
                ];
                
            }
        }
    }
?>