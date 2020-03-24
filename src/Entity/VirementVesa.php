<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 */
class VirementVesa
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Paiement", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $idPaiement;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $numeroVirement;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdPaiement(): ?Paiement
    {
        return $this->idPaiement;
    }

    public function setIdPaiement(Paiement $idPaiement): self
    {
        $this->idPaiement = $idPaiement;

        return $this;
    }

    public function getNumeroVirement(): ?string
    {
        return $this->numeroVirement;
    }

    public function setNumeroVirement(string $numeroVirement): self
    {
        $this->numeroVirement = $numeroVirement;

        return $this;
    }
}
