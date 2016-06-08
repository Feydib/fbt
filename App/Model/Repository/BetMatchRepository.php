<?php

namespace App\Model\Repository;

use App\Model\Entity\Betmatchs;
use App\Model\Entity\Players;
use App\Model\Entity\Matchs;
use App\Model\Entity\Matchteam;
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
     * @param integer $limit
     *   The number of matchs to return.
     * @param integer $offset
     *   The number of matchs to skip.
     * @param array $orderBy
     *   Optionally, the order by info, in the $column => $direction format.
     *
     * @return array A collection of matchs, keyed by id.
     */
    public function find($crit)
    {
        $betMatch = $this->findOneBy($crit);
        return $betMatch ? $betMatch : FALSE;
    }

    /**
     * Returns a collection of Players.
     *
     * @param
     *
     * @return array A collection of all Players Id.
     */
    public function findAllPlayersId()
    {
    	$qb = $this->_em->createQueryBuilder()
    	->select ('pl.idplayers')
    	->from('App\Model\Entity\Betmatchs','be')
    	->join('be.idplayers', 'pl')
    	->groupBy('be.idplayers')
    	;
    	$query = $qb->getQuery();
    	return $query->getResult() ? $query->getResult() : FALSE;
    }

    /**
     * Returns a collection of Players filtered by League.
     *
     * @param integer $idleague
     *		The league id filter
     * @return array A collection of all Players Id.
     */
    public function findPlayersIdbyLeague($idleague)
    {
    	$parameters = array (
    		'league' => $idleague
    		);

    	$qb = $this->_em->createQueryBuilder()
    	->select ('pl.idplayers')
    	->from('App\Model\Entity\Betmatchs','be')
    	->join('be.idplayers', 'pl')
    	->join('be.idmatchteam', 'mt')
    	->join('mt.idmatchs', 'm')
    	->groupBy('be.idplayers')
    	->where('m.idleague = :league')
    	->setparameters($parameters)
    	;
    	$query = $qb->getQuery();
    	return $query->getResult() ? $query->getResult() : FALSE;
    }

}