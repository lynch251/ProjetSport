<?php namespace App\Entity;

use App\Entity\Abonnement;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
* @ORM\Entity()
* @ORM\Table(name="Utilisateur")
*
*/
class Utilisateur implements UserInterface
{


    /**
    * @ORM\Id
    * @ORM\GeneratedValue
    * @ORM\Column(type="integer")
    */
    private $id;

    /**
    * @ORM\Column(type="string", unique=true)
    * @Assert\NotBlank()
    */
    private $login;

    /**
    * @ORM\Column(type="string" )
    * @Assert\NotBlank()
    * @Assert\Length(min=2, max=50)
    */
    private $nomUtilisateur;

    /**
    * @ORM\Column(type="string")
    * @Assert\NotBlank()
    * @Assert\Length(min=2, max=50)
    */
    private $prenomUtilisateur;

    /**
    * @ORM\Column(type="string", unique=true )
    * @Assert\Email()
    */
    private $email;

    /**
    * @ORM\Column(type="string")
    * @Assert\NotBlank()
    */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Abonnement", mappedBy="client", orphanRemoval=true)
     */
    private $abonnements;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\RoleUtilisateur", inversedBy="utilisateurs")
     */
    private $role;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Seance", mappedBy="utilisateur")
     */
    private $seances;


    /**
    * Utilisateur constructor.
    */
    public function __construct()
    {
    $this->id = -1;
    $this->login = "";
    $this->nomUtilisateur = "";
    $this->prenomUtilisateur = "";
    $this->email = "";
    $this->password = "";
    $this->roles = new RoleUtilisateur();
    $this->abonnements = new ArrayCollection();
    $this->seances = new ArrayCollection();

    }

    public function getId(): int
    {
    return $this->id;
    }

    public function setLogin(string $login): void
    {
    $this->login = $login;
    }

    public function getLogin(): string
    {
    return $this->login;
    }

    public function getNomprenom(): string
    {
        return $this->nomUtilisateur." ".$this->prenomUtilisateur;
    }

    public function getNomUtilisateur(): string
    {
    return $this->nomUtilisateur;
    }

    public function setNomUtilisateur(string $nomUtilisateur): void
    {
    $this->nomUtilisateur = $nomUtilisateur;
    }

    public function getPrenomUtilisateur(): string
    {
    return $this->prenomUtilisateur;
    }

    public function setPrenomUtilisateur(string $prenomUtilisateur): void
    {
    $this->prenomUtilisateur = $prenomUtilisateur;
    }


    /*
    * Méthode obligatoire pour répondre aux besoins de l'héritage à userInterface
    */
    public function getUserName(): string
    {
    return $this->login;
    }



    public function getEmail(): string
    {
    return $this->email;
    }

    public function setEmail(string $email): void
    {
    $this->email = $email;
    }

    /*
    * Méhtode héritée de UserInterface
    */
    public function getPassword(): string
    {
    return $this->password;
    }

    public function setPassword(string $password): void
    {
    $this->password = $password;
    }

    /*
    * Méhtode héritée de UserInterface
    */
    public function getRoles(): array
    {
        $role = $this->getRole()->getTitreSymfony();

        // il est obligatoire d'avoir au moins un rôle si on est authentifié, par convention c'est ROLE_USER
        if (empty($role)) {
            $role = 'ROLE_USER';
        }
        $roles[] = $role;

    return array_unique($roles);
    }

    /*
    * Méhtode héritée de UserInterface
    */
    public function getSalt(): ?string
    {
    return null;
    }

    /*
    * Méhtode héritée de UserInterface
    */
    public function eraseCredentials(): void
    {
    }

    /**
     * @return Collection|Abonnement[]
     */
    public function getAbonnements(): Collection
    {
        return $this->abonnements;
    }

    public function addAbonnement(Abonnement $abonnement): self
    {
        if (!$this->abonnements->contains($abonnement)) {
            $this->abonnements[] = $abonnement;
            $abonnement->setClient($this);
        }

        return $this;
    }

    public function removeAbonnement(Abonnement $abonnement): self
    {
        if ($this->abonnements->contains($abonnement)) {
            $this->abonnements->removeElement($abonnement);
            // set the owning side to null (unless already changed)
            if ($abonnement->getClient() === $this) {
                $abonnement->setClient(null);
            }
        }

        return $this;
    }

    public function getRole(): ?RoleUtilisateur
    {
        return $this->role;
    }

    public function setRole(?RoleUtilisateur $role): self
    {
        $this->role = $role;

        return $this;
    }

    /**
     * @return Collection|Seance[]
     */
    public function getSeances(): Collection
    {
        return $this->seances;
    }

    public function addSeance(Seance $seance): self
    {
        if (!$this->seances->contains($seance)) {
            $this->seances[] = $seance;
            $seance->setUtilisateur($this);
        }

        return $this;
    }

    public function removeSeance(Seance $seance): self
    {
        if ($this->seances->contains($seance)) {
            $this->seances->removeElement($seance);
            // set the owning side to null (unless already changed)
            if ($seance->getUtilisateur() === $this) {
                $seance->setUtilisateur(null);
            }
        }

        return $this;
    }


}