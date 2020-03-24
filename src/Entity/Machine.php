<?php

namespace App\Entity;

use App\Controller\MachineController;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MachineRepository")
 */
class Machine
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $nom;


    /**
     * @ORM\Column(type="string", length=255)
     */
    private $uniteDuree;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $uniteIntensite;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Utilisation", mappedBy="machines")
     */
    private $utilisations;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */

    private $logo;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * Fichier reconstitué à partir de la chaine de caractère
     */
    private $logoFile;


    public function __construct()
    {
        $this->utilisations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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


    public function getUniteDuree(): ?string
    {
        return $this->uniteDuree;
    }

    public function setUniteDuree(string $uniteDuree): self
    {
        $this->uniteDuree = $uniteDuree;

        return $this;
    }

    public function getUniteIntensite(): ?string
    {
        return $this->uniteIntensite;
    }

    public function setUniteIntensite(string $uniteIntensite): self
    {
        $this->uniteIntensite = $uniteIntensite;

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
            $utilisation->setMachines($this);
        }

        return $this;
    }

    public function removeUtilisation(Utilisation $utilisation): self
    {
        if ($this->utilisations->contains($utilisation)) {
            $this->utilisations->removeElement($utilisation);
            // set the owning side to null (unless already changed)
            if ($utilisation->getMachines() === $this) {
                $utilisation->setMachines(null);
            }
        }

        return $this;
    }

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): self
    {
        $this->logo = $logo;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getLogoFile(): ?File
    {
        return $this->logoFile;
    }

    public function setLogoFile(?File $file):self
    {
        $this->logoFile = $file;
        return $this;
    }



}
