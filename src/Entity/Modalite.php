<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class Modalite
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
    private $titre;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $periodiciteEngagement;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $periodiciteReglement;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

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

    public function getPeriodiciteEngagement(): ?string
    {
        return $this->periodiciteEngagement;
    }

    public function setPeriodiciteEngagement(string $periodiciteEngagement): self
    {
        $this->periodiciteEngagement = $periodiciteEngagement;

        return $this;
    }

    public function getPeriodiciteReglement(): ?string
    {
        return $this->periodiciteReglement;
    }

    public function setPeriodiciteReglement(string $periodiciteReglement): self
    {
        $this->periodiciteReglement = $periodiciteReglement;

        return $this;
    }
}
