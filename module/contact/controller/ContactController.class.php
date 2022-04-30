<?php
    class ContactController {
        function view() {
            common::loadView('top_page.html', VIEW_PATH_CONTACT . 'contact.html');
        }

        function sendContactMessage() {
            echo json_encode(Mailer::getInstance()->generateMail($_POST['name'], $_POST['account'], $_POST["text"], $_POST['theme']));
        }
    }

?>