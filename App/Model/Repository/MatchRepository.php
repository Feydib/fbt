<?php

namespace App\Model\Repository;

use App\Model\Entity\Match;
use Doctrine\ORM\EntityRepository;

/**
 * User repository
 */
class MatchRepository extends EntityRepository
{
    /**
     * Saves tournament to the database.
     *
     * @param Tournament
     */
    public function remove(Match $tournPlayer)
    {
        $this->_em->remove($tournPlayer);
        $this->_em->flush();
    }

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
     * Returns a collection of Matchs.
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
    public function find($crit = array(), $limit = null, $offset = 0, $orderBy = array())
    {
        $matchs = $this->findBy($crit, $orderBy, $limit, $offset);
        return $matchs;
    }

    /**
     * Returns a collection of Matchs.
     *
     * @param integer $limit
     *   The number of users to return.
     * @param integer $offset
     *   The number of users to skip.
     * @param array $orderBy
     *   Optionally, the order by info, in the $column => $direction format.
     *
     * @return array A collection of matchs
     */
    public function findNext($date, $sort, $limit) {
        $qb = $this->_em->createQueryBuilder()
                ->select('m')
                ->from('App\Model\Entity\Matchs', 'm')
                ->where('m.date > ?1')
                ->setParameter(1, $date)
                ->orderBy($sort)
                ->setMaxResults($limit)
                ;

        $query = $qb->getQuery();
        return $query->getResult();
    }

     /**
     * Returns a collection of Matchs.
     *
     * @param integer $limit
     *   The number of users to return.
     * @param integer $offset
     *   The number of users to skip.
     * @param array $orderBy
     *   Optionally, the order by info, in the $column => $direction format.
     *
     * @return array A collection of matchs
     */
    public function findPrevious($date, $sort, $limit) {
        $qb = $this->_em->createQueryBuilder()
                ->select('m')
                ->from('App\Model\Entity\Matchs', 'm')
                ->where('m.date < ?1')
                ->setParameter(1, $date)
                ->orderBy($sort, "DESC")
                ->setMaxResults($limit)
                ;

        $query = $qb->getQuery();
        return $query->getResult();
    }
}