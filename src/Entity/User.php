<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank(message:"Email est Obligatoire")]
    #[Assert\Email(message:" l'email '{{ value }}' n'est pas conforme au format EMAIL ")]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column]
    #[Assert\Type("integer",message:"doit contenir que des chiffres")]
    #[Assert\Length(min:8,max:13,minMessage:"il faut Minimum 8 chiffres",maxMessage:"il faut Maximum 13 chiffres")]
    private ?int $telephone = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Ce champ est Obligatoire")]
    private ?string $adress = null;

    

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Assert\NotBlank(message:"Ce champ est Obligatoire")]
    private ?\DateTimeInterface $daten = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $datecr = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Ce champ est Obligatoire")]
    #[Assert\Regex(pattern:"/^[a-zA-Z\s]+$/",message:"ce Champ ne contient que des lettres")]
    #[Assert\Length(min:2,max:20,minMessage:"il faut 2 Lettres",maxMessage:"il faut Maximum 20 Lettres")]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Ce champ est Obligatoire")]
    #[Assert\Regex(pattern:"/^[a-zA-Z\s]+$/",message:"ce Champ ne contient que des lettres")]
    #[Assert\Length(min:2,max:20,minMessage:"il faut 2 Lettres",maxMessage:"il faut Maximum 20 Lettres")]
    private ?string $prenom = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $id_fiche = null;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private $isVerified = false;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Ce champ est Obligatoire")]
    private ?string $sexe = null;

    #[ORM\Column(type: 'boolean', nullable: true)]
    private ?bool $is_activated = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[ORM\ManyToOne(inversedBy: 'users',cascade:["persist","remove","merge"])]
    private ?Specialite $specialite = null;

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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getTelephone(): ?int
    {
        return $this->telephone;
    }

    public function setTelephone(int $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(string $adress): self
    {
        $this->adress = $adress;

        return $this;
    }

    public function getSpecialite(): ?Specialite
    {
        return $this->specialite;
    }

    public function setSpecialite(?Specialite $specialite): self
    {
        $this->specialite = $specialite;

        return $this;
    }

    public function getDaten(): ?\DateTimeInterface
    {
        return $this->daten;
    }

    public function setDaten(\DateTimeInterface $daten): self
    {
        $this->daten = $daten;

        return $this;
    }

    public function getDatecr(): ?\DateTimeInterface
    {
        return $this->datecr;
    }

    public function setDatecr(\DateTimeInterface $datecr): self
    {
        $this->datecr = $datecr;

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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getIdFiche(): ?string
    {
        return $this->id_fiche;
    }

    public function setIdFiche(?string $id_fiche): self
    {
        $this->id_fiche = $id_fiche;

        return $this;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(string $sexe): self
    {
        $this->sexe = $sexe;

        return $this;
    }

    public function isIsActivated(): ?bool
    {
        return $this->is_activated;
    }

    public function setIsActivated(bool $is_activated): self
    {
        $this->is_activated = $is_activated;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }
}
