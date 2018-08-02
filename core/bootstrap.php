<?php

$routes = require_once __DIR__. "/../app/routes.php";
(new \Core\Router($routes));