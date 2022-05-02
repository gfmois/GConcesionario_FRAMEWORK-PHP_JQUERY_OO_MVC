<?php
    class AuthController {
        function view() {
            common::loadView('top_page.html', VIEW_PATH_AUTH . 'AuthForm.html');
        }

        function register() {
            echo json_encode(common::loadModel('AuthModel', 'loadRegisterUser', $_POST));
        }
    }
?>