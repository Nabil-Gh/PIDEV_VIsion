<?php

namespace App\Entity;

use App\Repository\RendezVousRepository;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Form\FormTypeInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RendezVousRepository::class)]
class RendezVous
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    
    #[ORM\Column(type: 'datetime_immutable')]
    #[Assert\NotBlank(message:"ce champs ne peut pas etre vide")]
    private ?\DateTimeInterface $date_rv = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"ce champs ne peut pas etre vide")]
    private ?string $type_rv = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $med = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $patient = null;

   

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDateRv(): ?\DateTimeInterface
    {
        return $this->date_rv;
    }

    public function setDateRv(\DateTimeInterface $date_rv): self
    {
        $this->date_rv = $date_rv;

        return $this;
    }

    public function getTypeRv(): ?string
    {
        return $this->type_rv;
    }

    public function setTypeRv(string $type_rv): self
    {
        $this->type_rv = $type_rv;

        return $this;
    }

    public function getMed(): ?User
    {
        return $this->med;
    }

    public function setMed(?User $med): self
    {
        $this->med = $med;

        return $this;
    }

    public function getPatient(): ?User
    {
        return $this->patient;
    }

    public function setPatient(?User $patient): self
    {
        $this->patient = $patient;

        return $this;
    }

    
}
