<?php
namespace App\Controller;

use Doctrine\DBAL\Schema\View;

use Silex\Application;
use Silex\ControllerProviderInterface;
use App\Model\Entity\Tournament;
use App\Model\Entity\Tournplayers;
use App\Model\Entity\Teams;

class TournamentController implements ControllerProviderInterface {
    
    private $app;
    
    public function myTournaments() {
        $tournamentRepository = $this->app['em']->getRepository('App\Model\Entity\Tournament');
        
        //We get logged user
        $userRepository =$this->app['em']->getRepository('App\Model\Entity\Players');
        $user = $userRepository->getUserByUsername($this->app['security']->getToken()->getUser()->getUsername());
        //We search user's tournaments
        $myTournaments = $tournamentRepository->findMyTournaments($user);
        
        return $this->app["twig"]->render("tournament/myTournaments.twig", array("myTournaments" => $myTournaments));
    }
    
    public function findTournamentRanking($idTournament) {
        $tournamentRepository = $this->app['em']->getRepository('App\Model\Entity\Tournament');
        $betScoreRepository = $this->app['em']->getRepository('App\Model\Entity\Betscore');
        $tournPlayersRepository = $this->app['em']->getRepository('App\Model\Entity\Tournplayers');
        
        $userRepository =$this->app['em']->getRepository('App\Model\Entity\Players');
        $currentUser = $userRepository->getUserByUsername($this->app['security']->getToken()->getUser()->getUsername());
        
        $tournament = $tournamentRepository->find($idTournament);
        $betScorePlayers = $betScoreRepository->findTournamentScores($tournament);
        
        $tournPlayers = $tournPlayersRepository->findBy(array("idtournament" => $tournament, "isaccepted" => true));
        
        $rank = 1;
        foreach($betScorePlayers as $k => $v) {
            $player = $v[0]->getIdplayers();
            if ($player == $currentUser) {
                return $rank . 'e/' . count($tournPlayers);
            }
            $rank++;
        }
        return count($tournPlayers) . 'e/' . count($tournPlayers);
    }

    public function index(Application $app) {
       //We list all tournament
       $tournamentRepository = $app['em']->getRepository('App\Model\Entity\Tournament');
       $tournamentList = $tournamentRepository->findAllTournaments();
       //We get logged user
       $userRepository =$app['em']->getRepository('App\Model\Entity\Players');
       $user = $userRepository->getUserByUsername($app['security']->getToken()->getUser()->getUsername());
       //We search user's tournaments
       $myTournaments = $tournamentRepository->findMyTournaments($user);
       
       //We build tournament list with tournaments which are not already joined
       $tournamentNotJoined = array();
       foreach($tournamentList as $tournament) {
           if (!in_array($tournament, $myTournaments)) {
               $tournamentNotJoined[] = $tournament;
           }
       }

       return $app["twig"]->render("tournament/index.twig", array('tournaments' => $tournamentNotJoined, "myTournaments" => $myTournaments));
    }
    
    
    
    /**
     * Create a form to add a tournament
     */
    public function add() {
        $addTournamentForm = $this->app['form.factory']->create(new \App\Form\TournamentType());
        return $this->app['twig']->render('tournament/add.twig', array("form" => $addTournamentForm->createView()));
    }
    
    /**
     * Adding a new tournament
     */
    public function doAdd() {
        $addTournamentForm = $this->app['form.factory']->create(new \App\Form\TournamentType());
        $addTournamentForm->bind($this->app['request']);
        $tournamentRepository = $this->app['em']->getRepository('App\Model\Entity\Tournament');
        
        if ($addTournamentForm->isValid()){
            $datas = $addTournamentForm->getData();
            
            $tournament = new Tournament();
            $tournament->setName($datas['name']);
            $tournament->setYear(new \DateTime);
                        
            $tournamentRepository->save($tournament);
            
            $tournPlayersRepository = $this->app['em']->getRepository('App\Model\Entity\Tournplayers');
            $tournPlayer = new Tournplayers();
            $tournPlayer->setIdtournament($tournament);    

            $userRepository = $this->app['em']->getRepository('App\Model\Entity\Players');
            $user = $userRepository->getUserByUsername($this->app['security']->getToken()->getUser()->getUsername());

            $tournPlayer->setIdPlayers($user);
            $tournPlayer->setIsAdmin(true);
            $tournPlayer->setIsaccepted(true);
            
            $tournPlayersRepository->save($tournPlayer);
                    
            $this->app['session']->getFlashBag()->add('success', $this->app['translator']->trans('save done'));
        } else {
            $this->app['session']->getFlashBag()->add('error', $this->app['translator']->trans('The form contains errors'));
        }
        return $this->app->redirect($this->app["url_generator"]->generate("tournament.index"));
    }
    
    /**
     * Delete a tournament
     * @param int $idTournament
     */
    public function delete($idTournament) {
        $userRepository = $this->app['em']->getRepository('App\Model\Entity\Players');
        $tournamentRepository = $this->app['em']->getRepository('App\Model\Entity\Tournament');
        $user = $userRepository->getUserByUsername($this->app['security']->getToken()->getUser()->getUsername());
        $tounament = $tournamentRepository->find($idTournament);
        
        //We find tournament to delete
        $tournPlayersRepository = $this->app['em']->getRepository('App\Model\Entity\Tournplayers');
        $tournPlayer = $tournPlayersRepository->findTournPlayers($user, $tounament);
        
        //User can delete a tournament if he is admin of this tournament
        if($tournPlayer && $tournPlayer->getIsadmin()) {
            $tournamentRepository->delete($tounament);
        }
        
        return $this->app->redirect($this->app["url_generator"]->generate("tournament.index"));
        
    }
    
    /**
     * Join a tournament
     * @param int $idTournament
     */
    public function join($idTournament) {
        $tournamentRepository = $this->app['em']->getRepository('App\Model\Entity\Tournament');
        
        $tourn = $tournamentRepository->find($idTournament);
        $tournname = $tourn->getName();
        
        $tournPlayersRepository = $this->app['em']->getRepository('App\Model\Entity\Tournplayers');
        $tournPlayer = new Tournplayers();
        $tournPlayer->setIdtournament($tournamentRepository->find($idTournament));    

        $userRepository = $this->app['em']->getRepository('App\Model\Entity\Players');
        $user = $userRepository->getUserByUsername($this->app['security']->getToken()->getUser()->getUsername());

        $tournPlayer->setIdPlayers($user);
        $tournPlayer->setIsAdmin(false);
        $tournPlayer->setIsaccepted(false);
        $tournPlayersRepository->save($tournPlayer);
        
        //$admins = $tournPlayersRepository->findAdminsMail($idTournament);
        $admins = $tournPlayersRepository->findTournAdmins($idTournament);
       
        foreach ($admins as $admin) {
        	$mails[] = $admin->getIdplayers()->getMail();
        }
        
        $body = "Bonjour,<br/><br/>"
        . "Vous avez une nouvelle demande de ".$user->getFirstname()." ".$user->getLastname()." pour rejoindre la compétition '".$tournname."'.<br/>"
        . "Ce mail est envoyé automatiquement, merci de ne pas y répondre.<br/><br/>";
        
        $message = \Swift_Message::newInstance()
        ->setSubject('Demande d\'ajout à votre compétition')
        ->setFrom(array('noreply@brebion.info' => "FBT - Admin"))
        //->setTo(array($datas['email']))
        ->setTo($mails)
        ->setBody($body, 'text/html');
        
        $this->app['mailer']->send($message);
        
        return $this->app->redirect($this->app["url_generator"]->generate("tournament.index"));
    }
    
    /**
     * Leave a tournament
     * @param int $idTournament
     */
    public function leave($idTournament) {
        $tournamentRepository = $this->app['em']->getRepository('App\Model\Entity\Tournament');
        $tournPlayersRepository = $this->app['em']->getRepository('App\Model\Entity\Tournplayers');
        $userRepository = $this->app['em']->getRepository('App\Model\Entity\Players');
        
        //We get current user
        $user = $userRepository->getUserByUsername($this->app['security']->getToken()->getUser()->getUsername());
        //We find tournament to leave
        $tournPlayer = $tournPlayersRepository->findTournPlayers($user, $tournamentRepository->find($idTournament));
        //We remove entry for currect user in tournament
        $tournPlayersRepository->remove($tournPlayer);
        
        return $this->app->redirect($this->app["url_generator"]->generate("tournament.index"));
    }
    
    /**
     * View a tournament
     * @param int $idTournament
     */
    public function view($idTournament) {
    	
        $tournamentRepository = $this->app['em']->getRepository('App\Model\Entity\Tournament');
        $tounament = $tournamentRepository->find($idTournament);
        $tournPlayersRepository = $this->app['em']->getRepository('App\Model\Entity\Tournplayers');
        
    	$players = $this->getTournamentPlayersAndScore($tounament);
        
        //We get current user and we check he's admin of tournament to accept
        $userRepository = $this->app['em']->getRepository('App\Model\Entity\Players');
        $user = $userRepository->getUserByUsername($this->app['security']->getToken()->getUser()->getUsername());
        $currentToPlayer = $tournPlayersRepository->findTournPlayers($user, $tounament);

        //We verify that current user is group member and accepted in group and we check if user is admin
        if($currentToPlayer && $currentToPlayer->getIsadmin()) {
            $isadmin = true;
        } else if (!$currentToPlayer || !$currentToPlayer->getIsaccepted()) {
            return $this->app->redirect($this->app["url_generator"]->generate("tournament.index"));
        } else {
            $isadmin = false;
        }

        return $this->app["twig"]->render("tournament/view.html.twig", array('tournamentplayers' => $players, 'tournament' => $tounament, 'currentUserAdmin' => $isadmin));
    }
    
    /**
     * return players and their total score
     * @param \App\Model\Entity\Tournament $tournament
     * @return array of players
     */
    private function getTournamentPlayersAndScore(Tournament $tournament) {
        $betScoreRepository = $this->app['em']->getRepository('App\Model\Entity\Betscore');
        $tournPlayersRepository = $this->app['em']->getRepository('App\Model\Entity\Tournplayers');
        $listBetScore = $betScoreRepository->findTournamentScores($tournament);
        
        $players = array();
        foreach($listBetScore as $k => $v) {
            $player = $v[0]->getIdplayers();
            $player->setScore($v["score"]);
            $player->setRightpronostics(count($betScoreRepository->findRightPronostics($player)));
            $player->setWrongpronostics(count($betScoreRepository->findWrongPronostics($player)));
            
            $players[] = $player;
        }
        
        $tournPlayers = $tournPlayersRepository->findBy(array("idtournament" => $tournament));
        //If a tounrament's user is not is list because his score is 0, we add it
        foreach($tournPlayers as $tPlayers) {
            if (!in_array($tPlayers->getIdplayers(), $players)) {
                $player = $tPlayers->getIdplayers();
                $player->setScore(0);
                $player->setRightpronostics(0);
                $player->setWrongpronostics(0);
                $players[] = $player;
            }
        }
        
        usort($players, array("App\Controller\TournamentController","cmp"));
        
        return $players;
    }
    
    function cmp($a, $b) {
        if ($a->getScore() == $b->getScore()) {
            return 0;
        }
        return ($a->getScore() < $b->getScore()) ? 1 : -1;
    }

    
    /**
     * accept a player in a tournament
     * @param int $idTournPlayers
     */
    public function accept($idTournPlayers) {
        $tournPlayersRepository = $this->app['em']->getRepository('App\Model\Entity\Tournplayers');
        //We find TournPlayers to accept
        $tournPlayer = $tournPlayersRepository->findTournPlayersById($idTournPlayers);
        //We get current user and we check he's admin of tournament to accept
        $userRepository = $this->app['em']->getRepository('App\Model\Entity\Players');
        $user = $userRepository->getUserByUsername($this->app['security']->getToken()->getUser()->getUsername());
        $currentToPlayer = $tournPlayersRepository->findTournPlayers($user, $tournPlayer->getIdtournament());
        if($currentToPlayer && $currentToPlayer->getIsadmin()) {
            $tournPlayer->setIsaccepted(true); 
            $tournPlayersRepository->save($tournPlayer);
        }
        return $this->app->redirect($this->app["url_generator"]->generate("tournament.view", array('idTournament' => $tournPlayer->getIdtournament()->getIdtournament())));
    }
    
    /**
     * refuse a player in a tournament
     * @param int $idTournPlayers
     */
    public function refuse($idTournPlayers) {
        $tournPlayersRepository = $this->app['em']->getRepository('App\Model\Entity\Tournplayers');
        //We find TournPlayers to accept
        $tournPlayer = $tournPlayersRepository->findTournPlayersById($idTournPlayers);
        //We get current user and we check he's admin of tournament to accept
        $userRepository = $this->app['em']->getRepository('App\Model\Entity\Players');
        $user = $userRepository->getUserByUsername($this->app['security']->getToken()->getUser()->getUsername());
        $currentToPlayer = $tournPlayersRepository->findTournPlayers($user, $tournPlayer->getIdtournament());
        if($currentToPlayer && $currentToPlayer->getIsadmin()) {
            $tournPlayersRepository->remove($tournPlayer);
        }
        return $this->app->redirect($this->app["url_generator"]->generate("tournament.view", array('idTournament' => $tournPlayer->getIdtournament()->getIdtournament())));
    }
    
    /**
     * remove a player from a tournament
     * @param int $idTournPlayers
     */
    public function remove($idTournPlayers) {
        $tournPlayersRepository = $this->app['em']->getRepository('App\Model\Entity\Tournplayers');
        //We find TournPlayers to accept
        $tournPlayer = $tournPlayersRepository->findTournPlayersById($idTournPlayers);
        //We get current user and we check he's admin of tournament to accept
        $userRepository = $this->app['em']->getRepository('App\Model\Entity\Players');
        $user = $userRepository->getUserByUsername($this->app['security']->getToken()->getUser()->getUsername());
        $currentToPlayer = $tournPlayersRepository->findTournPlayers($user, $tournPlayer->getIdtournament());
        if($currentToPlayer && $currentToPlayer->getIsadmin()) {
            $tournPlayer->setIsaccepted(false); 
            $tournPlayersRepository->save($tournPlayer);
        }
        return $this->app->redirect($this->app["url_generator"]->generate("tournament.view", array('idTournament' => $tournPlayer->getIdtournament()->getIdtournament())));
    }
    
        
    /**
     * set admin right on a tournament
     * @param int $idTournPlayers
     */
    public function setAdmin($idTournPlayers) {
        $tournPlayersRepository = $this->app['em']->getRepository('App\Model\Entity\Tournplayers');
        //We find TournPlayers to accept
        $tournPlayer = $tournPlayersRepository->findTournPlayersById($idTournPlayers);
        //We get current user and we check he's admin of tournament to accept
        $userRepository = $this->app['em']->getRepository('App\Model\Entity\Players');
        $user = $userRepository->getUserByUsername($this->app['security']->getToken()->getUser()->getUsername());
        $currentToPlayer = $tournPlayersRepository->findTournPlayers($user, $tournPlayer->getIdtournament());
        if($currentToPlayer && $currentToPlayer->getIsadmin()) {
            $tournPlayer->setIsadmin(true); 
            $tournPlayersRepository->save($tournPlayer);
        }
        return $this->app->redirect($this->app["url_generator"]->generate("tournament.view", array('idTournament' => $tournPlayer->getIdtournament()->getIdtournament())));
    }

    /**
     * remove admin right on a tournament
     * @param int $idTournPlayers
     */
    public function removeAdmin($idTournPlayers) {
        $tournPlayersRepository = $this->app['em']->getRepository('App\Model\Entity\Tournplayers');
        //We find TournPlayers to accept
        $tournPlayer = $tournPlayersRepository->findTournPlayersById($idTournPlayers);
        //We get current user and we check he's admin of tournament to accept
        $userRepository = $this->app['em']->getRepository('App\Model\Entity\Players');
        $user = $userRepository->getUserByUsername($this->app['security']->getToken()->getUser()->getUsername());
        $currentToPlayer = $tournPlayersRepository->findTournPlayers($user, $tournPlayer->getIdtournament());
        if($currentToPlayer && $currentToPlayer->getIsadmin()) {
            $tournPlayer->setIsadmin(false); 
            $tournPlayersRepository->save($tournPlayer);
        }
        return $this->app->redirect($this->app["url_generator"]->generate("tournament.view", array('idTournament' => $tournPlayer->getIdtournament()->getIdtournament())));
    }
    
    /**
     * Invite new players
     * @param int $idTournPlayers
     */
    public function invite($idTournament) {
        $inviteForm = $this->app['form.factory']->create(new \App\Form\MailType($idTournament));
        return $this->app['twig']->render('form/mail.twig', array("form" => $inviteForm->createView()));
    }
    
    /**
     * Send a mail to invite new players
     * @param int $idTournPlayers
     */
    public function doInvite() {
        $inviteForm = $this->app['form.factory']->create(new \App\Form\MailType());
        $inviteForm->bind($this->app['request']);
        if ($inviteForm->isValid()){
            $datas = $inviteForm->getData();
            
            //We get current user
            $userRepository = $this->app['em']->getRepository('App\Model\Entity\Players');
            $user = $userRepository->getUserByUsername($this->app['security']->getToken()->getUser()->getUsername());
            
            //We get current tournament name
            $tournamentRepository = $this->app['em']->getRepository('App\Model\Entity\Tournament');
            $tourn = $tournamentRepository->find($datas['tournament']);
            $tournname = $tourn->getName();
            
            $body = "Bonjour,<br/><br/>"
            		. "Vous avez reçu une invitation de ".$user->getFirstname()." ".$user->getLastname()." à rejoindre la compétition '".$tournname."' sur le site de pronostiques FBT. <br/>"
                    . "Pour vous inscrire, veuillez cliquer sur le lien suivant : <a href='http://".$_SERVER['SERVER_NAME'].$this->app['url_generator']->generate('user.signup')."'>Inscription</a>.<br />"
                    . "Une fois inscrit et identifié sur le site, vous pourrez rejoindre la compétition soit directement en cliquant sur <a href='http://".$_SERVER['SERVER_NAME'].$this->app['url_generator']->generate('tournament.join', array('idTournament' => $datas['tournament']))."'>Rejoindre</a>,"
                    . "soit en allant dans l'onglet compétition et en cliquant sur '".$tournname."'.<br/><br/>"
            		. "Ce mail est envoyé automatiquement, merci de ne pas y répondre.<br/><br/>"
            		. "A bientôt sur notre site.<br/>"
            		. "Sportivement !";
            
            $message = \Swift_Message::newInstance()
                ->setSubject('Invitation Prononostics')
                ->setFrom(array('noreply@brebion.info' => $user->getFirstname()." ".$user->getLastname()." by FBT"))
                ->setTo(array($datas['email']))
                ->setBody($body, 'text/html');

            $this->app['mailer']->send($message);

        }
        return $this->app->redirect($this->app["url_generator"]->generate('tournament.view', array('idTournament' => $datas['tournament'])));
    }
    
    public function connect(Application $app) {
        $this->app =$app;
        
        // créer un nouveau controller basé sur la route par défaut
        $tournament = $app['controllers_factory'];
        $tournament->match("/", 'App\Controller\TournamentController::index')->bind("tournament.index");
        $tournament->get('/mines', array($this, "myTournaments"))->bind("tournament.mytournaments");
        $tournament->get('/delete/{idTournament}', array($this,"delete"))->bind('tournament.delete');
        $tournament->post('/doadd', array($this,"doAdd"))->bind('tournament.doadd');
        $tournament->get('/add', array($this,"Add"))->bind('tournament.add');
        $tournament->get('/join/{idTournament}', array($this,"Join"))->bind('tournament.join');
        $tournament->get('/leave/{idTournament}', array($this,"Leave"))->bind('tournament.leave');
        $tournament->get('/view/{idTournament}', array($this,"View"))->bind('tournament.view');
        $tournament->get('/accept/{idTournPlayers}', array($this,"Accept"))->bind('tournament.accept');
        $tournament->get('/refuse/{idTournPlayers}', array($this,"Refuse"))->bind('tournament.refuse');
        $tournament->get('/remove/{idTournPlayers}', array($this,"Remove"))->bind('tournament.remove');
        $tournament->get('/setadmin/{idTournPlayers}', array($this,"SetAdmin"))->bind('tournament.setAdmin');
        $tournament->get('/removeadmin/{idTournPlayers}', array($this,"RemoveAdmin"))->bind('tournament.removeAdmin');
        $tournament->get('/invite/{idTournament}', array($this,"Invite"))->bind('tournament.invite');
        $tournament->get('/ranking/{idTournament}', array($this,"FindTournamentRanking"))->bind('tournament.ranking');
        
        $tournament->post('/doinvite', array($this,"doInvite"))->bind('tournament.doInvite');
        return $tournament;
    }

}
