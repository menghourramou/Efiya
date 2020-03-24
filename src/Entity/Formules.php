<?php

namespace App\Entity;//Entity équivaut à une table en BDD

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FormulesRepository")
 */
class Formules
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;// première propriété:id Formules

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $formule;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Reservation", mappedBy="formule")
     */
    private $reservations;

    /**
     * @ORM\Column(type="integer")
     */
    private $prix;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFormule(): ?string
    {
        return $this->formule;
    }

    public function setFormule(string $formule): self
    {
        $this->formule = $formule;

        return $this;
    }

    /**
     * @return Collection|Reservation[]
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): self
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations[] = $reservation;
            $reservation->setFormule($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->contains($reservation)) {
            $this->reservations->removeElement($reservation);
            // set the owning side to null (unless already changed)
            if ($reservation->getFormule() === $this) {
                $reservation->setFormule(null);
            }
        }

        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): self
    {
        $this->prix = $prix;

        return $this;
    }
}
