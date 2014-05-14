<?php

namespace App\Model\Repository;

use App\Model\Entity\Players;
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