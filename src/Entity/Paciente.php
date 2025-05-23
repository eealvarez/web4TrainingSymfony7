<?php

namespace App\Entity;

use App\Repository\PacienteRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use App\Validator\Constraints as AppAssert; // Alias para nuestros validadores personalizados
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PacienteRepository::class)]
#[AppAssert\IsChild] // Aplica la restricción personalizada a la clase Paciente
class Paciente extends Persona
{
    #[ORM\Column(length: 50, unique: true)]
    #[Assert\NotBlank(message: "El número de expediente no puede estar vacío.")]
    #[Assert\Length(max: 50, maxMessage: "El número de expediente no puede tener más de {{ limit }} caracteres.")]
    private ?string $numeroExpediente = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    #[Assert\NotBlank(message: "La fecha de registro no puede estar vacía.")]
    private ?\DateTimeImmutable $fechaRegistro = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $diagnosticoPrincipal = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $observaciones = null;

    // Relaciones ManyToOne con Mama, Papa y Encargado
    // Un Paciente tiene UNA Mama, UN Papa, UN Encargado.
    // Una Mama/Papa/Encargado puede estar relacionado con MUCHOS Pacientes.
    #[ORM\ManyToOne(targetEntity: Mama::class)]
    #[ORM\JoinColumn(nullable: true)] // La relación es opcional
    private ?Mama $mama = null;

    #[ORM\ManyToOne(targetEntity: Papa::class)]
    #[ORM\JoinColumn(nullable: true)] // La relación es opcional
    private ?Papa $papa = null;

    #[ORM\ManyToOne(targetEntity: Encargado::class)]
    #[ORM\JoinColumn(nullable: true)] // La relación es opcional
    private ?Encargado $encargado = null;

    public function __construct()
    {
        $this->fechaRegistro = new \DateTimeImmutable(); // Establece la fecha de registro al crear el objeto
    }

    public function getNumeroExpediente(): ?string
    {
        return $this->numeroExpediente;
    }

    public function setNumeroExpediente(string $numeroExpediente): static
    {
        $this->numeroExpediente = $numeroExpediente;

        return $this;
    }

    public function getFechaRegistro(): ?\DateTimeImmutable
    {
        return $this->fechaRegistro;
    }

    public function setFechaRegistro(\DateTimeImmutable $fechaRegistro): static
    {
        $this->fechaRegistro = $fechaRegistro;

        return $this;
    }

    public function getDiagnosticoPrincipal(): ?string
    {
        return $this->diagnosticoPrincipal;
    }

    public function setDiagnosticoPrincipal(?string $diagnosticoPrincipal): static
    {
        $this->diagnosticoPrincipal = $diagnosticoPrincipal;

        return $this;
    }

    public function getObservaciones(): ?string
    {
        return $this->observaciones;
    }

    public function setObservaciones(?string $observaciones): static
    {
        $this->observaciones = $observaciones;

        return $this;
    }

    public function getMama(): ?Mama
    {
        return $this->mama;
    }

    public function setMama(?Mama $mama): static
    {
        $this->mama = $mama;

        return $this;
    }

    public function getPapa(): ?Papa
    {
        return $this->papa;
    }

    public function setPapa(?Papa $papa): static
    {
        $this->papa = $papa;

        return $this;
    }

    public function getEncargado(): ?Encargado
    {
        return $this->encargado;
    }

    public function setEncargado(?Encargado $encargado): static
    {
        $this->encargado = $encargado;

        return $this;
    }
}
