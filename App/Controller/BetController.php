<?php
namespace App\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use App\Model\Entity\Betmatchs;


class BetController implements ControllerProviderInterface {

	private $app;
	private $lid;

    /**
     * Form to bet on a match
     * @param \Silex\Application $app
     * @return type
     */
    public function betForm(Application $app, $idMatchTeam) {
        //var_dump($idMatchTeam);
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

        foreach($matchTeamList as $matchTeam) {
            if (strtotime("-15 minutes",$matchTeam->getIdmatchs()->getDate()->getTimestamp()) > time()) {
                $matchTeamIdList[] = $matchTeam->getIdmatchteam();
            }
        }

        $betForm = $this->app['form.factory']->create(new \App\Form\BetType($matchTeamIdList));
        $betForm->bind($this->app['request']);
        if ($betForm->isValid()){
            $datas = $betForm->getData();

            foreach($matchTeamList as $matchTeam) {
                $userRepository = $this->app['em']->getRepository('App\Model\Entity\Players');
                $user = $userRepository->getUserByUsername($this->app['security']->getToken()->getUser()->getUsername());

                $betMatch = $betMatchRepository->find(array("idmatchteam" => $matchTeam, "idplayers" => $user ));
                if (in_array($matchTeam->getIdmatchteam(), $matchTeamIdList)) {
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

            //add flash success
            $this->app['session']->getFlashBag()->add('success', $this->app['translator']->trans('save succeed'));
        } else {
            $this->app['session']->getFlashBag()->add('error', $this->app['translator']->trans('save error'));
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

    public function getMatchPlayerScore($player, $idMatch) {
        $userRepository = $this->app['em']->getRepository('App\Model\Entity\Players');
        $matchRepository = $this->app['em']->getRepository('App\Model\Entity\Matchs');
        $betScoreRepository = $this->app['em']->getRepository('App\Model\Entity\Betscore');

        $user = $userRepository->getUserByUsername($player);
        $match = $matchRepository->find(array("idmatchs" => $idMatch));

        $betScore = $betScoreRepository->find(array("idmatchs" => $match, "idplayers" => $user));
        if ($betScore) {
            $score = $betScore->getScore();
        } else {
            $score = "";
        }

        return $score;
    }

    public function getPlayerTotalScore($player) {
        $betScoreRepository = $this->app['em']->getRepository('App\Model\Entity\Betscore');
        $score = $betScoreRepository->findSum($player,$this->lid);

        if($score[1] !== null) {
            $score = $score[1];
        } else {
            $score = 0;
        }

        return $score;
    }

    public function getCurrentPlayerTotalScore() {
        $betScoreRepository = $this->app['em']->getRepository('App\Model\Entity\Betscore');
        //We get logged user
        $userRepository =$this->app['em']->getRepository('App\Model\Entity\Players');
        $user = $userRepository->getUserByUsername($this->app['security']->getToken()->getUser()->getUsername());
        $score = $betScoreRepository->findSum($user,$this->lid);

        if($score[1] !== null) {
            $score = $score[1];
        } else {
            $score = 0;
        }

        return $score;
    }

    public function getBestPlayerTotalScore() {
        $betScoreRepository = $this->app['em']->getRepository('App\Model\Entity\Betscore');
        $betScorePlayers = $betScoreRepository->findBestScores($this->lid);

        $players = array();
        foreach($betScorePlayers as $k => $v) {
            $player = $v[0]->getIdplayers();
            $player->setScore($v["score"]);
            $player->setRightpronostics(count($betScoreRepository->findRightPronostics($player,$this->lid)));
            $player->setWrongpronostics(count($betScoreRepository->findWrongPronostics($player,$this->lid)));

            $players[] = $player;
        }

        return $this->app['twig']->render('betscore/bestPlayers.twig', array("players" => $players));
    }

    /**
     * Find Current player ranking
     * @return int
     */
    public function findRanking() {

        $betScoreRepository = $this->app['em']->getRepository('App\Model\Entity\Betscore');

        $userRepository =$this->app['em']->getRepository('App\Model\Entity\Players');
        $currentUser = $userRepository->getUserByUsername($this->app['security']->getToken()->getUser()->getUsername());

        $betScorePlayers = $betScoreRepository->findScores($this->lid);

        $rank = 1;
        $totalUsers = count($userRepository->getUserByLeague($this->lid));
        foreach($betScorePlayers as $k => $v) {
            $player = $v[0]->getIdplayers();
            if ($player == $currentUser) {
                return $rank .'e/' . $totalUsers;
            }
            $rank++;
        }
        return $totalUsers.'/' .$totalUsers;
    }

    public function connect(Application $app) {
        $this->app = $app;
        $this->lid = $app['session']->get('idleague');
        // créer un nouveau controller basé sur la route par défaut
        $bet = $app['controllers_factory'];
        $bet->match("/bet/{idMatchTeam}", 'App\Controller\BetController::betForm')->bind("bet.betForm");
        $bet->post('/dobet', array($this,"doBet"))->bind('bet.doBet');
        $bet->get('/getScore/best', array($this,"getBestPlayerTotalScore"))->bind('bet.best');
        $bet->get('/getScore/{player}/{idMatch}', array($this,"getMatchPlayerScore"))->bind('bet.getPlayerScore');
        $bet->get('/getPlayerScore/{player}', array($this,"getPlayerTotalScore"))->bind('bet.getPlayerTotalScore');
        $bet->get('/getCurrentPlayerScore', array($this,"getCurrentPlayerTotalScore"))->bind('bet.getCurrentPlayerTotalScore');
        $bet->get('/getCurrentPlayerRanking', array($this,"findRanking"))->bind('bet.getCurrentPlayerRanking');
        return $bet;
    }

}
