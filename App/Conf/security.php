<?php

//On définit notre user manager
# user manager
$app['user.provider']=$app->share(
    function($app){
        return new App\Model\Repository\UserProvider($app['db']);
    }
);

// On définit la politique de sécurité
$app->register(new Silex\Provider\SecurityServiceProvider(), array(
    'security.firewalls'    => array(
        'login_path' => array(
                'pattern' => '^/login.*$',
                'anonymous' => true
            ),
        'secured' => array(
            'pattern'   => '^/.*$',
            'form' => array('login_path' => '/login/login', 'check_path' => '/connexion'),
            'logout' => array('logout_path' => '/deconnexion'), // url à appeler pour se déconnecter
            'users' => $app->share(function() use ($app) {
                // La classe App\User\UserProvider est spécifique à notre application et est décrite plus bas
                return $app['user.provider'];
            }),
        ),
    ),
));
$app['security.access_rules'] = array(
    array('^/login.*$', 'IS_AUTHENTICATED_ANONYMOUSLY'),
    array('^/.+$', 'ROLE_USER')
);