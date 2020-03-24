<?php
// src/Entity/Avis.php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use \Datetime;
/**
* @ORM\Entity()
* @ORM\Table(name="Avis")
* */
class Avis
{
    /**
    * @ORM\Id()
    * @ORM\GeneratedValue(strategy="AUTO")
    * @ORM\Column(type="integer")
    */
    private $id=0;

    /**
    * @ORM\Column(type="string")
    */
    private $auteurAvis = '';

    /**
    * @ORM\Column(type="string")
    */
    private $titreAvis= '';

    /**
    * @ORM\Column(type="string")
    */
    private $messageAvis= '';

    /**
    * @ORM\Column(type="datetime")
    */
    private $dateAvis  ;

    /**
    * @ORM\Column(type="integer")
    */
    private $nbEtoileAvis = 0;
    public function __construct()
    {
        $this->dateAvis = DateTime::createFromFormat("Y-m-d H:i:s", date("Y-m-d H:i:s"));
    }
    
    public function getId(): ?int
    {
        return $this->id; 
    }
    public function setId(?int $id)
    {
        $this->id = $id;
    }
    public function getAuteurAvis()
    {
        return $this->auteurAvis; 
    }
    public function setAuteurAvis($auteurAvis)
    {
        $this->auteurAvis = $auteurAvis;
    }
    public function getTitreAvis()
    {
        return $this->titreAvis; 
    }
    public function setTitreAvis($titreAvis)
    {
        $this->titreAvis = $titreAvis;
    }
    public function getMessageAvis()
    {
        return $this->messageAvis; 
    }
    public function setMessageAvis($messageAvis)
    {
        $this->messageAvis = $messageAvis;
    }

    public function getDateAvis()
    {
        return $this->dateAvis; 
    }

    public function setDateAvis($dateAvis)
    {
        $this->dateAvis = $dateAvis;
    }

    public function getNbEtoileAvis()
    {
        return $this->nbEtoileAvis; 
    }

    public function setNbEtoileAvis($nbEtoileAvis)
    {
        $this->nbEtoileAvis = $nbEtoileAvis;
    }
    // ...
}
