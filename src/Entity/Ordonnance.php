<?php

namespace App\Entity;

use App\Repository\OrdonnanceRepository;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: OrdonnanceRepository::class)]
class Ordonnance
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 25)]
    #[Assert\NotBlank(
        message:"doit etre no vide"
    )] 
    #[Assert\Length(
        min: 3,
        max: 50,
        minMessage: ' name must be at least {{ limit }} characters long',
        maxMessage: ' name cannot be longer than {{ limit }} characters',
    )]
    #[Assert\Regex(
        pattern: '/\d/',
        match: false,
        message: 'ne peut pas contenir des nombres',
    )]
    private ?string $nompatient = null;

    #[ORM\Column(length: 25)]
    #[Assert\NotBlank(
        message:"doit etre no vide"
    )] 
    #[Assert\Length(
        min: 3,
        max: 50,
        minMessage: ' name must be at least {{ limit }} characters long',
        maxMessage: 'name cannot be longer than {{ limit }} characters',
    )]
    #[Assert\Regex(
        pattern: '/\d/',
        match: false,
        message: 'ne peut pas contenir des nombres',
    )]
    private ?string $nommedecin = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(
        message:"doit etre no vide"
    )] 
    #[Assert\Length(
        min: 5,
        max: 50,
        minMessage: 'description must be at least {{ limit }} characters long',
        maxMessage: 'description name cannot be longer than {{ limit }} characters',
    )]
    
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(
        message:"doit etre no vide"
    )] 
    private ?string $medicament = null;

    #[ORM\Column(type :Types::DATE_IMMUTABLE)]
    #[Assert\NotBlank(
        message:"doit etre no vide"
    )]
    private ?DateTimeInterface $dateo = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    
    private ?Consultation $consultation = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomPatient(): ?string
    {
        return $this->nompatient;
    }

    public function setNomPatient(string $nompatient): self
    {
        $this->nompatient = $nompatient;

        return $this;
    }

    public function getNomMedecin(): ?string
    {
        return $this->nommedecin;
    }

    public function setNomMedecin(string $nommedecin): self
    {
        $this->nommedecin = $nommedecin;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getMedicament(): ?string
    {
        return $this->medicament;
    }

    public function setMedicament(string $medicament): self
    {
        $this->medicament = $medicament;

        return $this;
    }

    public function getDateO(): ?DateTimeInterface
    {
        return $this->dateo;
    }

    public function setDateO(\DateTimeInterface $dateo): self
    {
        $this->dateo = $dateo;

        return $this;
    }

    public function getConsultation(): ?Consultation
    {
        return $this->consultation;
    }

    public function setConsultation(?Consultation $consultation): self
    {
        $this->consultation = $consultation;

        return $this;
    }
}
