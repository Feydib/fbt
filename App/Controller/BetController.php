<?php
namespace App\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use App\Model\Entity\Betmatchs;


class BetController implements ControllerProviderInterface {

    /**
     * Form to bet on a match
     * @param \Silex\Application $app
     * @return type
     */
    public function betForm(Application $app, $idMatchTeam) {
        //var_dump($idMatchTeam);
        //var_dump($this->getPronostic($app, $idMatchTeam));
        $betForm = $app['form.factory']->create(new \App\Form\BetType($idMatchTeam, $this->getPronostic($app, $idMatchTeam)));
        return $app['twig']->render('match/betForm.twig', array("form" => $betForm->createView()));
    }
    
    /**
     * Form to bet on a match
     * @param \Silex\Application $app
     * @return type
     */
    public function doBet() {
        $matchTeamRepository = $this->app['em']->getRepository('App\Model\Entity\Matchteam');
        $betMatchRepository = $this->app['em']->getRepository('App\Model\Entity\Betmatchs');
        $matchTeamList = $matchTeamRepository->find(null, 0, array("score" => NULL)); 
 
        //var_dump($matchTeamList);
        foreach($matchTeamList as $matchTeam) {
            //var_dump($matchTeamIdList->getIdmatchteam());
            $matchTeamIdList[] = $matchTeam->getIdmatchteam();
        }
        
        $betForm = $this->app['form.factory']->create(new \App\Form\BetType($matchTeamIdList));
        $betForm->bind($this->app['request']);
        if ($betForm->isValid()){
            $datas = $betForm->getData();

            foreach($matchTeamList as $matchTeam) {
                $userRepository = $this->app['em']->getRepository('App\Model\Entity\Players');
                $user = $userRepository->getUserByUsername($this->app['security']->getToken()->getUser()->getUsername());
                
                $betMatch = $betMatchRepository->find(array("idmatchteam" => $matchTeam, "idplayers" => $user ));
                if ($betMatch) {
                    $betMatch->setScore($datas["score".$matchTeam->getIdmatchteam()]);
                    $betMatchRepository->update($betMatch);
                } else {
                    $betMatch = new Betmatchs();
                    $betMatch->setIdmatchteam($matchTeam);
                    $betMatch->setIdplayers($user);
                    $betMatch->setScore($datas["score".$matchTeam->getIdmatchteam()]);
                    $betMatchRepository->save($betMatch);
                }
            }
        }
       
        return $this->app->redirect($this->app["url_generator"]->generate("match.index"));
    }
    
    /**
     * Get bet score if players bet on this matchteam
     * @param \Silex\Application $app
     * @param type $matchTeam
     * @return int
     */
    public function getPronostic(Application $app, $matchTeam ) {
        $userRepository = $app['em']->getRepository('App\Model\Entity\Players');
        $matchTeamRepository = $app['em']->getRepository('App\Model\Entity\Matchteam');
        $betMatchRepository = $app['em']->getRepository('App\Model\Entity\Betmatchs');
        
        $user = $userRepository->getUserByUsername($app['security']->getToken()->getUser()->getUsername());
        $matchTeam = $matchTeamRepository->find(null, 0, array("idmatchteam" => $matchTeam));   
        $betMatch = $betMatchRepository->find(array("idmatchteam" => $matchTeam, "idplayers" => $user ));
        if ($betMatch) {
            $score = $betMatch->getScore();
        } else {
            $score = 0;
        }

        return $score;
    }

    public function connect(Application $app) {
        $this->app = $app;
        // créer un nouveau controller basé sur la route par défaut
        $bet = $app['controllers_factory'];
        $bet->match("/bet/{idMatchTeam}", 'App\Controller\BetController::betForm')->bind("bet.betForm");
        $bet->post('/dobet', array($this,"doBet"))->bind('bet.doBet');

        return $bet;
    }

}
