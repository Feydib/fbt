<?php

namespace App\Model\Repository;

use App\Model\Entity\Toplayers;
use Doctrine\ORM\EntityRepository;
use App\Model\Entity\Players;
use App\Model\Entity\Tournament;
/**
 * User repository
 */
class ToPlayersRepository extends EntityRepository
{

    public function findToPlayers(Players $players, Tournament $tournament) {
        $toPlayers = $this->findOneBy(array( "idtournament" => $tournament, 'idplayers' => $players));
        return $toPlayers ? $toPlayers : FALSE;
    }
    /**
     * Saves tournament to the database.
     *
     * @param Tournament
     */
    public function remove(Toplayers $tournPlayer)
    {
        $this->_em->remove($tournPlayer);
        $this->_em->flush();
    }
    
    /**
     * Saves players tournament to the database.
     *
     * @param Tournament
     */
    public function save(Toplayers $tournPlayer)
    {
        $this->_em->persist($tournPlayer);
        $this->_em->flush();
    }


}