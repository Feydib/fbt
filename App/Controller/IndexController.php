<?php
namespace App\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;


class IndexController implements ControllerProviderInterface {


    public function index(Application $app) {
       return $app["twig"]->render("index.twig");
    }

    public function connect(Application $app) {
        // créer un nouveau controller basé sur la route par défaut
        $index = $app['controllers_factory'];
        $index->match("/", 'App\Controller\IndexController::index')->bind("index.index");

        return $index;
    }

}
