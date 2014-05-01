<?php

//On ajoute l'autoloader
$loader = require_once __DIR__ . '/../vendor/autoload.php';

//dans l'autoloader nous ajoutons notre répertoire applicatif 
$loader->add("App",dirname(__DIR__));

//Nous instancions un objet Silex\Application
$app = new Silex\Application();

//en dev, nous voulons voir les erreurs
$app['debug'] = true;

//configuration twig
require __DIR__ . '/../App/Conf/twig.php';

//doctrine conf
require __DIR__ . '/../App/Conf/doctrine.php';

//on ajoute la gestion des sessions
$app->register(new Silex\Provider\SessionServiceProvider());
//on ajoute la gestion des url avec "path" 
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());

//politique de securité et userManager
require __DIR__ . '/../App/Conf/security.php';       

// FR: Obtenir la configuration lang.
require __DIR__ . '/../App/Conf/lang.php';

// Config mail
require __DIR__ . '/../App/Conf/swiftmailer.php';

/*****************  Inclusion des ROUTES *************************/ 
// FR: Obtenir les routes.
require __DIR__ . '/../App/Conf/routes.php';
# validation
$app->register(new Silex\Provider\ValidatorServiceProvider());
# form
$app->register(new Silex\Provider\FormServiceProvider());


$app->run();

