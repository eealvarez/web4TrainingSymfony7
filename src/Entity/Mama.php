<?php

namespace App\Entity;

use App\Repository\MamaRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: MamaRepository::class)]
class Mama extends Persona // Hereda de Persona
{
    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255, maxMessage: "La ocupación no puede tener más de {{ limit }} caracteres.")]
    private ?string $ocupacion = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Assert\Length(max: 50, maxMessage: "El estado civil no puede tener más de {{ limit }} caracteres.")]
    private ?string $estadoCivil = null;

    public function getOcupacion(): ?string
    {
        return $this->ocupacion;
    }

    public function setOcupacion(?string $ocupacion): static
    {
        $this->ocupacion = $ocupacion;

        return $this;
    }

    public function getEstadoCivil(): ?string
    {
        return $this->estadoCivil;
    }

    public function setEstadoCivil(?string $estadoCivil): static
    {
        $this->estadoCivil = $estadoCivil;

        return $this;
    }
}
