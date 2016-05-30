<?php
namespace App\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;
use App\Model\Entity\Teams;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Cookie;

class TeamsController implements ControllerProviderInterface {

    private $app;
    private $lid;

    public function index(Application $app) {
    //TODO to be update
/*       $tournamentRepository = $app['em']->getRepository('App\Model\Entity\Tournament');
       $tournamentList = $tournamentRepository->findTout(50);

       $userRepository =$app['em']->getRepository('App\Model\Entity\Players');
       $user = $userRepository->getUserByUsername($app['security']->getToken()->getUser()->getUsername());
       $myTournaments = $tournamentRepository->findMyTournaments($user);

       return $app["twig"]->render("tournament/index.twig", array('tournaments' => $tournamentList, "myTournaments" => $myTournaments));
*/    }

    public function Groups(Application $apps) {
    	$teamRepository = $this->app['em']->getRepository('App\Model\Entity\Teams');
    	$groupList = $teamRepository->findGroups($this->lid);
    	$teamsList = $teamRepository->findAll();

    	return $this->app['twig']->render('team/groups.twig', array('groupList' => $groupList, 'teamList' => $teamsList));
    }

    /**
     * Get pool details
     * @return view
     */
    public function poolResults(Application $app) {
        $teamRepository = $this->app['em']->getRepository('App\Model\Entity\Teams');
    	$teamsList = $teamRepository->findTeams(array("idleague" => $this->lid), null, 0, array("ranking" => "ASC"));
    	$groupList = $teamRepository->findGroups($this->lid);

        return $this->app["twig"]->render("match/pool.twig", array('groupList' => $groupList, 'teamsList' => $teamsList));
    }


    public function connect(Application $app) {
        $this->app = $app;
		$this->lid = $app['session']->get('idleague');
        // créer un nouveau controller basé sur la route par défaut
        $team = $app['controllers_factory'];
        $team->match("/", 'App\Controller\TeamsController::index')->bind("team.index");
        $team->get('/group', array($this,"Groups"))->bind('team.groups');
        $team->get('/pool', array($this,"poolResults"))->bind('team.pool');
       return $team;
    }

}
