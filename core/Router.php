<?php

namespace Core;

class Router {

   private $routes;

   public function __construct(array $routes) {
      $this->setRoutes($routes);
      $this->init();
   }

   private function setRoutes(array $routes) {
      foreach($routes as $route) {
         $controllerAndAction = explode("@", $route[1]);
         $this->routes[] = [$route[0], $controllerAndAction[0], $controllerAndAction[1]];
      }
   }

   private function getRequest() {
      $request = new \stdClass();
      $request->get = new \stdClass();
      $request->post = new \stdClass();
      $request->files = new \stdClass();

      foreach($_GET as $key => $value) {
         $request->get->$key = $value;
      }

      foreach($_POST as $key => $value) {
         $request->post->$key = $value;
      }

      foreach($_FILES as $key => $value) {
         $request->files->$key = $value;
      }

      return $request;
   }

   private function getURL() {
      return parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
   }

   private function init() {
      $url = $this->getURL();
      $urlArray = explode("/", $url);
      $params = [];
      $found = false;

      foreach($this->routes as $route) {
         $routeArray = explode("/", $route[0]);

         for($i = 0; $i < count($routeArray); $i++) {

            if (strpos($routeArray[$i], "{") !== false && (count($routeArray) === count($urlArray))) {
               $routeArray[$i] = $urlArray[$i];
               $params[] = $urlArray[$i];
            }
            
            $route[0] = implode("/", $routeArray);
         }

         if ($url === $route[0]) {
            $found = true;
            $controller = $route[1];
            $action = $route[2];
            break;
         }
      }

      if ($found) $this->callActionOfController($controller, $action, $params);
   }

   private function callActionOfController(string $controller, string $action, array $params) {
      $controller = (new \Core\Container)->getNewController($controller);

      if (method_exists($controller, $action)) {
         $params = count($params) > 3 ? array_slice($params, 0, 3) : $params;
         $params[] = $this->getRequest();   

         call_user_func_array([$controller, $action], $params);  
      }
   }
}