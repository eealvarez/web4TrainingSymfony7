<?php

namespace App\Validator\Constraints;

use App\Entity\Paciente; // Importa la entidad Paciente para el tipo de valor
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class IsChildValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof IsChild) {
            throw new UnexpectedTypeException($constraint, IsChild::class);
        }

        // Si el valor es nulo, no validamos aquí. Otras restricciones (NotBlank) deberían manejarlo.
        if (null === $value) {
            return;
        }

        // Asegurarse de que estamos validando una instancia de Paciente
        if (!$value instanceof Paciente) {
            throw new UnexpectedValueException($value, Paciente::class);
        }

        $fechaNacimiento = $value->getFechaNacimiento();

        // Si la fecha de nacimiento es nula, otra restricción debería manejarlo.
        if (null === $fechaNacimiento) {
            return;
        }

        $today = new \DateTimeImmutable();
        // Calcular la edad en años
        $age = $fechaNacimiento->diff($today)->y;

        // Si la edad es mayor a 13, añadimos una violación
        if ($age > 13) {
            $this->context->buildViolation($constraint->message)
                // Asociamos el error a la propiedad 'fechaNacimiento'
                // para que el formulario lo muestre junto a ese campo específico.
                ->atPath('fechaNacimiento')
                ->addViolation();
        }
    }
}
