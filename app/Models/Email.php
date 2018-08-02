<?php

namespace App\Models;

class Email {

   public static function validate($email) {
      $errors = [];

      if (isset($email->to) && isset($email->subject) &&
          isset($email->content)) {

         if (empty(trim(htmlspecialchars($email->to)))) 
            $errors[] = "Email must be provided";
         if (empty(trim(htmlspecialchars($email->subject))))
            $errors[] = "Subject must be provided";
         if (empty(trim(htmlspecialchars($email->content))))
            $errors[] = "Content must be provided";

      } else $errors[] = "Undefined error!";
         
      return $errors;
   }

}