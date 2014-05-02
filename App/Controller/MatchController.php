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
    
    /**
     * Match which we can put result
     * @param \Silex\Application $app
     * @return type
     */
    public function matchToScore(Application $app) {
       $matchRepository = $app['em']->getRepository('App\Model\Entity\Matchs');
       $matchList = $matchRepository->find(null, 0 , array('date' => 'ASC')); 

       return $app["twig"]->render("match/list.twig", array('matchs' => $matchList));
    }
    
        /**
     * Form to bet on a match
     * @param \Silex\Application $app
     * @return type
     */
    public function scoreForm(Application $app, $idMatchTeam) {
        $betForm = $app['form.factory']->create(new \App\Form\ScoreType($idMatchTeam));
        return $app['twig']->render('match/scoreForm.twig', array("form" => $betForm->createView()));
    }
    
    /**
     * Form to bet on a match
     * @param \Silex\Application $app
     * @return type
     */
    public function penForm(Application $app, $idMatchTeam) {
        $betForm = $app['form.factory']->create(new \App\Form\PenType($idMatchTeam));
        return $app['twig']->render('match/scoreForm.twig', array("form" => $betForm->createView()));
    }
    
    /**
     * Form to bet on a match
     * @param \Silex\Application $app
     * @return type
     */
    public function doScore() {
        $matchTeamRepository = $this->app['em']->getRepository('App\Model\Entity\Matchteam');
        $matchTeamList = $matchTeamRepository->find(null, 0, array("score" => NULL)); 
 
        foreach($matchTeamList as $matchTeam) {
            $matchTeamIdList[] = $matchTeam->getIdmatchteam();
        }
        
        $betForm = $this->app['form.factory']->create(new \App\Form\ScoreType($matchTeamIdList));
        $betForm->bind($this->app['request']);
        if ($betForm->isValid()){
            $datas = $betForm->getData();

            foreach($matchTeamList as $matchTeam) {
                if ($datas["score".$matchTeam->getIdmatchteam()] != "-") {
                    $matchTeam->setScore($datas["score".$matchTeam->getIdmatchteam()]);
                }
                if ($datas["pen".$matchTeam->getIdmatchteam()] != "-") {
                    $matchTeam->setPen($datas["pen".$matchTeam->getIdmatchteam()]);
                }
                $matchTeamRepository->update($matchTeam);
            }
        }
       
        return $this->app->redirect($this->app["url_generator"]->generate("match.matchToScore"));
    }

    public function connect(Application $app) {
        // créer un nouveau controller basé sur la route par défaut
        $this->app = $app;
        $match = $app['controllers_factory'];
        $match->match("/", 'App\Controller\MatchController::index')->bind("match.index");
        $match->get("/admin/match",  array($this,"matchToScore"))->bind("match.matchToScore");
        $match->get("/admin/match/score/{idMatchTeam}", array($this,"scoreForm") )->bind("match.scoreForm");
        $match->get("/admin/match/pen/{idMatchTeam}", array($this,"penForm") )->bind("match.penForm");
        $match->post('/admin/match/doScore', array($this,"doScore"))->bind('match.doScore');
        return $match;
    }

}
