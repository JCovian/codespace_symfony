<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=60)
     */
    private $email;

    /**
     * @ORM\OneToMany(targetEntity=Coche::class, mappedBy="user", orphanRemoval=true)
     */
    private $coches;

    public function __construct()
    {
        $this->coches = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

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

    /**
     * @return Collection<int, Coche>
     */
    public function getCoches(): Collection
    {
        return $this->coches;
    }

    public function addCoche(Coche $coche): self
    {
        if (!$this->coches->contains($coche)) {
            $this->coches[] = $coche;
            $coche->setUser($this);
        }

        return $this;
    }

    public function removeCoche(Coche $coch): self
    {
        if ($this->coches->removeElement($coche)) {
            // set the owning side to null (unless already changed)
            if ($coche->getUser() === $this) {
                $coche->setUser(null);
            }
        }

        return $this;
    }
}
