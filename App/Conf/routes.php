<?php
       

$app->mount("/", new App\Controller\IndexController());
$app->mount("/login", new App\Controller\UserController($app['user_provider']));

?>
