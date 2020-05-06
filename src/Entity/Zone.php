<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ZoneRepository")
 */
class Zone
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
    private $name;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Street", mappedBy="zone")
     */
    private $street;

    public function __construct()
    {
        $this->street = new ArrayCollection();
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

    /**
     * @return Collection|Street[]
     */
    public function getStreet(): Collection
    {
        return $this->street;
    }

    public function addStreet(Street $street): self
    {
        if (!$this->street->contains($street)) {
            $this->street[] = $street;
            $street->setZone($this);
        }

        return $this;
    }

    public function removeStreet(Street $street): self
    {
        if ($this->street->contains($street)) {
            $this->street->removeElement($street);
            // set the owning side to null (unless already changed)
            if ($street->getZone() === $this) {
                $street->setZone(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return (string)$this->name;
    }
}
