<?php

include_once("../inc/bootstrap.php"); // include bootstrap file
$config = parse_ini_file('../config/config.ini', true); // include config file
$key = $config['keys']['sendgridapikey']; // get sendgrid api key

class EmailSender
{
    private $key;

    public function __construct($key)
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
