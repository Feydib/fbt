<?php
$app->register(new Silex\Provider\SwiftmailerServiceProvider());
$app['swiftmailer.options'] = array(
    'host' => 'smtp.gmail.com',
    'port' => '465',
    'username' => 'user@gmail.com',
    'password' => 'password',
    'encryption' => 'ssl',
     'auth_mode' => 'login'
);

