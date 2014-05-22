<?php

namespace App\Model\Repository;

use App\Model\Entity\Betscore;
use Doctrine\ORM\EntityRepository;

/**
 * BetMatchRepository
 */
class BetScoreRepository extends EntityRepository
{
    

    /**
     * Saves Betscore to the database.
     *
     * @param Betscore
     */
    public function save(Betscore $betScore)
    {
        $this->_em->persist($betScore);
        $this->_em->flush();
    }
    
    /**
     * Update Betscore to the database.
     *
     * @param Betscore
     */
    public function update(BetScore $betScore)
    {
        $this->_em->merge($betScore);
        $this->_em->flush();
    }
    /**
     * Returns a collection of betScore.
     *
     * @param array $crit
     *   Crit for searching
     *
     * @return array A collection of score, keyed by id.
     */
    public function find($crit)
    {        
        $betScore = $this->findOneBy($crit);
        return $betScore ? $betScore : FALSE;
    }
    
        /**
     * Returns a collection of betScore.
     *
     * @param array $crit
     *   Crit for searching
     *
     * @return array A collection of score, keyed by id.
     */
    public function findSum($players)
    {        
        $qb = $this->_em->createQueryBuilder()
                ->select('SUM(b.score)')
                ->from('App\Model\Entity\BetScore', 'b')
                ->where('b.idplayers = ?1')
                ->setParameter(1, $players)
                ;
        
        $query = $qb->getQuery();
        return $query->getOneOrNullResult();
    }
}