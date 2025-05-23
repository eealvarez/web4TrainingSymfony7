<?php

namespace App\Entity;

use App\Repository\PersonaRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PersonaRepository::class)]
#[ORM\InheritanceType("SINGLE_TABLE")] // Estrategia de herencia: todas las subclases en una tabla
#[ORM\DiscriminatorColumn(name: "discr", type: "string")] // Columna para diferenciar las subclases
#[ORM\DiscriminatorMap([
    "persona" => "Persona",
    "paciente" => "Paciente",
    "mama" => "Mama",
    "papa" => "Papa",
    "encargado" => "Encargado"
])] // Mapa de las subclases y sus valores en 'discr'
class Persona
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "El nombre no puede estar vacío.")]
    #[Assert\Length(max: 255, maxMessage: "El nombre no puede tener más de {{ limit }} caracteres.")]
    private ?string $nombre = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "El apellido no puede estar vacío.")]
    #[Assert\Length(max: 255, maxMessage: "El apellido no puede tener más de {{ limit }} caracteres.")]
    private ?string $apellido = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    #[Assert\NotBlank(message: "La fecha de nacimiento no puede estar vacía.")]
    #[Assert\LessThanOrEqual('today', message: "La fecha de nacimiento no puede ser en el futuro.")]
    private ?\DateTimeImmutable $fechaNacimiento = null;

    #[ORM\Column(length: 20, unique: true)]
    #[Assert\NotBlank(message: "El DNI no puede estar vacío.")]
    #[Assert\Length(max: 20, maxMessage: "El DNI no puede tener más de {{ limit }} caracteres.")]
    private ?string $dni = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(max: 255, maxMessage: "La dirección no puede tener más de {{ limit }} caracteres.")]
    private ?string $direccion = null;

    #[ORM\Column(length: 20, nullable: true)]
    #[Assert\Length(max: 20, maxMessage: "El teléfono no puede tener más de {{ limit }} caracteres.")]
    private ?string $telefono = null;

    #[ORM\Column(length: 255, unique: true, nullable: true)]
    #[Assert\Email(message: "El email '{{ value }}' no es un email válido.")]
    #[Assert\Length(max: 255, maxMessage: "El email no puede tener más de {{ limit }} caracteres.")]
    private ?string $email = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNombre(): ?string
    {
        return $this->nombre;
    }

    public function setNombre(string $nombre): static
    {
        $this->nombre = $nombre;

        return $this;
    }

    public function getApellido(): ?string
    {
        return $this->apellido;
    }

    public function setApellido(string $apellido): static
    {
        $this->apellido = $apellido;

        return $this;
    }

    public function getFechaNacimiento(): ?\DateTimeImmutable
    {
        return $this->fechaNacimiento;
    }

    public function setFechaNacimiento(\DateTimeImmutable $fechaNacimiento): static
    {
        $this->fechaNacimiento = $fechaNacimiento;

        return $this;
    }

    public function getDni(): ?string
    {
        return $this->dni;
    }

    public function setDni(string $dni): static
    {
        $this->dni = $dni;

        return $this;
    }

    public function getDireccion(): ?string
    {
        return $this->direccion;
    }

    public function setDireccion(?string $direccion): static
    {
        $this->direccion = $direccion;

        return $this;
    }

    public function getTelefono(): ?string
    {
        return $this->telefono;
    }

    public function setTelefono(?string $telefono): static
    {
        $this->telefono = $telefono;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }
}
