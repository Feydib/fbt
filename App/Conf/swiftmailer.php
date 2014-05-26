<?php
$app->register(new Silex\Provider\SwiftmailerServiceProvider());
$app['swiftmailer.options'] = array(
	'transport' => 'smtp',
    'host' => 'localhost',
    'port' => '25',
);

