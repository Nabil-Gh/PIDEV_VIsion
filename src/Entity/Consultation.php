<?php

namespace App\Entity;

use App\Repository\ConsultationRepository;
use DateTimeInterface;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ConsultationRepository::class)]
class Consultation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(
        message:"doit etre no vide"
    )] 
    #[Assert\Length(
        min: 5,
        max: 50,
        minMessage: 'Your adresse must be at least {{ limit }} characters long',
        maxMessage: 'Your adresse cannot be longer than {{ limit }} characters',
    )]
    private ?string $adresse = null;

    

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(
        message:"doit etre no vide"
    )]
    private ?string $typec = null;
    

    #[ORM\Column(type :Types::DATE_IMMUTABLE)]
    #[Assert\NotBlank(
        message:"doit etre no vide"
    )] 
    private ?DateTimeInterface $datec = null;
    

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(
        message:"doit etre no vide"
    )] 
     private ?string $timec = null;
     

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(
        message:"doit etre no vide"
    )] 
    private ?string $email = null;
    
    
    #[ORM\Column(length: 255)] 
    #[Assert\Length(
        min: 3,
        max: 50,
        
    )]
    
    #[Assert\Regex(
        pattern: '/\d/',
        match: false,
        message: 'ne peut pas contenir des nombres',
    )]
    private ?string $fname = null;
     
    
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(
        message:"doit etre no vide"
    )] 
    #[Assert\Length(
        min: 3,
        max: 50,
        minMessage: 'Your last name must be at least {{ limit }} characters long',
        maxMessage: 'Your last name cannot be longer than {{ limit }} characters',
    )]
    #[Assert\Regex(
        pattern: '/\d/',
        match: false,
        message: 'ne peut pas contenir des nombres',
    )]
    private ?string $lname = null;
    
    

   #[ORM\Column(length: 255)]
   #[Assert\NotBlank(
    message:"doit etre no vide"
    )] 
    #[Assert\Length(
        min: 8,
        max: 8,
        minMessage: 'Your phone number must be  {{ limit }} numbers ',
        maxMessage: 'Your phone number must be  {{ limit }} numbers',
    )]
    private ?string $phone = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getTypeC(): ?string
    {
        return $this->typec;
    }

    public function setTypeC(string $typec): self
    {
        $this->typec = $typec;

        return $this;
    }

    public function getDateC(): ?DateTimeInterface
    {
        return $this->datec;
    }

    public function setDateC(\DateTimeInterface $datec): self
    {
        $this->datec = $datec;

        return $this;
    }

    public function getTimeC(): ?string
    {
        return $this->timec;
    }

    public function setTimeC(string $timec): self
    {
        $this->timec = $timec;

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

    public function getFname(): ?string
    {
        return $this->fname;
    }

    public function setFname(string $fname): self
    {
        $this->fname = $fname;

        return $this;
    }

    public function getLname(): ?string
    {
        return $this->lname;
    }

    public function setLname(string $lname): self
    {
        $this->lname = $lname;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    
}
