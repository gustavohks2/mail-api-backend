<?php

namespace App\Models;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\PHPException;

class MailSender {

   private $mail;
   const DEFAULT_UPLOAD_DIR = __DIR__ . "/../../storage/email_attachments";

   public function __construct() {
      $this->mail = new PHPMailer;
      $this->set_config();
   }

   private function set_config() {
      // SMTP config
      $this->SMTPDebug = 2;
      $this->mail->isSMTP();
      $this->mail->CharSet = "UTF-8";
      $this->mail->Port = 587;
      $this->mail->SMTPAuth = true;
      $this->mail->SMTPSecure = "tls";
      $this->mail->Host = "smtp.gmail.com";

      // User Authentication
      $this->mail->Username = "email.tester.mail.api@gmail.com";
      $this->mail->Password = "emailtester";

      // Sender
      $this->mail->setFrom("gustavohks2@gmail.com", "Mail API");
   }

   public function send($email_to, $subject, $content, array $attachments) {
      // Receiver
      $this->mail->addAddress($email_to);

      // Subject and Email body
      $this->mail->Subject = trim(htmlspecialchars($subject));
      $this->mail->Body = trim(htmlspecialchars($content));

      if ($filepaths = $this->handleAttachments($attachments)) {
         foreach($filepaths as $attachment) {
            $this->mail->addAttachment($attachment);
         }
      }
      
      return $this->mail->send();
   }

   private function handleAttachments(array $attachments) {
      $filepaths = [];

      for($i = 0; $i < count($attachments["name"]); $i++) {

         $ext = pathinfo($attachments["name"][$i], PATHINFO_EXTENSION);
         $num = $i + 1;
         $filepath = self::DEFAULT_UPLOAD_DIR . "/attachment_{$num}.{$ext}";
         
         if (move_uploaded_file($attachments["tmp_name"][$i], $filepath))
            $filepaths[] = $filepath;
         else {
            return false; 
            exit;
         }
      }
      
      return $filepaths;
   }
}