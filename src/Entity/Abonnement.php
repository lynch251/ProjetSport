<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Entity(repositoryClass="App\Repository\AbonnementRepository")
 */
class Abonnement
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
    private $dateDebut;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateFin;

    /**
     * @ORM\Column(type="datetime")
     */
    private $dateValidite;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Offre", inversedBy="abonnements")
     * @ORM\JoinColumn(nullable=false)
     */
    private $offre;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Utilisateur", inversedBy="abonnements")
     * @ORM\JoinColumn(nullable=false)
     */
    private $client;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Paiement", mappedBy="abonnement", orphanRemoval=true)
     */
    private $paiement;

    /**
     * @ORM\Column(type="boolean")
     */
    private $renouveler;

    private $etat;

    public function __construct()
    {
        $this->paiement = new ArrayCollection();
        $this->abonnement = false;

    }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getDateValidite(): ?\DateTimeInterface
    {
        return $this->dateValidite;
    }

    public function setDateValidite(\DateTimeInterface $dateValidite): self
    {
        $this->dateValidite = $dateValidite;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->dateDebut;
    }

    public function setDateDebut(\DateTimeInterface $dateDebut): self
    {
        $this->dateDebut = $dateDebut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->dateFin;
    }

    public function setDateFin(\DateTimeInterface $dateFin): self
    {
        $this->dateFin = $dateFin;

        return $this;
    }

    public function getOffre(): ?Offre
    {
        return $this->offre;
    }

    public function setOffre(?Offre $offre): self
    {
        $this->offre = $offre;

        return $this;
    }

    public function getClient(): ?Utilisateur
    {
        return $this->client;
    }

    public function setClient(?Utilisateur $client): self
    {
        $this->client = $client;

        return $this;
    }

    /**
     * @return Collection|Paiement[]
     */
    public function getPaiement(): Collection
    {
        return $this->paiement;
    }

    public function addPaiement(Paiement $paiement): self
    {
        if (!$this->paiement->contains($paiement)) {
            $this->paiement[] = $paiement;
            $paiement->setAbonnement($this);
        }

        return $this;
    }

    public function removePaiement(Paiement $paiement): self
    {
        if ($this->paiement->contains($paiement)) {
            $this->paiement->removeElement($paiement);
            // set the owning side to null (unless already changed)
            if ($paiement->getAbonnement() === $this) {
                $paiement->setAbonnement(null);
            }
        }

        return $this;
    }

    /**
     * @return int 0, si jamais de paiementUser, 1 si paiementUser en cours de validité, 2 si paiementUser pas actif, 3 si abonnementUser fin
     */
    public function getEtat():int {
        if(count($this->paiement)==0)
            return 0;
        if($this->dateFin >= date())
            return 3;
        if($this->dateValidite >= date())
            return 1;
        return 2;

    }


    public function getRenouveler():bool
    {
        if($this->renouveler == null)
            return false;
        return $this->renouveler;
    }

    public function setRenouveler(bool $renouveler): self
    {
        $this->renouveler = $renouveler;

        return $this;
    }


    public function getNomSignificatif():string {
        //Méthode ajoutée pour avoir un nom plus significatif
        //Une fois symfonyisée, elle s'appelera "<objet>.nomSignificatif" dans Twig ou "nomSignificatif" dans les forms.
        return $this->id." ".$this->getClient()->getNomUtilisateur()." ".$this->getClient()->getPrenomUtilisateur()." ".$this->getOffre()->getTitre();
    }
}
