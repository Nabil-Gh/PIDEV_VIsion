<?php

namespace App\Entity;

use App\Repository\FicheMedicaleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FicheMedicaleRepository::class)]
class FicheMedicale
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    
    

    

    #[ORM\OneToMany(mappedBy: 'fiche', targetEntity: Documents::class, orphanRemoval: true)]
    private Collection $docs;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $patient = null;

    public function __construct()
    {
        $this->docs = new ArrayCollection();
    }

    

    public function getId(): ?int
    {
        return $this->id;
    }


    

    

    /**
     * @return Collection<int, Documents>
     */
    public function getDocs(): Collection
    {
        return $this->docs;
    }

    public function addDoc(Documents $doc): self
    {
        if (!$this->docs->contains($doc)) {
            $this->docs->add($doc);
            $doc->setFiche($this);
        }

        return $this;
    }

    public function removeDoc(Documents $doc): self
    {
        if ($this->docs->removeElement($doc)) {
            // set the owning side to null (unless already changed)
            if ($doc->getFiche() === $this) {
                $doc->setFiche(null);
            }
        }

        return $this;
    }

    public function getPatient(): ?User
    {
        return $this->patient;
    }

    public function setPatient(User $patient): self
    {
        $this->patient = $patient;

        return $this;
    }

    

   
}
