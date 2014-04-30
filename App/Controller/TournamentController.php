<?php
namespace App\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use App\Model\Entity\Tournament;
use App\Model\Entity\Toplayers;


class TournamentController implements ControllerProviderInterface {
    
    private $app;

    public function index(Application $app) {
       $tournamentRepository = $app['em']->getRepository('App\Model\Entity\Tournament');
       $tournamentList = $tournamentRepository->findTout(50);
       
       $userRepository =$app['em']->getRepository('App\Model\Entity\Players');
       $user = $userRepository->getUserByUsername($app['security']->getToken()->getUser()->getUsername());
       $myTournaments = $tournamentRepository->findMyTournaments($user);

       return $app["twig"]->render("tournament/index.twig", array('tournaments' => $tournamentList, "myTournaments" => $myTournaments));
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
            $tournament->setYear($datas['date']);
                        
            $tournamentRepository->save($tournament);
            
            $tournPlayersRepository = $this->app['em']->getRepository('App\Model\Entity\Toplayers');
            $tournPlayer = new Toplayers();
            $tournPlayer->setIdtournament($tournament);    

            $userRepository = $this->app['em']->getRepository('App\Model\Entity\Players');
            $user = $userRepository->getUserByUsername($this->app['security']->getToken()->getUser()->getUsername());

            $tournPlayer->setIdPlayers($user);
            $tournPlayer->setIsAdmin(true);
            
            $tournPlayersRepository->save($tournPlayer);
                    
            $this->app['session']->getFlashBag()->add('success', $this->app['translator']->trans('save done'));
        } else {
            $this->app['session']->getFlashBag()->add('error', $this->app['translator']->trans('The form contains errors'));
        }
        $tournamentList = $tournamentRepository->findTout(50);

        return $this->app["twig"]->render("tournament/index.twig", array('tournaments' => $tournamentList));
    }
    
    /**
     * Delete a tournament
     * @param int $idTournament
     */
    public function delete($idTournament) {
        /*if ($this->app['security']->isGranted('ROLE_USER')){
            return;
        } */
        $tournamentRepository = $this->app['em']->getRepository('App\Model\Entity\Tournament');
        $tournamentRepository->delete($tournamentRepository->find($idTournament));
        
        return $this->app->redirect($this->app["url_generator"]->generate("tournament.index"));
        
    }
    
    /**
     * Join a tournament
     * @param int $idTournament
     */
    public function join($idTournament) {
        $tournamentRepository = $this->app['em']->getRepository('App\Model\Entity\Tournament');
        
        $tournPlayersRepository = $this->app['em']->getRepository('App\Model\Entity\Toplayers');
        $tournPlayer = new Toplayers();
        $tournPlayer->setIdtournament($tournamentRepository->find($idTournament));    

        $userRepository = $this->app['em']->getRepository('App\Model\Entity\Players');
        $user = $userRepository->getUserByUsername($this->app['security']->getToken()->getUser()->getUsername());

        $tournPlayer->setIdPlayers($user);
        $tournPlayer->setIsAdmin(false);
            
        $tournPlayersRepository->save($tournPlayer);
        
        return $this->app->redirect($this->app["url_generator"]->generate("tournament.index"));
    }
    
    /**
     * Leave a tournament
     * @param int $idTournament
     */
    public function leave($idTournament) {
        $tournamentRepository = $this->app['em']->getRepository('App\Model\Entity\Tournament');
        $tournPlayersRepository = $this->app['em']->getRepository('App\Model\Entity\Toplayers');
        $userRepository = $this->app['em']->getRepository('App\Model\Entity\Players');
        
        //We get current user
        $user = $userRepository->getUserByUsername($this->app['security']->getToken()->getUser()->getUsername());
        //We find tournament to leave
        $tournPlayer = $tournPlayersRepository->findToPlayers($user, $tournamentRepository->find($idTournament));
        //We remove entry for currect user in tournament
        $tournPlayersRepository->remove($tournPlayer);
        
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
        return $tournament;
    }

}
