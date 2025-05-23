<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 * @Target({"CLASS"})
 */
#[\Attribute(\Attribute::TARGET_CLASS)] // Aplica esta restricción a la clase
class IsChild extends Constraint
{
    public string $message = 'El paciente debe tener 13 años o menos para ser registrado como niño.';

    public function getTargets(): string|array
    {
        // Esta restricción se aplica a la clase completa (CLASS_CONSTRAINT),
        // lo que permite al validador acceder a todas las propiedades del objeto Paciente.
        return self::CLASS_CONSTRAINT;
    }
}
