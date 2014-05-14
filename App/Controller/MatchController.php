<?php
namespace App\Controller;

use App\Model\Repository\BetMatchRepository;

use Silex\Application;
use Silex\ControllerProviderInterface;
use App\Model\Entity\Betscore;
use App\Model\Entity\Matchs;
use App\Model\Entity\Betmatchs;
use App\Model\Entity\Players;

class MatchController implements ControllerProviderInterface {


	protected function calculPoint($idMatch) {
		$matchTeamRepository = $this->app['em']->getRepository('App\Model\Entity\Matchteam');
		$betScoreRepository = $this->app['em']->getRepository('App\Model\Entity\Betscore');
		$betMatchRepository = $this->app['em']->getRepository('App\Model\Entity\Betmatchs');
		$playersRepository = $this->app['em']->getRepository('App\Model\Entity\Players');

		$playerIdList = $betMatchRepository->findAllPlayersId();
		$matchTeamList = $matchTeamRepository->find(null, 0, array("idmatchs" => $idMatch->getIdmatchs()));

		//Get idTeam, Score and Pen for both teams
		foreach($matchTeamList as $matchTeam) {
			$scoreList[] = $matchTeam->getScore();
			$penList[] = $matchTeam->getPen();
			$idmatchteamList[] = $matchTeam->getIdmatchteam();
			foreach ($matchTeam->getBet() as $bet) {
				$betscoreList[] = $bet->getScore();
				$betplayerList[] = $bet->getIdplayers();
			}
		}
		
		if ( ($scoreList[O] != null ) && ( $scoreList[1] != null ) ) {

			foreach ($playerIdList as $player) { //List array for each players who bets
				$i = 0;
				foreach ($betplayerList as $key => $betplayer) { //List array of betMatch to find players
					if ($player['idplayers'] === $betplayer->getIdplayers()) { // Try to match players and betters
						$betscore[$i] = $betscoreList[$key];
						$i++;
					}
				}
				//Calculate probability
				//TODO get team ranking
				
				//If match bet = match result, then calulate odds, else, odds = 0
				if ( ($scoreList[0] > $scoreList[1] && $betscore[0] > $betscore[1]) ) {
					$odds = 3;
				}
				elseif ( ($scoreList[0] == $scoreList[1] && $betscore[0] == $betscore[1]) ) {
					$odds = 7;
				}
				elseif ( ($scoreList[0] < $scoreList[1] && $betscore[0] < $betscore[1]) ) {
					$odds = 5;
				}
				else {
					$odds = 0;
				}
				//Verify if bet score equal real score
				if ($scoreList[0] == $betscore1 && $scoreList[1] == $betscore2) {
					$coef = 2;
				}
				elseif ($scoreList[0] == $betscore1 || $scoreList[1] == $betscore2) {
					$coef = 1.5;
				}
				else $coef = 1;
				
				$points = 10*$coef*$odds;
				/*$var = $playersRepository->getUserById($player);
				 echo gettype($var);
				if (is_object($var)) {
				echo get_class($var);
				}*/
						
				$score = new Betscore();
				$score->setScore($points);
				$score->setIdmatchs($idMatch);
				$score->setIdplayers($playersRepository->getUserById($player));
				$betScoreRepository->save($score);
			}
		
		}
		

	}
	
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
     * Form to update a match
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
            	$idmatch = $matchTeam->getIdmatchs();
                if ( $datas["score".$matchTeam->getIdmatchteam()] !== "-"  ) {
                    $matchTeam->setScore($datas["score".$matchTeam->getIdmatchteam()]);       
                }
                if ( $datas["pen".$matchTeam->getIdmatchteam()] !== "-" ) {
                    $matchTeam->setPen($datas["pen".$matchTeam->getIdmatchteam()]);
                }
                $matchTeamRepository->update($matchTeam);
                ( $idmatch == $idmatchprec ) ? $this->calculPoint($idmatch) : $idmatchprec = $idmatch ;
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
