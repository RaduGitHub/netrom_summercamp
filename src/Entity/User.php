<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @method string getUserIdentifier()
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=320)
     */
    private string $email;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private string $password;

    /**
     * @ORM\OneToMany(targetEntity=LicensePlate::class, mappedBy="user_id")
     */
    private $licensePlates;

    private string $roles;

    public function __construct()
    {
        $this->licensePlates = new ArrayCollection();
        $this->roles = "";
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection|LicensePlate[]
     */
    public function getLicensePlates(): Collection
    {
        return $this->licensePlates;
    }

    public function addLicensePlate(LicensePlate $licensePlate): self
    {
        if (!$this->licensePlates->contains($licensePlate)) {
            $this->licensePlates[] = $licensePlate;
            $licensePlate->setLicensePlate($this);
        }

        return $this;
    }

    public function removeLicensePlate(LicensePlate $licensePlate): self
    {
        if ($this->licensePlates->removeElement($licensePlate)) {
            // set the owning side to null (unless already changed)
            if ($licensePlate->getLicensePlate() === $this) {
                $licensePlate->setLicensePlate(null);
            }
        }

        return $this;
    }

    public function setRole(string $role = "ROLE_USER"){
        //dd($roles)
        //$this->roles = $role;
    }

    public function getRoles()
    {
        //$roles = $this->roles->toArray();
        // guarantee every user at least has ROLE_USER
        //$roles[] = 'ROLE_USER';
//        dd($roles);

        return ["ROLE_USER"];
    }

    public function __toString(): string
    {
        // to show the name of the Category in the select
//        return $this->name;
//         to show the id of the Category in the select
         return $this->id;
    }

    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getUsername()
    {
        // TODO: Implement getUsername() method.
    }

    public function __call($name, $arguments)
    {
        // TODO: Implement @method string getUserIdentifier()
    }
}
