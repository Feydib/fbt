<?php
//$app = new Silex\Application();
$app->register(new DerAlex\Silex\YamlConfigServiceProvider(__DIR__.'/conf.yml'));

// Doctrine (db)
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
$isDevMode = true;
$config = Setup::createAnnotationMetadataConfiguration(array(__DIR__.'/App/Model/Entity'), $isDevMode);
$app->register(new Silex\Provider\DoctrineServiceProvider());

$app['db.options'] = array(
        'driver' => $app['config']['database']['driver'],
        'dbhost' => $app['config']['database']['host'],
        'dbname' => $app['config']['database']['dbname'],
        'user' => $app['config']['database']['user'],
        'password' => $app['config']['database']['password'],
		'charset' => $app['config']['database']['charset'],
);

$app['em'] = EntityManager::create($app['db'], $config);


