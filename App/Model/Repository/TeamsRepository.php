<?php

namespace App\Model\Repository;

use App\Model\Entity\Teams;
use Doctrine\ORM\EntityRepository;

use App\Model\Entity\Football;
use App\Model\Entity\Countries;

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
    public function save(Teams $team)
    {
        $this->_em->persist($team);
        $this->_em->flush();
    }

     /**
     * Update Team to the database.
     *
     * @param Team
     */
    public function update(Teams $team)
    {
        $this->_em->merge($team);
        $this->_em->flush();
    }
    
    /**
     * Deletes the tournament.
     *
     * @param Tournament tournament
     */
    public function delete(Teams $team)
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
     * @return array A collection of teams, keyed by team id.
     */
    public function findTeams($crit=array(), $limit = null, $offset = 0, $orderBy = array())
    {        
        $teams = $this->findBy($crit, $orderBy, $limit, $offset);
        return $teams;
    }
    
    /**
     * Get teams wich are not finished their pool.
     *
     * @param integer $limit
     * @return Returns a collection of Team.
     */
    public function findTeamsPoolUnfinished($pool) {
        $qb = $this->_em->createQueryBuilder()
                ->select('t')
                ->from('App\Model\Entity\Teams', 't')
                ->where('t.played < 3')
                ->andWhere('t.pool = ?1')
                ->setParameter(1, $pool)
                ;
        
        $query = $qb->getQuery();
        $teams = $query->getResult();
        return $teams;
        
    }

    /**
     * Get teams ranking.
     *
     * @param integer $idteam
     * @return Returns an INT.
     */
    public function getRank($idteam) {
    	$qb = $this->_em->createQueryBuilder()
    	->select('f.worldrank')
    	->from('App\Model\Entity\Teams', 't')
    	->join('t.countries', 'c')
    	->join('c.football','f')
    	->where('t.idteams = ?1')
    	->setParameter(1, $idteam)
    	;
    	
    	$query = $qb->getQuery();
    	$rank = $query->getResult();
    	return $rank[0]['worldrank'];
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