<?php

namespace App\Form;

use App\Entity\Paciente;
use App\Entity\Mama;
use App\Entity\Papa;
use App\Entity\Encargado;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository; // Para el query_builder

class PacienteTypeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Campos heredados de Persona
            ->add('nombre', TextType::class, [
                'label' => 'Nombre del Paciente',
                'attr' => ['placeholder' => 'Ej: Juan'],
            ])
            ->add('apellido', TextType::class, [
                'label' => 'Apellido del Paciente',
                'attr' => ['placeholder' => 'Ej: Pérez'],
            ])
            ->add('fechaNacimiento', DateType::class, [
                'label' => 'Fecha de Nacimiento',
                'widget' => 'single_text', // Muestra un solo campo de texto para la fecha
                'html5' => true, // Usa el input de fecha HTML5 para calendarios nativos
                'help' => 'El paciente debe tener 13 años o menos.', // Ayuda visual para la validación de edad
                'empty_data' => null,


            ])
            ->add('dni', TextType::class, [
                'label' => 'DNI del Paciente',
                'attr' => ['placeholder' => 'Ej: 12345678A'],
            ])
            ->add('direccion', TextType::class, [
                'label' => 'Dirección',
                'required' => false,
                'attr' => ['placeholder' => 'Ej: Calle Falsa 123'],
            ])
            ->add('telefono', TextType::class, [
                'label' => 'Teléfono',
                'required' => false,
                'attr' => ['placeholder' => 'Ej: +34 123 456 789'],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'required' => false,
                'attr' => ['placeholder' => 'Ej: juan.perez@example.com'],
            ])

            // Campos específicos de Paciente
            ->add('numeroExpediente', TextType::class, [
                'label' => 'Número de Expediente',
                'attr' => ['placeholder' => 'Ej: EXP-001-2025'],
            ])
            // La fechaRegistro se establece en el constructor de la entidad, no se necesita en el formulario
            ->add('diagnosticoPrincipal', TextareaType::class, [
                'label' => 'Diagnóstico Principal',
                'required' => false,
                'attr' => ['rows' => 3, 'placeholder' => 'Breve descripción del diagnóstico...'],
            ])
            ->add('observaciones', TextareaType::class, [
                'label' => 'Observaciones',
                'required' => false,
                'attr' => ['rows' => 5, 'placeholder' => 'Notas adicionales sobre el paciente...'],
            ])

            // Campos para las relaciones (Mama, Papa, Encargado)
            // Usamos query_builder para asegurarnos de que solo se muestren las entidades correctas
            // (aunque Doctrine ya hace esto por el DiscriminatorMap, es una buena práctica para filtros adicionales)
            ->add('mama', EntityType::class, [
                'class' => Mama::class,
                'choice_label' => function (Mama $mama) {
                    return $mama->getNombre() . ' ' . $mama->getApellido() . ' (DNI: ' . $mama->getDni() . ')';
                },
                'placeholder' => 'Selecciona una Mamá (Opcional)',
                'required' => false,
                'empty_data' => null,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('m')
                        ->orderBy('m.nombre', 'ASC');
                },
            ])
            ->add('papa', EntityType::class, [
                'class' => Papa::class,
                'choice_label' => function (Papa $papa) {
                    return $papa->getNombre() . ' ' . $papa->getApellido() . ' (DNI: ' . $papa->getDni() . ')';
                },
                'placeholder' => 'Selecciona un Papá (Opcional)',
                'required' => false,
                'empty_data' => null,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('p')
                        ->orderBy('p.nombre', 'ASC');
                },
            ])
            ->add('encargado', EntityType::class, [
                'class' => Encargado::class,
                'choice_label' => function (Encargado $encargado) {
                    return $encargado->getNombre() . ' ' . $encargado->getApellido() . ' (DNI: ' . $encargado->getDni() . ')';
                },
                'placeholder' => 'Selecciona un Encargado (Opcional)',
                'required' => false,
                'empty_data' => null,
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('e')
                        ->orderBy('e.nombre', 'ASC');
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Paciente::class,
            // Deshabilita la validación HTML5 del navegador para que Symfony la maneje completamente
            'attr' => ['novalidate' => 'novalidate'],
        ]);
    }
}
