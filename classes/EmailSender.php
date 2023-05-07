<?php

include_once("../inc/bootstrap.php");
$config = parse_ini_file('../config/config.ini', true);
$key = $config['keys']['sendgridapikey'];

class EmailSender
{
    private $key;

    public function setKey($key)
    {
        $this->key = $key;
        require_once('../vendor/autoload.php');
    }

    public function sendEmail($from, $to, $subject, $text_content, $html_content)
    {
        $email = new \SendGrid\Mail\Mail();
        $email->setFrom($from);
        $email->setSubject($subject);
        $email->addTo($to);
        $email->addContent("text/plain", $text_content);
        $email->addContent("text/html", $html_content);
        $sendgrid = new \SendGrid($this->key);
        try {
            $response = $sendgrid->send($email);
            return $response->statusCode();
        } catch (Exception $e) {
            return false;
        }
    }
}
