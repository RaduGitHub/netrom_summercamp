<?php

namespace App\Entity;

use App\Repository\LicensePlateRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=LicensePlateRepository::class)
 */
class LicensePlate
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="license_plates")
     * @ORM\JoinColumn(nullable=false)
     * @ORM\Column (type="string", length=10)
     */
    private string $license_plate;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=true)
     * @ORM\Column(type="integer")
     */
    private ?int $user_id;

    public function getLicensePlate(): string
    {
        return $this->license_plate;
    }

    public function setLicensePlate(string $license_plate): self
    {
        $this->license_plate = $license_plate;

        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function __toString(): string{
        return $this->license_plate;
    }
}
