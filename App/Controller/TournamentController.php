<?php
namespace App\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use App\Model\Entity\Tournament;
use App\Model\Entity\Tournplayers;
use App\Model\Entity\Teams;

class TournamentController implements ControllerProviderInterface {
    
    private $app;

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
        
        $tournPlayersRepository = $this->app['em']->getRepository('App\Model\Entity\Tournplayers');
        $tournPlayer = new Tournplayers();
        $tournPlayer->setIdtournament($tournamentRepository->find($idTournament));    

        $userRepository = $this->app['em']->getRepository('App\Model\Entity\Players');
        $user = $userRepository->getUserByUsername($this->app['security']->getToken()->getUser()->getUsername());

        $tournPlayer->setIdPlayers($user);
        $tournPlayer->setIsAdmin(false);
        $tournPlayer->setIsaccepted(false);
            
        $tournPlayersRepository->save($tournPlayer);
        
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
        
        $teamRepository = $this->app['em']->getRepository('App\Model\Entity\Teams');
    	$teamsList = $teamRepository->findTeams(array(), null, 0, array("ranking" => "ASC"));
    	$groupList = $teamRepository->findGroups();
    	
        //We get current user and we check he's admin of tournament to accept
        $userRepository = $this->app['em']->getRepository('App\Model\Entity\Players');
        $user = $userRepository->getUserByUsername($this->app['security']->getToken()->getUser()->getUsername());
        $currentToPlayer = $tournPlayersRepository->findTournPlayers($user, $tounament);

        //We vérify that current user is group member and accepted in group and we check if user is admin
        if($currentToPlayer && $currentToPlayer->getIsadmin()) {
            $isadmin = true;
        } else if (!$currentToPlayer || !$currentToPlayer->getIsaccepted()) {
            return $this->app->redirect($this->app["url_generator"]->generate("tournament.index"));
        } else {
            $isadmin = false;
        }

        return $this->app["twig"]->render("tournament/view.html.twig", array('tournament' => $tounament, 'currentUserAdmin' => $isadmin, 'groupList' => $groupList, 'teamsList' => $teamsList));
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
     * remove admin right on a tournament
     * @param int $idTournPlayers
     */
    public function invite($idTournament) {
        $inviteForm = $this->app['form.factory']->create(new \App\Form\MailType($idTournament));
        return $this->app['twig']->render('form/mail.twig', array("form" => $inviteForm->createView()));
    }
    
    /**
     * remove admin right on a tournament
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
            
            $body = "Vous avez reçu une invitation de ".$user->getFirstname()." ".$user->getLastname()." à rejoindre le site de pronotiques FBT. <br/>"
                    . "Veuillez cliquer sur le lien suivant pour vous inscrire <a href=".$_SERVER['SERVER_NAME'].$this->app['url_generator']->generate('user.signup').">Inscription</a><br />"
                    . "Une fois identifié sur celui-ci pour rejoindre la compétition <a href=".$_SERVER['SERVER_NAME'].$this->app['url_generator']->generate('tournament.join', array('idTournament' => $datas['tournament'])).">Rejoindre</a>"
                    . "";
            
            $message = \Swift_Message::newInstance()
                ->setSubject('Invitation Prononostics')
                ->setFrom(array('noreply@yoursite.com'))
                ->setTo(array($datas['email']))
                ->setBody($body, 'text/html');

            $this->app['mailer']->send($message);

        }
        
        return $this->app->redirect($this->app["url_generator"]->generate("tournament.index"));
    }
    
    public function connect(Application $app) {
        $this->app =$app;
        
        // créer un nouveau controller basé sur la route par défaut
        $tournament = $app['controllers_factory'];
        $tournament->match("/", 'App\Controller\TournamentController::index')->bind("tournament.index");
        $tournament->get('/delete/{idTournament}', array($this,"delete"))->bind('tournament.delete');
        $tournament->post('/doadd', array($this,"doAdd"))->bind('tournament.doadd');
        $tournament->get('/add', array($this,"Add"))->bind('tournament.add');
        $tournament->get('/join/{idTournament}', array($this,"Join"))->bind('tournament.join');
        $tournament->get('/leave/{idTournament}', array($this,"Leave"))->bind('tournament.leave');
        $tournament->get('/view/{idTournament}', array($this,"View"))->bind('tournament.view');
        $tournament->get('/accept/{idTournPlayers}', array($this,"Accept"))->bind('tournament.accept');
        $tournament->get('/remove/{idTournPlayers}', array($this,"Remove"))->bind('tournament.remove');
        $tournament->get('/setadmin/{idTournPlayers}', array($this,"SetAdmin"))->bind('tournament.setAdmin');
        $tournament->get('/removeadmin/{idTournPlayers}', array($this,"RemoveAdmin"))->bind('tournament.removeAdmin');
        $tournament->get('/invite/{idTournament}', array($this,"Invite"))->bind('tournament.invite');
        $tournament->post('/doinvite', array($this,"doInvite"))->bind('tournament.doInvite');
        return $tournament;
    }

}
