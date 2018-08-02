<?php

namespace Core;

class Container {

   public function getNewController($controller) {
      $controller = "App\\Controllers\\" . $controller;
      return new $controller;
   }
}