<?php

namespace App\Form;

use App\Entity\Encargado;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class EncargadoTypeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre', TextType::class, [
                'label' => 'Nombre del Encargado',
                'attr' => ['placeholder' => 'Ej: David'],
            ])
            ->add('apellido', TextType::class, [
                'label' => 'Apellido del Encargado',
                'attr' => ['placeholder' => 'Ej: López'],
            ])
            ->add('fechaNacimiento', DateType::class, [
                'label' => 'Fecha de Nacimiento del Encargado',
                'widget' => 'single_text',
                'html5' => true,
            ])
            ->add('dni', TextType::class, [
                'label' => 'DNI del Encargado',
                'attr' => ['placeholder' => 'Ej: 55667788D'],
            ])
            ->add('direccion', TextType::class, [
                'label' => 'Dirección del Encargado',
                'required' => false,
                'attr' => ['placeholder' => 'Ej: Calle del Sol 5'],
            ])
            ->add('telefono', TextType::class, [
                'label' => 'Teléfono del Encargado',
                'required' => false,
                'attr' => ['placeholder' => 'Ej: +34 789 012 345'],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email del Encargado',
                'required' => false,
                'attr' => ['placeholder' => 'Ej: david.lopez@example.com'],
            ])
            // Campos específicos de Encargado
            ->add('relacionConPaciente', TextType::class, [
                'label' => 'Relación con el Paciente',
                'attr' => ['placeholder' => 'Ej: Tío, Abuela, Tutor'],
            ])
            ->add('esPrincipal', CheckboxType::class, [
                'label' => '¿Es el Encargado Principal?',
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Encargado::class,
            'attr' => ['novalidate' => 'novalidate'],
        ]);
    }
}
