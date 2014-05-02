<?php


namespace App\Model\Entity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * FbtPlayers
 *
 * @Table(name="FBT_Players")
 * @Entity(repositoryClass="App\Model\Repository\UserRepository")
 */
class Players implements UserInterface
{
    /**
     * @var integer $id
     * @Id
     * @Column(name="idPlayers", type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    private $idplayers;

    /**
     * @var string
     *
     * @Column(name="username", type="string", length=255, nullable=false)
     */
    private $username;

    /**
     * @var string
     *
     * @Column(name="firstname", type="string", length=45, nullable=true)
     */
    private $firstname;

    /**
     * @var string
     *
     * @Column(name="lastname", type="string", length=45, nullable=true)
     */
    private $lastname;

    /**
     * @var string
     *
     * @Column(name="mail", type="string", length=45, nullable=true)
     */
    private $mail;
        /**
     * Salt.
     *
     * @Column(name="salt", type="string", length=255)
     */
    private $salt;
    

    /**
     * @var string
     *
     * @Column(name="password", type="string", length=255, nullable=true)
     */
    private $password;

    /**
     * @var \DateTime
     *
     * @Column(name="rdate", type="datetime", nullable=true)
     */
    private $rdate;

    /**
     * @var string
     *
     * @Column(name="role", type="string", length=45, nullable=true)
     */
    private $role;

    /**
     * @var boolean
     *
     * @Column(name="active", type="boolean", nullable=true)
     */
    private $active;

    public function getIdplayers() {
        return $this->idplayers;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getFirstname() {
        return $this->firstname;
    }

    public function getLastname() {
        return $this->lastname;
    }

    public function getMail() {
        return $this->mail;
    }

    public function getPassword() {
        return $this->password;
    }

    public function getRdate() {
        return $this->rdate;
    }

    public function getRole() {
        return $this->role;
    }

    public function getActive() {
        return $this->active;
    }

    public function setIdplayers($idplayers) {
        $this->idplayers = $idplayers;
    }

    public function setUsername($username) {
        $this->username = $username;
    }

    public function setFirstname($firstname) {
        $this->firstname = $firstname;
    }

    public function setLastname($lastname) {
        $this->lastname = $lastname;
    }

    public function setMail($mail) {
        $this->mail = $mail;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function setRdate(\DateTime $rdate) {
        $this->rdate = $rdate;
    }
    /**
     * @inheritDoc
     */
    public function getRoles()
    {
        return array($this->getRole());
    }
    public function setRole($role) {
        $this->role = $role;
    }

    public function setActive($active) {
        $this->active = $active;
    }

    public function eraseCredentials() {
        
    }

    public function getSalt() {
        
    }
    
    public function setSalt($salt) {
        $this->salt = $salt;
    }

}
