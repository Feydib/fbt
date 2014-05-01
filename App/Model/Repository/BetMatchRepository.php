<?php

namespace App\Model\Repository;

use App\Model\Entity\Betmatchs;
use Doctrine\ORM\EntityRepository;

/**
 * BetMatchRepository
 */
class BetMatchRepository extends EntityRepository
{
    

    /**
     * Saves Betmatchs to the database.
     *
     * @param Betmatchs
     */
    public function save(Betmatchs $betMatch)
    {
        $this->_em->persist($betMatch);
        $this->_em->flush();
    }
    
    /**
     * Update Betmatchs to the database.
     *
     * @param Betmatchs
     */
    public function update(Betmatchs $betMatch)
    {
        $this->_em->merge($betMatch);
        $this->_em->flush();
    }
    /**
     * Returns a collection of betMatch.
     *
     * @param array $crit
     *   Crit for searching
     *
     * @return array A collection of matchs, keyed by id.
     */
    public function find($crit)
    {        
        $betMatch = $this->findOneBy($crit);
        return $betMatch ? $betMatch : FALSE;
    }
}