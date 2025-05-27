<?php

namespace App\Form;

use App\Entity\Papa;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class PapaTypeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nombre', TextType::class, [
                'label' => 'Nombre del Papá',
                'attr' => ['placeholder' => 'Ej: Carlos'],
            ])
            ->add('apellido', TextType::class, [
                'label' => 'Apellido del Papá',
                'attr' => ['placeholder' => 'Ej: Rodríguez'],
            ])
            ->add('fechaNacimiento', DateType::class, [
                'label' => 'Fecha de Nacimiento del Papá',
                'widget' => 'single_text',
                'html5' => true,
            ])
            ->add('dni', TextType::class, [
                'label' => 'DNI del Papá',
                'attr' => ['placeholder' => 'Ej: 11223344C'],
            ])
            ->add('direccion', TextType::class, [
                'label' => 'Dirección del Papá',
                'required' => false,
                'attr' => ['placeholder' => 'Ej: Calle Falsa 123'],
            ])
            ->add('telefono', TextType::class, [
                'label' => 'Teléfono del Papá',
                'required' => false,
                'attr' => ['placeholder' => 'Ej: +34 654 321 098'],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email del Papá',
                'required' => false,
                'attr' => ['placeholder' => 'Ej: carlos.rodriguez@example.com'],
            ])
            // Campos específicos de Papa
            ->add('ocupacion', TextType::class, [
                'label' => 'Ocupación',
                'required' => false,
                'attr' => ['placeholder' => 'Ej: Abogado, Empresario'],
            ])
            ->add('empresaTrabajo', TextType::class, [
                'label' => 'Empresa de Trabajo',
                'required' => false,
                'attr' => ['placeholder' => 'Ej: ABC S.L.'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Papa::class,
            'attr' => ['novalidate' => 'novalidate'],
        ]);
    }
}
