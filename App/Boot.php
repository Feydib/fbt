<?php

//On ajoute l'autoloader
$loader = require_once __DIR__ . '/../vendor/autoload.php';

//dans l'autoloader nous ajoutons notre répertoire applicatif 
$loader->add("App",dirname(__DIR__));

//Nous instancions un objet Silex\Application
$app = new Silex\Application();

//en dev, nous voulons voir les erreurs
$app['debug'] = true;

//On enregistre l'endroit où l'on retrouve nos vues twig
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__ . '/../App/Views',
));

//On définit notre layout
$app->before(function () use ($app) {
    $app['twig']->addGlobal('layout', $app['twig']->loadTemplate('layout.html.twig'));
});


// Doctrine (db)
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
$isDevMode = true;
$config = Setup::createAnnotationMetadataConfiguration(array(__DIR__.'/App/Model/Repository'), $isDevMode);
$app->register(new Silex\Provider\DoctrineServiceProvider());
$app['db.options'] = array(
        'driver' => 'pdo_mysql',
        'dbhost' => 'localhost',
        'dbname' => 'silex',
        'user' => 'root',
        'password' => 'root',
);
$app['em'] = EntityManager::create($app['db'], $config);

/*
$app['form.extensions'] = $app->share($app->extend('form.extensions', function ($extensions) use($app) {
    $managerRegistry = new ManagerRegistry(null, array(), array('em'), null, null, '\Doctrine\ORM\Proxy\Proxy');
    $managerRegistry->setContainer($app);
    $extensions[] = new DoctrineOrmExtension($managerRegistry);
     
    return $extensions;
}));
*/
//on ajoute la gestion des sessions
$app->register(new Silex\Provider\SessionServiceProvider());
//on ajoute la gestion des url avec "path" 
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
use Symfony\Component\HttpFoundation\Request;

//On définit notre user manager
# user manager
$app['repository.user'] = $app->share(function ($app) {
    return new App\Model\Repository\UserRepository($app['db'], $app['security.encoder.digest'], $app['em']);
});
$app['user_provider']=$app->share(
    function($app){
        return new App\Model\Repository\UserProvider($app['db']);
    }
);

// On définit la politique de sécurité
$app->register(new Silex\Provider\SecurityServiceProvider(), array(
    'security.firewalls'    => array(
        'secured' => array(
            'pattern'   => '^.*$',
            'anonymous' => true,
            'form' => array('login_path' => '/login/login', 'check_path' => '/connexion'),
            'logout' => array('logout_path' => '/deconnexion'), // url à appeler pour se déconnecter
            'users' => $app->share(function() use ($app) {
                // La classe App\User\UserProvider est spécifique à notre application et est décrite plus bas
                return $app['user_provider'];
            }),
        ),
    ),
));
$app['security.access_rules'] = array(
    array('^/login/info$', 'ROLE_USER'),
    array('^/login$','IS_AUTHENTICATED_ANONYMOUSLY'),
);
       
/************* Gestion fichier de langue **********************/
$app->register(new Silex\Provider\TranslationServiceProvider(), array(
    "locale_fallback" => "fr",
));
use Symfony\Component\Translation\Loader\YamlFileLoader;
$app['translator'] = $app->share($app->extend('translator', function($translator, $app) {
    $translator->addLoader('yaml', new YamlFileLoader());
    $translator->addResource('yaml', __DIR__.'/locales/fr.yml', 'fr');

    return $translator;
}));

$app['translator.loader'] = new Symfony\Component\Translation\Loader\YamlFileLoader();

/*****************  Inclusion des ROUTES *************************/ 
// FR: Obtenir les routes.
require 'routes.php';
# validation
$app->register(new Silex\Provider\ValidatorServiceProvider());
# form
$app->register(new Silex\Provider\FormServiceProvider());




/*
$app->register(
    new Silex\Provider\SecurityServiceProvider(),
    array(
        'security.firewalls' => array(
            'default' => array(
                'ldap' => true,
                'pattern' => '^/hello/',
                'form' => array(
                    'login_path' => '/',
                    'check_path' => '/connexion',
                ),
                'logout' => array(
                       'logout_path' => '/admin/logout'
                ),
                'users' => $app->share(
                    function () use ($app) {
                        return new App\LdapUserProvider(
                            array('ROLE_USER')
                        );
                    }
                ),
            ),
        ),
    )
);
$app['security.authentication_listener.factory.ldap'] = $app->protect(
    function ($name, $options) use ($app) {
        $app['security.authentication_provider.'.$name.'.ldap'] = $app->share(
            function () use ($app) {
                return new App\LdapAuthenticationProvider(
                    $app['security.user_provider.default'],
                    'l'
                );
            }
        );
     
        $app['security.authentication_listener.'.$name.'.ldap'] = $app->share(
            function () use ($app) {
                return new App\LdapAuthenticationListener(
                        $app['security'],
                        $app['security.authentication_manager'],
                        $app['security.http_utils']
                );
            }
        );
     
        return array(
            'security.authentication_provider.'.$name.'.ldap',
                'security.authentication_listener.'.$name.'.ldap',
                null,
                'form'
        );
    }
);*/

$app->run();
?>
