<?php

namespace App\Model\Repository;

use App\Model\Entity\Tournament;
use App\Model\Entity\Players;
use Doctrine\ORM\EntityRepository;
use \DateTime;

/**
 * User repository
 */
class TournamentRepository extends EntityRepository
{

    /**
     * Saves tournament to the database.
     *
     * @param Tournament
     */
    public function save(Tournament $tournament)
    {
        $this->_em->persist($tournament);
        $this->_em->flush();
    }

    /**
     * Deletes the tournament.
     *
     * @param Tournament tournament
     */
    public function delete(Tournament $tournament)
    {
        $this->_em->remove($tournament);
        $this->_em->flush();
    }

    /**
     * Returns a tournament matching the supplied id.
     *
     * @param array $crit
     *
     * @return Tournament|false An entity object if found, false otherwise.
     */
    public function find($crit = array())
    {
        $tournamentData = $this->findOneBy($crit);
        return $tournamentData ? $tournamentData : FALSE;
    }

    /**
     * Returns a tournament matching the supplied id.
     *
     * @param integer $id
     *
     * @return Tournament|false An entity object if found, false otherwise.
     */
    public function findMyTournaments(Players $players, $league){
    	$parameters = array (
    		'player' => $players,
    		'league' => $league
    		);
        $qb = $this->_em->createQueryBuilder()
                ->select('t')
                ->addSelect('to')
                ->from('App\Model\Entity\Tournament', 't')
                ->leftJoin('t.players', 'to')
                ->where('to.idplayers = :player')
                ->andwhere('t.idleague = :league')
                ->setParameters($parameters)
                ;

        $query = $qb->getQuery();
        $tournaments = $query->getResult();

        return $tournaments;
    }

    /**
     * Returns a collection of Tournament.
     *
     * @param integer $limit
     *   The number of users to return.
     * @param integer $offset
     *   The number of users to skip.
     * @param array $orderBy
     *   Optionally, the order by info, in the $column => $direction format.
     *
     * @return array A collection of users, keyed by user id.
     */
    public function findAllTournaments($crit=array())
    {
        $tournaments = $this->findBy($crit);
        return $tournaments;
    }


    /**
     * Instantiates a Tournament entity and sets its properties using db data.
     *
     * @param array $tournamentData
     *   The array of db data.
     *
     * @return Tournament
     */
    protected function buildTournament($tournamentData)
    {
        $tournament = new Tournament();
        $tournament->setIdtournament($tournamentData['idTournament']);
        $tournament->setIdleague($tournamentData['idLeague']);
        $tournament->setName($tournamentData['name']);
        $tournament->setYear(new DateTime($tournamentData['year']));

        return $tournament;
    }


}