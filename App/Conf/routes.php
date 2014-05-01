<?php
       

$app->mount("/", new App\Controller\IndexController());
$app->mount("/login", new App\Controller\UserController($app['user_provider']));
$app->mount("/tournaments", new App\Controller\TournamentController($app['user_provider']));
$app->mount("/pronostics", new App\Controller\MatchController($app['user_provider']));
$app->mount("/bet", new App\Controller\BetController($app['user_provider']));

?>
