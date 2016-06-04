<?php

namespace App\Model\Repository;

use App\Model\Entity\Players;
use App\Model\Entity\Tournplayers;
use Doctrine\ORM\EntityRepository;
use DateTime;
/**
 * User repository
 */
class UserRepository extends EntityRepository
{
    /**
     * @var \Doctrine\DBAL\Connection
     */
    //protected $db;
    protected $table;

    public function getUser($crit = array())
    {
        $user = $this->findOneBy($crit);
        if (empty($user)) {
    		return false;
    	}
        return $user;
    }

    public function getUserByUsername($username)
    {
        $user = $this->findOneBy(array('username' => $username));
        return $user;
    }

    public function getUserById($idPlayer)
    {
    	$user = $this->findOneBy(array('idplayers' => $idPlayer));
    	return $user;
    }

    public function getUserByMail($mail)
    {
    	$user = $this->findOneBy(array('mail' => $mail));
    	if (empty($user)) {
    		return false;
    	}
    	return $user;
    }

    public function getUserByLeague($idleague = NULL) {
    	//TODO -> SQL request SELECT * FROM FBT.FBT_TournPlayers as tp inner join FBT_Players as p on tp.idPlayers=p.idPlayers inner join FBT_Tournament as t on tp.idTournament=t.idTournament where t.idLeague = 1 group by tp.idPlayers;
    	$qb = $this->_em->createQueryBuilder()
    	->select ('p.idplayers')
    	->from('App\Model\Entity\Tournplayers','tp')
    	->join('tp.idplayers', 'p')
    	->join('tp.idtournament', 't')
    	->Where('t.idleague = ?1')
    	->groupBy('tp.idplayers')
    	->setParameter(1,$idleague)
    	;
    	$query = $qb->getQuery();
    	return $query->getResult() ? $query->getResult() : FALSE;
    }

    /**
     * Saves the user to the database.
     *
     * @param User $user
     */
    public function save(Players $user)
    {
        $this->_em->persist($user);
        $this->_em->flush();
    }


    /**
     * Instantiates a user entity and sets its properties using db data.
     *
     * @param array $userData
     *   The array of db data.
     *
     * @return \MusicBox\Entity\User
     */
    protected function buildUser($userData)
    {
        $user = new Players();
        $user->setIdPlayers($userData['idPlayers']);
        $user->setUsername($userData['username']);
        $user->setFirstname($userData['firstname']);
        $user->setLastname($userData['lastname']);
        $user->setSalt($userData['salt']);
        $user->setPassword($userData['password']);
        $user->setmail($userData['mail']);
        $user->setRole($userData['role']);
        $user->setRdate(new DateTime($userData['rdate']));
        $user->setActive($userData['active']);

        return $user;
    }


    /**
     * We verify if the email already exist
     * @param type $email
     * @return boolean
     */
    function emailExists($email) {
        $usersData = $this->findOneBy(array( "mail" => $email));
        if (!empty($usersData)) {
            return true;
        }
        return false;
    }

    /**
     * We verify if the username already exist
     * @param type $username
     * @return boolean
     */
    function usernameExists($username) {
        $usersData = $this->findOneBy(array( "username" => $username));
        if (!empty($usersData)) {
            return true;
        }
        return false;
    }
}