<?php

namespace App\Entity;

use App\Repository\ProduitsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as assert;


#[ORM\Entity(repositoryClass: ProduitsRepository::class)]
class Produits
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(min:3,minMessage:"il faut min 3 caracteres")]
    #[Assert\NotBlank(message:"champ obligatoire")]
    

    private ?string $libelle = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(min:3,minMessage:"il faut min 3 caracteres")]
    #[Assert\NotBlank(message:"champ obligatoire")]
    private ?string $description = null;

    #[ORM\Column]
    #[Assert\Type("float")]
    #[Assert\NotBlank(message:"champ obligatoire")]
    private ?float $prix = null;

    #[ORM\ManyToOne(cascade:["persist","remove","merge"])] 
    private ?Categories $type = null;

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

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

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;

        return $this;
    }

    public function getType(): ?Categories
    {
        return $this->type;
    }

    public function setType(?Categories $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }
}
