<?php

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