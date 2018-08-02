<?php

$routes[] = ["/api/emails", "EmailController@send"];
$routes[] = ["/api/emails/index", "EmailController@index"];

return $routes;