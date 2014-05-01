<?php
namespace App\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;


class MatchController implements ControllerProviderInterface {


    public function index(Application $app) {
       $matchRepository = $app['em']->getRepository('App\Model\Entity\Matchs');
       $matchList = $matchRepository->find(null, 0 , array('date' => 'ASC')); 

       return $app["twig"]->render("match/index.twig", array('matchs' => $matchList));
    }

    public function connect(Application $app) {
        // créer un nouveau controller basé sur la route par défaut
        $match = $app['controllers_factory'];
        $match->match("/", 'App\Controller\MatchController::index')->bind("match.index");

        return $match;
    }

}
