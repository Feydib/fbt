<?php
namespace App\Controller;

use App\Model\Repository\BetMatchRepository;

use Silex\Application;
use Silex\ControllerProviderInterface;
use App\Model\Entity\Betscore;
use App\Model\Entity\Matchteam;
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

	foreach ($playerIdList as $player) { //List array for each players who bets
            $i = $points = $coef = $odds = 0;
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
            if ($scoreList[0] == $betscore[0] && $scoreList[1] == $betscore[1]) {
                $coef = 2;
            }
            elseif ($scoreList[0] == $betscore[0] || $scoreList[1] == $betscore[1]) {
                $coef = 1.5;
            }
            else $coef = 1;

            $points = 10*$coef*$odds;

            $score = new Betscore();
            $score->setScore($points);
            $score->setIdmatchs($idMatch);
            $score->setIdplayers($playersRepository->getUserById($player));
            $betScoreRepository->save($score);
        }
    }

    /**
     * get team which win match
     * @param \App\Model\Entity\Matchs $idMatch
     * @return Team
     */
    private function getMatchWinner(Matchs $idMatch) {
        $matchTeamRepository = $this->app['em']->getRepository('App\Model\Entity\Matchteam');
        $teamRepository = $this->app['em']->getRepository('App\Model\Entity\Teams');
        $matchTeamList = $matchTeamRepository->find(null, 0, array("idmatchs" => $idMatch->getIdmatchs()));

        $matchTeam1 = $matchTeamList[0];
        $team1 = $matchTeam1->getIdteams();
        $matchTeam2 = $matchTeamList[1];
        $team2 = $matchTeam2->getIdteams();
        
        if ($matchTeam1->getScore() > $matchTeam2->getScore()) {
            return $matchTeam1->getIdteams();
        } else if ($matchTeam1->getScore() < $matchTeam2->getScore()) {
            return $matchTeam2->getIdteams();
        } else {
            if ($matchTeam1->getPen() > $matchTeam2->getPen()) {
                return $matchTeam1->getIdteams();
            } else {
                return $matchTeam2->getIdteams();
            }
        }
    }
    
    /**
     * Method to update pool ranking
     * @param type $pool
     */
    private function updatePoolRanking($pool) {
        $teamRepository = $this->app['em']->getRepository('App\Model\Entity\Teams');
        $matchRepository = $this->app['em']->getRepository('App\Model\Entity\Matchs');
        $matchteamRepository = $this->app['em']->getRepository('App\Model\Entity\Matchteam');
        $arrayTeams = $teamRepository->findTeams(array("pool" => $pool), null, 0, array("pts" => "DESC", "gav" => "DESC", "gf" => "DESC", "ga" => "ASC"));
        
        //var_dump($arrayTeams);
        $ranking = 1;
        $teamEqual = null;
        foreach($arrayTeams as $team) {
            $equal = false;
            foreach($arrayTeams as $teamToCompare) {
                // if team is Equal with prévious, his ranking is already updated
                if($team !== $teamToCompare && $team !== $teamEqual) {
                    //if teams' results are totally equal
                    if($team->getPts() == $teamToCompare->getPts() 
                            && $team->getGav() == $teamToCompare->getGav()
                            && $team->getGf() == $teamToCompare->getGf()
                            && $team->getGa() == $teamToCompare->getGa()) {
                        $equal = true;
                        $teamEqual = $teamToCompare;
                    } 
                }
            } 
            if ($team !== $teamEqual) {
                if (!$equal) {
                    $team->setRanking($ranking++);
                } else {
                    
                    //when we have to compare match result in 2 teams
                    $matchTeamList = $matchteamRepository->find(null, 0, array("idteams" => $team));
                    foreach($matchTeamList as $matchteam){
                        $match = $matchteam->getIdmatchs();
                        $listMatchTeams = $match->getTeams();
                        $matchToCompare = $matchRepository->find(array("idmatchs" => $match->getIdmatchs()));
                        $matchTeam1 = $matchteamRepository->findOne(array("idteams" => $team, "idmatchs" => $matchToCompare));
                        $matchTeam2 = $matchteamRepository->findOne(array("idteams" => $teamEqual, "idmatchs" => $matchToCompare));
                        if ($matchTeam2 !== null) {
                            
                            $matchTeamArray = $matchteamRepository->find(null, 0, array("idmatchs" => $match));
                            if ($matchTeamArray[0]->getScore() > $matchTeamArray[1]->getScore()) {
                                //we update equals team ranking
                                if($matchTeamArray[0] == $matchTeam1 ) {
                                    $team->setRanking($ranking++);
                                    $teamEqual->setRanking($ranking++);
                                    $teamRepository->update($teamEqual);
                                } else {
                                    $teamEqual->setRanking($ranking++);
                                    $teamRepository->update($teamEqual);
                                    $team->setRanking($ranking++);
                                }
                            } else if ($matchTeamArray[0]->getScore() < $matchTeamArray[1]->getScore()) {
                                if($matchTeamArray[0] == $matchTeam1 ) {
                                    $teamEqual->setRanking($ranking++);
                                    $teamRepository->update($teamEqual);
                                    $team->setRanking($ranking++);
                                } else {
                                    $team->setRanking($ranking++);
                                    $teamEqual->setRanking($ranking++);
                                    $teamRepository->update($teamEqual);
                                }
                            } else {
                                //when totally equal
                                $team->setRanking($ranking++);
                                $teamEqual->setRanking($ranking++);
                                $teamRepository->update($teamEqual);
                            }
                        }
                    }
                }
            }
            $teamRepository->update($team);
        }
    }

    /**
     * Update teams after set score
     * @param \App\Model\Entity\Matchs $idMatch
     */    
    private function updateTeams(Matchs $idMatch) {
        $matchTeamRepository = $this->app['em']->getRepository('App\Model\Entity\Matchteam');
        $teamRepository = $this->app['em']->getRepository('App\Model\Entity\Teams');
        $matchTeamList = $matchTeamRepository->find(null, 0, array("idmatchs" => $idMatch->getIdmatchs()));

        $matchTeam1 = $matchTeamList[0];
        $team1 = $matchTeam1->getIdteams();
        $matchTeam2 = $matchTeamList[1];
        $team2 = $matchTeam2->getIdteams();
	
        //We set points and goalaverage 
        if ($matchTeam1->getScore() > $matchTeam2->getScore()) {
            $team1->setPts($team1->getPts() + 3);
            $team1->setGf($matchTeam1->getScore() + $team1->getGf());
            $team1->setGa($matchTeam2->getScore() + $team1->getGa());
            $team1->setGav($team1->getGav() + ($matchTeam1->getScore() - $matchTeam2->getScore()));
            $team1->setPlayed($team1->getPlayed() + 1);
            $team1->setWin($team1->getWin() + 1);
            
            $team2->setPts($team2->getPts() + 0);
            $team2->setGf($matchTeam2->getScore() + $team2->getGf());
            $team2->setGa($matchTeam1->getScore() + $team2->getGa());
            $team2->setGav($team2->getGav() + ($matchTeam2->getScore() - $matchTeam1->getScore()));
            $team2->setPlayed($team2->getPlayed() + 1);
            $team2->setLost($team2->getLost() + 1);
        } else if ($matchTeam1->getScore() < $matchTeam2->getScore()) {
            $team1->setPts($team1->getPts() + 0);
            $team1->setGf($matchTeam1->getScore() + $team1->getGf());
            $team1->setGa($matchTeam2->getScore() + $team1->getGa());
            $team1->setGav($team1->getGav() + ($matchTeam1->getScore() - $matchTeam2->getScore()));
            $team1->setPlayed($team1->getPlayed() + 1);
            $team1->setLost($team1->getLost() + 1);
            
            $team2->setPts($team2->getPts() + 3);
            $team2->setGf($matchTeam2->getScore() + $team2->getGf());
            $team2->setGa($matchTeam1->getScore() + $team2->getGa());
            $team2->setGav($team2->getGav() + ($matchTeam2->getScore() - $matchTeam1->getScore()));
            $team2->setPlayed($team2->getPlayed() + 1);
            $team2->setWin($team2->getWin() + 1);
        } else {
            $team1->setPts($team1->getPts() + 1);
            $team1->setGf($matchTeam1->getScore() + $team1->getGf());
            $team1->setGa($matchTeam2->getScore() + $team1->getGa());
            $team1->setPlayed($team1->getPlayed() + 1);
            $team1->setDraw($team1->getDraw() + 1);
            
            $team2->setPts($team2->getPts() + 1);
            $team2->setGf($matchTeam2->getScore() + $team2->getGf());
            $team2->setGa($matchTeam1->getScore() + $team2->getGa());
            $team2->setPlayed($team2->getPlayed() + 1);
            $team2->setDraw($team2->getDraw() + 1);
        }
        //we update teams
        $teamRepository->update($team1);
        $teamRepository->update($team2);
        
        $this->updatePoolRanking($team1->getPool());
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
       $matchList = $matchRepository->find(array(), null, 0 , array('date' => 'ASC')); 

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
            $idmatchprec = null;
            $savedMatchs = array();
            foreach($matchTeamList as $matchTeam) {
            	$idmatch = $matchTeam->getIdmatchs();
            	$score = false;
                if ( $datas["score".$matchTeam->getIdmatchteam()] !== "-" && $datas["score".$matchTeam->getIdmatchteam()] !== null) {
                    $matchTeam->setScore($datas["score".$matchTeam->getIdmatchteam()]); 
                    $score = true;
                }
                if ( $datas["pen".$matchTeam->getIdmatchteam()] !== "-" && $datas["pen".$matchTeam->getIdmatchteam()] !== null) {
                    $matchTeam->setPen($datas["pen".$matchTeam->getIdmatchteam()]);
                }
                if ($score) {
                    
                    $matchTeamRepository->update($matchTeam);
                    //if ($idmatch == $idmatchprec) {
                    if (in_array($idmatch, $savedMatchs)) {
                        $this->updateTeams($idmatch);
                        $this->completeNextMatchs($matchTeam);
                        $this->calculPoint($idmatch);
                    } else {
                        $savedMatchs[] = $idmatch;
                        $idmatchprec = $idmatch ;
                    }
                     
                }
                
                
            }
        }
        return $this->app->redirect($this->app["url_generator"]->generate("match.matchToScore"));
    }
    
    /**
     * Method to create match which depond on other matchs like final
     * @param $matchTeam matchteam played
     */
    private function completeNextMatchs(Matchteam $matchTeam) {
        $matchPrerequisiteRepository = $this->app['em']->getRepository('App\Model\Entity\MatchPrerequisite');
        $teamRepository = $this->app['em']->getRepository('App\Model\Entity\Teams');
        $matchTeamRepository = $this->app['em']->getRepository('App\Model\Entity\Matchteam');
        
        //if pool is completed we create following match with pool teams
        if($matchTeam->getIdmatchs()->getIdmatchs() < 49 && $this->isPoolCompleted($matchTeam->getIdteams()->getPool())) {            
            //We get pool winner and second for final tour
            $matchsWinner = $matchPrerequisiteRepository->find(array('idpoolwinner' => $matchTeam->getIdteams()->getPool()));
            $matchsSecond = $matchPrerequisiteRepository->find(array('idpoolsecond' => $matchTeam->getIdteams()->getPool()));
            
            $teamList = $teamRepository->findTeams(array('pool' => $matchTeam->getIdteams()->getPool()), null, 0, array("ranking" => "DESC"));

            //create new matchs
            $matchTeam1 = new Matchteam();
            $matchTeam1->setIdmatchs($matchsWinner->getIdmatchs());
            $matchTeam1->setIdteams($teamList[0]);
            $matchTeamRepository->save($matchTeam1);
            
            $matchTeam2 = new Matchteam();
            $matchTeam2->setIdmatchs($matchsSecond->getIdmatchs());
            $matchTeam2->setIdteams($teamList[1]);
            
            $matchTeamRepository->save($matchTeam2);
            
        } else if ($matchTeam->getIdmatchs()->getIdmatchs() >= 49){
            $finalMatch = $matchPrerequisiteRepository->find(array('idmatchs2' => $matchTeam->getIdmatchs()));
            if(!isset($finalMatch) || $finalMatch === null) {
                $finalMatch = $matchPrerequisiteRepository->find(array('idmatchs1' => $matchTeam->getIdmatchs()));
            }
            
            //create new matchs
            $matchTeam3 = new Matchteam();
            $matchTeam3->setIdmatchs($finalMatch->getIdmatchs());
            $matchTeam3->setIdteams($this->getMatchWinner($matchTeam->getIdmatchs()));
            $matchTeamRepository->save($matchTeam3);
        }
    }
    
    /**
     * We veridy that the pool is completed
     * @param \App\Controller\Integer $pool
     * @return boolean
     */
    private function isPoolCompleted($pool) {
        $teamRepository = $this->app['em']->getRepository('App\Model\Entity\Teams');
        $arrayTeams = $teamRepository->findTeamsPoolUnfinished($pool);

        if(count($arrayTeams) == 0) {
            return true;
        }
        return false;
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
        
        //$match->get("/admin/match/test/{pool}", array($this,"updatePoolRanking") )->bind("match.updatePoolRanking");
        return $match;
    }
    
    

}
