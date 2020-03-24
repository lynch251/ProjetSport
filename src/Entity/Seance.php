<?php

namespace App\Entity;

use App\Entity\Seance;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SeanceRepository")
 */
class Seance
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $intitule;


    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Utilisateur", inversedBy="seances")
     * @ORM\JoinColumn(nullable=false)
     */
    private $utilisateur;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Utilisation", mappedBy="seance")
     * @ORM\JoinColumn(nullable=true)
     */
    private $utilisations;


    public function __construct()
    {
        $this->utilisations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getIntitule(): ?string
    {
        return $this->intitule;
    }

    public function setIntitule(?string $intitule): self
    {
        $this->intitule = $intitule;

        return $this;
    }


    public function getUtilisateur(): ?Utilisateur
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(?Utilisateur $utilisateur): self
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    /**
     * @return Collection|Utilisation[]
     */
    public function getUtilisations(): Collection
    {
        return $this->utilisations;
    }

    public function addUtilisation(Utilisation $utilisation): self
    {
        if (!$this->utilisations->contains($utilisation)) {
            $this->utilisations[] = $utilisation;
            $utilisation->setSeance($this);
        }

        return $this;
    }

    public function removeUtilisation(Utilisation $utilisation): self
    {
        if ($this->utilisations->contains($utilisation)) {
            $this->utilisations->removeElement($utilisation);
            // set the owning side to null (unless already changed)
            if ($utilisation->getSeance() === $this) {
                $utilisation->setSeance(null);
            }
        }

        return $this;
    }

}
