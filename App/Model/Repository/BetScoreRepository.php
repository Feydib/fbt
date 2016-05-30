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
    public function findSum($players, $league)
    {
    	$parameters = array (
    		'player' => $players,
    		'league' => $league
    		);
        $qb = $this->_em->createQueryBuilder()
                ->select('SUM(b.score)')
                ->from('App\Model\Entity\BetScore', 'b')
                ->where('b.idplayers = :player')
                ->andWhere('b.idleague = :league')
                ->setParameters($parameters)
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
    public function findRightPronostics($players,$league)
    {
    	$parameters = array (
    		'player' => $players,
    		'league' => $league
    		);
        $qb = $this->_em->createQueryBuilder()
                ->select('b')
                ->from('App\Model\Entity\BetScore', 'b')
                ->where('b.idplayers = :player')
                ->andWhere('b.idleague = :league')
                ->andWhere('b.score <> 0')
                ->setParameters($parameters)
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
    public function findWrongPronostics($players,$league)
    {
    	$parameters = array (
    		'player' => $players,
    		'league' => $league
    		);
        $qb = $this->_em->createQueryBuilder()
                ->select('b')
                ->from('App\Model\Entity\BetScore', 'b')
                ->where('b.idplayers = :player')
                ->andWhere('b.idleague = :league')
                ->andWhere('b.score = 0')
                ->setParameters($parameters)
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
    public function findTournamentScores(Tournament $tournament,$league) {
    	$parameters = array (
    		'tournament' => $tournament,
    		'league' => $league
    		);
        $qb = $this->_em->createQueryBuilder()
                ->select('b, SUM(b.score) as score')
                ->from('App\Model\Entity\Betscore', 'b')
                ->leftJoin('b.players', 't')
                ->where('t.idtournament = :tournament')
                ->andWhere('b.idleague = :league')
                ->groupBy('b.idplayers')
                ->orderBy("score", "DESC")
                ->setParameters($parameters)
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
    public function findScores($league)
    {
    	$parameters = array (
    		'league' => $league
    		);
        $qb = $this->_em->createQueryBuilder()
                ->select('b, SUM(b.score) as score')
                ->from('App\Model\Entity\Betscore', 'b')
                ->Where('b.idleague = :league')
                ->groupBy('b.idplayers')
                ->orderBy("score", "DESC")
                ->setParameters($parameters)
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
    public function findBestScores($league){
    	$parameters = array (
    		'league' => $league
    		);
        $qb = $this->_em->createQueryBuilder()
                ->select('b, SUM(b.score) as score')
                ->from('App\Model\Entity\Betscore', 'b')
                ->Where('b.idleague = :league')
                ->groupBy('b.idplayers')
                ->orderBy("score", "DESC")
               ->setMaxResults(10)
               ->setParameters($parameters)
                ;

        $query = $qb->getQuery();
        $playerScore = $query->getResult();

        return $playerScore;
    }
}