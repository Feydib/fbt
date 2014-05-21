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
        'secured' => array(
            'pattern'   => '^/.*$',
            'anonymous' => true,
            'form' => array('login_path' => '/', 'check_path' => '/connexion','default_target_path'=>
'/','always_use_default_target_path'=>true, 'use_referer' => true),
            'logout' => array('logout_path' => '/deconnexion'), // url à appeler pour se déconnecter
            'users' => $app->share(function() use ($app) {
                // La classe App\User\UserProvider est spécifique à notre application et est décrite plus bas
                return $app['user.provider'];
            }),
        ),
    ),
));
$app['security.access_rules'] = array(
    array('^.*/admin.*$', 'ROLE_ADMIN'),
    array('^/login.*$', 'IS_AUTHENTICATED_ANONYMOUSLY'),
    array('^/$', 'IS_AUTHENTICATED_ANONYMOUSLY'),
    array('^/.*$', 'ROLE_ADMIN'),
    
);
$app['security.role_hierarchy'] = array(
    'ROLE_ADMIN' => array('ROLE_USER'),
    
);