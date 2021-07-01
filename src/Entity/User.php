<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @method string getUserIdentifier()
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
     * @ORM\Column(type="string", length=320)
     */
    private $Email;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $Password;

    /**
     * @ORM\OneToMany(targetEntity=LicensePlate::class, mappedBy="license_plate")
     */
    private $license_plates;

    /**
     * @ORM\OneToOne(targetEntity=LicensePlate::class, mappedBy="user_id", cascade={"persist", "remove"})
     */
    private $user_id;

    public function __construct()
    {
        $this->license_plates = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->Email;
    }

    public function setEmail(string $Email): self
    {
        $this->Email = $Email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->Password;
    }

    public function setPassword(string $Password): self
    {
        $this->Password = $Password;

        return $this;
    }

    /**
     * @return Collection|LicensePlate[]
     */
    public function getLicensePlates(): Collection
    {
        return $this->license_plates;
    }

    public function addLicensePlate(LicensePlate $licensePlate): self
    {
        if (!$this->license_plates->contains($licensePlate)) {
            $this->license_plates[] = $licensePlate;
            $licensePlate->setLicensePlate($this);
        }

        return $this;
    }

    public function removeLicensePlate(LicensePlate $licensePlate): self
    {
        if ($this->license_plates->removeElement($licensePlate)) {
            // set the owning side to null (unless already changed)
            if ($licensePlate->getLicensePlate() === $this) {
                $licensePlate->setLicensePlate(null);
            }
        }

        return $this;
    }

    public function getUserId(): ?LicensePlate
    {
        return $this->user_id;
    }

    public function setUserId(?LicensePlate $user_id): self
    {
        // unset the owning side of the relation if necessary
        if ($user_id === null && $this->user_id !== null) {
            $this->user_id->setUserId(null);
        }

        // set the owning side of the relation if necessary
        if ($user_id !== null && $user_id->getUserId() !== $this) {
            $user_id->setUserId($this);
        }

        $this->user_id = $user_id;

        return $this;
    }

    public function getRoles()
    {
        // TODO: Implement getRoles() method.
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
