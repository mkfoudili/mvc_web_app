<?php
require_once __DIR__ . '/../view/ContactView.php';

class ContactController {
    private $labEmail = "mk_foudili@esi.dz"; // !! remndder to replace with lab email later

    public function index() {
        $view = new ContactView();
        $view->renderForm();
    }

    public function send() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo "Method not allowed";
            return;
        }

        $from    = $_POST['email'] ?? '';
        $subject = $_POST['subject'] ?? '';
        $message = $_POST['message'] ?? '';

        if (empty($from) || empty($subject) || empty($message)) {
            $view = new ContactView();
            $view->renderError("All fields are required.");
            return;
        }

        $headers = "From: " . $from . "\r\n" .
                "Reply-To: " . $from . "\r\n" .
                "X-Mailer: PHP/" . phpversion();

        $success = @mail($this->labEmail, $subject, $message, $headers);

        $view = new ContactView();
        if ($success) {
            $view->renderSuccess();
        } else {
            http_response_code(500);
            $view->renderError("We could not send your message at the moment. 
                                Please try again later or contact the lab directly.");
        }
    }

}