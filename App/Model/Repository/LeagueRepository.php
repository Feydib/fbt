<?php
/*
 * Created on 30 mai 16
 *
 * To change the template for this generated file go to
 * Window - Preferences - PHPeclipse - PHP - Code Templates
 */

namespace App\Model\Repository;

use App\Model\Entity\League;
use Doctrine\ORM\EntityRepository;

 /**
 * League repository
 */
class LeagueRepository extends EntityRepository
{

    /**
     * Saves league to the database.
     *
     * @param League
     */
    public function save(League $league)
    {
        $this->_em->persist($league);
        $this->_em->flush();
    }

    /**
     * Deletes the League.
     *
     * @param League league
     */
    public function delete(League $league)
    {
        $this->_em->remove($league);
        $this->_em->flush();
    }

     /**
     * Returns a League matching the supplied id.
     *
     * @param array $crit
     *
     * @return League|false An entity object if found, false otherwise.
     */
    public function findLeague($crit = array())
    {
        $leagueData = $this->findOneBy($crit);
        return $leagueData ? $leagueData : FALSE;
    }


}
?>
