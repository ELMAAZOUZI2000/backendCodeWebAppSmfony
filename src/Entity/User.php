<?php

namespace App\Entity;
 
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
/**
 * @ORM\Entity(repositoryClass=UserRepository::class) 
 * @UniqueEntity(
 *      fields={"email"}, message="email déjà existé" 
 * )
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min=10,max=255)
     */
    private $username;

    /**
     * @ORM\Column(type="string", length=255) 
     * @Assert\Email()
     */
    private $email;

     

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Length(min=7,max=255)
     * @Assert\EqualTo(propertyPath="confirmPassword",message="passwords are not the same") 
    */
    private $password;

    /**
     *  @var string
     *  @Assert\EqualTo(propertyPath="confirmPassword",message="passwords are not the same") 
     */
    public $confirmPassword;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $account_validation;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }
    
    

    public function getSalt(){
        return null;
    }

    public function eraseCredentials(){
        
    }

     
    public function getRoles(): array
    {
        return ['ROLE_USER'];
    }
 
    public function setRoles(array $roles): self
    {
        $this->roles = [];
        foreach ($roles as $role) {
            $this->addRole($role);
        }

        return $this;
    }

    public function getAccountValidation(): ?bool
    {
        return $this->account_validation;
    }

    public function setAccountValidation(?bool $account_validation): self
    {
        $this->account_validation = $account_validation;

        return $this;
    }

}
