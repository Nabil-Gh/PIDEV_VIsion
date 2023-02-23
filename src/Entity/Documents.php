<?php

namespace App\Entity;

use App\Repository\DocumentsRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: DocumentsRepository::class)]

class Documents
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"ce champs ne peut pas etre vide")]
    private ?string $fichier = null;


    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\ManyToOne(inversedBy: 'docs')]
    #[ORM\JoinColumn(nullable: false)]
    private ?FicheMedicale $fiche = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]


    #[Assert\Length(min:3,minMessage:"il faut un Minimum de 3 lettres")]
    
     private ?string $nom = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFichier(): ?string
    {
        return $this->fichier;
    }

    public function setFichier(string $fichier): self
    {
        $this->fichier = $fichier;

        return $this;
    }

    public function getFiche(): ?FicheMedicale
    {
        return $this->fiche;
    }

    public function setFiche(?FicheMedicale $fiche): self
    {
        $this->fiche = $fiche;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }
}
