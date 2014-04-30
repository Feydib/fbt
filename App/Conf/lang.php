<?php

/************* Gestion fichier de langue **********************/
$app->register(new Silex\Provider\TranslationServiceProvider(), array(
    "locale" => "fr",
));
use Symfony\Component\Translation\Loader\YamlFileLoader;
use Symfony\Component\Translation\Loader\XliffFileLoader;
$app['translator'] = $app->share($app->extend('translator', function($translator, $app) {
    $translator->addLoader('yaml', new YamlFileLoader());
    $translator->addResource('yaml', __DIR__.'/../../web/locales/fr.yml', 'fr');
    $translator->addLoader('xliff', new XliffFileLoader());
    $file = __DIR__ .'/../../vendor/symfony/validator/Symfony/Component/Validator/Resources/translations/validators.fr.xlf';
    $translator->addResource('xliff', $file , 'fr');
    return $translator;
}));

