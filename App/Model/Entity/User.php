<?php
namespace App\Model\Entity;

use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Products
 *
 * @Table(name="users")
 * @Entity(repositoryClass="App\Model\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @var integer $id
     * @Id
     * @Column(type="integer")
     * @GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
    * Username.
    *
    *  @Column(name="username", type="string", length=255)
    */
    protected $username;
    
    /**
     * FirstName.
     *
     * @Column(name="firstName", type="string", length=255)
     */
    protected $firstname;
    
    /**
     * LastName.
     *
     * @Column(name="lastname", type="string", length=255)
     */
    protected $lastname;

    /**
     * Salt.
     *
     * @Column(name="salt", type="string", length=255)
     */
    protected $salt;

    /**
     * Password.
     *
     * @Column(name="password", type="string", length=255)
     */
    protected $password;

    /**
     * Email.
     *
     * @Column(name="email", type="string", length=255)
     */
    protected $email;

    /**
     * Role.
     *
     * ROLE_USER or ROLE_ADMIN.
     *
     * @Column(name="role", type="string", length=255)
     */
    protected $role;


    /**
     * When the artist entity was created.
     *
     * @Column(name="createdAt", type="datetime")
     */
    protected $createdAt;
    

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @inheritDoc
     */
    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }
    
        /**
     * @inheritDoc
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    }

        /**
     * @inheritDoc
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }
    
    /**
     * @inheritDoc
    */
    public function getSalt()
    {
        return $this->salt;
    }

    public function setSalt($salt)
    {
        $this->salt = $salt;
    }

    /**
     * @inheritDoc
     */
    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($mail)
    {
        $this->email = $mail;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @inheritDoc
     */
    public function getRoles()
    {
        return array($this->getRole());
    }

    public function getRole()
    {
        return $this->role;
    }

    public function setRole($role) {
        $this->role = $role;
    }

    /**
     * @inheritDoc
     */
    public function eraseCredentials()
    {
    }
    
    public function toArray(User $user){
        return (array)$user;
    }
}