<?php

namespace App\Model\Repository;

use App\Model\Entity\Betscore;
use App\Model\Entity\Tournament;
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
     * @param array $player
     *   player for searching
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
    
    /**
     * Returns a collection of betScore.
     *
     * @param array $player
     *   player for searching
     *
     * @return array A collection of score, keyed by id.
     */
    public function findRightPronostics($players) {        
        $qb = $this->_em->createQueryBuilder()
                ->select('b')
                ->from('App\Model\Entity\BetScore', 'b')
                ->where('b.idplayers = ?1')
                ->andWhere('b.score <> 0')
                ->setParameter(1, $players)
                ;
        
        $query = $qb->getQuery();
        return $query->getResult();
    }
    
        /**
     * Returns a collection of betScore.
     *
     * @param array $player
     *   player for searching
     *
     * @return array A collection of score, keyed by id.
     */
    public function findWrongPronostics($players) {        
        $qb = $this->_em->createQueryBuilder()
                ->select('b')
                ->from('App\Model\Entity\BetScore', 'b')
                ->where('b.idplayers = ?1')
                ->andWhere('b.score = 0')
                ->setParameter(1, $players)
                ;
        
        $query = $qb->getQuery();
        return $query->getResult();
    }
    
     /**
     * Returns players and total score
     *
     * @param Tournament $tournament
     *
     * @return Tournament|false An entity object if found, false otherwise.
     */
    public function findTournamentScores(Tournament $tournament)
    {
        $qb = $this->_em->createQueryBuilder()
                ->select('b, SUM(b.score) as score')
                ->from('App\Model\Entity\Betscore', 'b')
                ->leftJoin('b.players', 't')
                ->where('t.idtournament = ?1')
                ->groupBy('b.idplayers')
                ->orderBy("score", "DESC")
                ->setParameter(1, $tournament)
                ;
        
        $query = $qb->getQuery();
        $playerScore = $query->getResult();

        return $playerScore;
    }
    
         /**
     * Returns players and total score
     *
     * @param Tournament $tournament
     *
     * @return Tournament|false An entity object if found, false otherwise.
     */
    public function findScores()
    {
        $qb = $this->_em->createQueryBuilder()
                ->select('b, SUM(b.score) as score')
                ->from('App\Model\Entity\Betscore', 'b')
                ->groupBy('b.idplayers')
                ->orderBy("score", "DESC")
                ;
        
        $query = $qb->getQuery();
        $playerScore = $query->getResult();

        return $playerScore;
    }
    
    /**
     * Returns best players and total score
     *
     * @param Tournament $tournament
     *
     * @return Tournament|false An entity object if found, false otherwise.
     */
    public function findBestScores()
    {
        $qb = $this->_em->createQueryBuilder()
                ->select('b, SUM(b.score) as score')
                ->from('App\Model\Entity\Betscore', 'b')
                ->groupBy('b.idplayers')
                ->orderBy("score", "DESC")
               ->setMaxResults(10)
                ;
        
        $query = $qb->getQuery();
        $playerScore = $query->getResult();

        return $playerScore;
    }
}