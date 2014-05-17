<?php

namespace App\Model\Repository;

use App\Model\Entity\Matchteam;
use Doctrine\ORM\EntityRepository;

/**
 * MatchTeamRepository
 */
class MatchTeamRepository extends EntityRepository
{
    
    /**
     * Saves MatchTeam to the database.
     *
     * @param Matchteam
     */
    public function save(Matchteam $matchteam)
    {
        $this->_em->persist($matchteam);
        $this->_em->flush();
    }
    /**
     * Saves MatchTeam to the database.
     *
     * @param Matchteam
     */
    public function update(Matchteam $matchteam)
    {
        $this->_em->merge($matchteam);
        $this->_em->flush();
    }
    /**
     * Returns a collection of MatchsTeam.
     *
     * @param integer $limit
     *   The number of users to return.
     * @param integer $offset
     *   The number of users to skip.
     * @param array $orderBy
     *   Optionally, the order by info, in the $column => $direction format.
     *
     * @return array A collection of matchs, keyed by id.
     */
    public function find($limit = null, $offset = 0, $crit = array(), $orderBy = array())
    {        
        $matchs = $this->findBy($crit, $orderBy, $limit, $offset);
        return $matchs;
    }
     /**
     * Returns a collection of MatchsTeam.
     *
     * @param integer $limit
     *   The number of users to return.
     * @param integer $offset
     *   The number of users to skip.
     * @param array $orderBy
     *   Optionally, the order by info, in the $column => $direction format.
     *
     * @return array A collection of matchs, keyed by id.
     */
    public function findOne($crit = array())
    {        
        $matchs = $this->findOneBy($crit);
        return $matchs;
    }
}