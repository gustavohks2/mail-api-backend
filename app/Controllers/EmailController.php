<?php

namespace App\Controllers;

use \App\Models\MailSender;
use \App\Models\Email;

class EmailController {

   public function send($request) {
      header("Content-Type: application/json");

      header("Access-Control-Allow-Origin: *");
      header("Access-Control-Allow-Methods: PUT, GET, POST");
      header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

      $email = $request->post;
      $attachments = $request->files->attachments;
      
      if (!empty($errors = Email::validate($email))) {
         echo json_encode([ "success" => 0, "errors" => $errors ]);
         return;
      }

      $mail = new MailSender();
      if (!$mail->send($email->to, $email->subject, $email->content, $attachments)) {
         echo json_encode([ "success" => 0, "errors" => ["Failed while trying to send email"] ]);
         exit;
      }
         
      echo json_encode([ "success" => 1, "message" => "Email successfully sent" ]);
   }

}