<?php

namespace App\Entity;

use App\Repository\SpecialiteRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SpecialiteRepository::class)]
class Specialite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(min:5,minMessage:"il faut Minimum 5 lettres")]
    #[Assert\Regex(pattern:"/^[a-zA-Z\s]+$/",message:"ce Champ ne contient que des lettres")]

    private ?string $namespe = null;

    #[ORM\OneToMany(mappedBy: 'specialite', targetEntity: User::class)]
    private Collection $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNamespe(): ?string
    {
        return $this->namespe;
    }

    public function setNamespe(string $namespe): self
    {
        $this->namespe = $namespe;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->setSpecialite($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getSpecialite() === $this) {
                $user->setSpecialite(null);
            }
        }

        return $this;
    }
}
