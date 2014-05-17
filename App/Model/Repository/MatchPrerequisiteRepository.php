<?php

namespace App\Model\Repository;

use App\Model\Entity\MatchPrerequisite;
use Doctrine\ORM\EntityRepository;

/**
 * MatchPrerequisite repository
 */
class MatchPrerequisiteRepository extends EntityRepository
{
    
    
    /**
     * Saves players tournament to the database.
     *
     * @param Tournament
     */
    public function save(Tournplayers $tournPlayer)
    {
        $this->_em->persist($tournPlayer);
        $this->_em->flush();
    }

    /**
     * Returns a Matchs.
     *
     * @param integer $limit
     *   The number of users to return.
     * @param integer $offset
     *   The number of users to skip.
     * @param array $orderBy
     *   Optionally, the order by info, in the $column => $direction format.
     *
     * @return a Match.
     */
    public function find($crit = array())
    {        
        $matchs = $this->findOneBy($crit);
        return $matchs;
    }
}