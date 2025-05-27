<?php

namespace App\Form;

use App\Entity\Mama;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class MamaTypeForm extends AbstractType // Extiende directamente AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Los campos de Persona se añadirán aquí directamente, ya que Mama extiende Persona
        // Symfony sabe que Mama es un Persona y Doctrine mapea los campos automáticamente.
        $builder
            ->add('nombre', TextType::class, [
                'label' => 'Nombre de la Mamá',
                'attr' => ['placeholder' => 'Ej: Ana'],
            ])
            ->add('apellido', TextType::class, [
                'label' => 'Apellido de la Mamá',
                'attr' => ['placeholder' => 'Ej: García'],
            ])
            ->add('fechaNacimiento', DateType::class, [
                'label' => 'Fecha de Nacimiento de la Mamá',
                'widget' => 'single_text',
                'html5' => true,
            ])
            ->add('dni', TextType::class, [
                'label' => 'DNI de la Mamá',
                'attr' => ['placeholder' => 'Ej: 98765432B'],
            ])
            ->add('direccion', TextType::class, [
                'label' => 'Dirección de la Mamá',
                'required' => false,
                'attr' => ['placeholder' => 'Ej: Av. Siempreviva 742'],
            ])
            ->add('telefono', TextType::class, [
                'label' => 'Teléfono de la Mamá',
                'required' => false,
                'attr' => ['placeholder' => 'Ej: +34 987 654 321'],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email de la Mamá',
                'required' => false,
                'attr' => ['placeholder' => 'Ej: ana.garcia@example.com'],
            ])
            // Campos específicos de Mama
            ->add('ocupacion', TextType::class, [
                'label' => 'Ocupación',
                'required' => false,
                'attr' => ['placeholder' => 'Ej: Ama de casa, Ingeniera'],
            ])
            ->add('estadoCivil', TextType::class, [
                'label' => 'Estado Civil',
                'required' => false,
                'attr' => ['placeholder' => 'Ej: Casada, Soltera'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Mama::class,
            'attr' => ['novalidate' => 'novalidate'],
        ]);
    }
}
