<?php

namespace App\Entity;

use App\Repository\PapaRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PapaRepository::class)]
class Papa extends Persona
{
    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255, maxMessage: "La ocupación no puede tener más de {{ limit }} caracteres.")]
    private ?string $ocupacion = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255, maxMessage: "La empresa de trabajo no puede tener más de {{ limit }} caracteres.")]
    private ?string $empresaTrabajo = null;

    public function getOcupacion(): ?string
    {
        return $this->ocupacion;
    }

    public function setOcupacion(?string $ocupacion): static
    {
        $this->ocupacion = $ocupacion;

        return $this;
    }

    public function getEmpresaTrabajo(): ?string
    {
        return $this->empresaTrabajo;
    }

    public function setEmpresaTrabajo(?string $empresaTrabajo): static
    {
        $this->empresaTrabajo = $empresaTrabajo;

        return $this;
    }
}
