<?php

namespace App\Entity;

use App\Repository\EncargadoRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EncargadoRepository::class)]
class Encargado extends Persona
{
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "La relación con el paciente no puede estar vacía.")]
    #[Assert\Length(max: 255, maxMessage: "La relación no puede tener más de {{ limit }} caracteres.")]
    private ?string $relacionConPaciente = null;

    #[ORM\Column]
    private ?bool $esPrincipal = false;

    public function getRelacionConPaciente(): ?string
    {
        return $this->relacionConPaciente;
    }

    public function setRelacionConPaciente(string $relacionConPaciente): static
    {
        $this->relacionConPaciente = $relacionConPaciente;

        return $this;
    }

    public function isEsPrincipal(): ?bool
    {
        return $this->esPrincipal;
    }

    public function setEsPrincipal(bool $esPrincipal): static
    {
        $this->esPrincipal = $esPrincipal;

        return $this;
    }
}
