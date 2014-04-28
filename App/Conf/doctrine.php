<?php

// Doctrine (db)
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
$isDevMode = true;
$config = Setup::createAnnotationMetadataConfiguration(array(__DIR__.'/App/Model/Entity'), $isDevMode);
$app->register(new Silex\Provider\DoctrineServiceProvider());
$app['db.options'] = array(
        'driver' => 'pdo_mysql',
        'dbhost' => 'localhost',
        'dbname' => 'fbt',
        'user' => 'root',
        'password' => 'root',
);
$app['em'] = EntityManager::create($app['db'], $config);


