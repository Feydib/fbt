<?php
// cli-config.php
// 
//Nous instancions un objet Silex\Application
$app = new Silex\Application();

// Doctrine (db)
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
$isDevMode = true;
$config = Setup::createAnnotationMetadataConfiguration(array(__DIR__.'\..\App\Model\Entity'), $isDevMode);
$app->register(new Silex\Provider\DoctrineServiceProvider());
$app['db.options'] = array(
        'driver' => 'pdo_mysql',
        'dbhost' => 'localhost',
        'dbname' => 'fbt',
        'user' => 'root',
        'password' => 'root',
);
$app['em'] = EntityManager::create($app['db'], $config);

use Doctrine\ORM\Tools\Console\ConsoleRunner;

$entityManager = $app['em'];

return ConsoleRunner::createHelperSet($entityManager);

//CMD

//Create entity FROM DB
// php vendor/doctrine/orm/bin/doctrine orm:convert-mapping --from-database annotation App/Model/Entity

//Check that the DB and Entities are same
// php vendor/doctrine/orm/bin/doctrine orm:validate-shema

//Update DB from entities
// php vendor/doctrine/orm/bin/doctrine orm:schema-tool:update

// More on http://doctrine-orm.readthedocs.org/en/latest/reference/tools.html ....