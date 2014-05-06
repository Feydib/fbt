<?php

namespace App\Model\Repository;

use App\Model\Entity\Teams;
use Doctrine\ORM\EntityRepository;

/**
 * User repository
 */
class TeamsRepository extends EntityRepository
{

    /**
     * Saves team to the database.
     *
     * @param Team
     */
    public function save(Team $team)
    {
        $this->_em->persist($team);
        $this->_em->flush();
    }

    /**
     * Deletes the tournament.
     *
     * @param Tournament tournament
     */
    public function delete(Team $team)
    {
        $this->_em->remove($team);
        $this->_em->flush();
    }

    /**
     * Returns a team matching the supplied id.
     *
     * @param integer $id
     *
     * @return Team|false An entity object if found, false otherwise.
     */
    public function find($id)
    {
        $teamtData = $this->findOneBy(array( "idteam" => $id));
        return $teamData ? $teamData : FALSE;
    }

    /**
     * Returns a collection of groups
     *
     * @param 
     * @return array A collection of group.
     */
    public function findGroups()
    {
    	$qb = $this->_em->createQueryBuilder()
    		->select('t.pool')
    		->from('App\Model\Entity\Teams', 't')
    		->groupBy('t.pool');
    		;	
    	$query = $qb->getQuery();
    	return $query->getResult();
    }
    
    /**
     * Returns a collection of Team.
     *
     * @param integer $limit
     *   The number of teams to return.
     * @param integer $offset
     *   The number of teams to skip.
     * @param array $orderBy
     *   Optionally, the order by info, in the $column => $direction format.
     *
     * @return array A collection of users, keyed by user id.
     */
    public function findTeams($limit, $offset = 0, $orderBy = array())
    {        
        $teams = $this->findBy(array(), $orderBy, $limit, $offset);
        return $teams;
    }
    
    
    /**
     * Instantiates a Team entity and sets its properties using db data.
     *
     * @param array $teamData
     *   The array of db data.
     *
     * @return Team
     */
    protected function buildTeam($teamData)
    {
        $team = new Team();
        $team->setpool($teamData['pool']);
        $team->setcountries($teamData['countries']);

        return $team;
    }

    /**
     * Update a Team entity and sets its properties using db data.
     *
     * @param array $teamData
     *   The array of db data.
     *
     * @return Team
     */
    protected function updateTeam($teamData)
    {
    //TODO create update method
        $team = new Team();
        $team->setpool($teamData['pool']);
        $team->setcountries($teamData['countries']);

        return $team;
    }


}