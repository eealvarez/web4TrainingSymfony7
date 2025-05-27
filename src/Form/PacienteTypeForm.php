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
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormInterface;

// Asegúrate de importar tus Form Types de los familiares:
use App\Form\MamaTypeForm;
use App\Form\PapaTypeForm;
use App\Form\EncargadoTypeForm;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

class PacienteTypeForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {

        // Add ID field conditionally (for existing patients)
        if ($options['data'] instanceof Paciente && $options['data']->getId()) {
            $builder->add('id', HiddenType::class, [
                'mapped' => true,
                'required' => false,
            ]);
        }

        if ($options['data'] && $options['data']->getId()) { // Only add 'id' if data (Paciente object) exists and has an ID
            $builder->add('id', HiddenType::class, [
                'mapped' => true, // It maps to the 'id' property of the Paciente entity
                'required' => false, // ID is not required for a new patient
            ]);
        }
        if ($options['data'] && $options['data']->getId()) { // Only add 'id' if data (Paciente object) exists and has an ID
            $builder->add('id', HiddenType::class, [
                'mapped' => true, // It maps to the 'id' property of the Paciente entity
                'required' => false, // ID is not required for a new patient
            ]);
        }

        $builder
            ->add('nombre', TextType::class, ['label' => 'Nombre del Paciente', 'attr' => ['placeholder' => 'Ej: Juan']])
            ->add('apellido', TextType::class, ['label' => 'Apellido del Paciente', 'attr' => ['placeholder' => 'Ej: Pérez']])
            ->add('fechaNacimiento', DateType::class, ['label' => 'Fecha de Nacimiento del Paciente', 'widget' => 'single_text', 'html5' => true, 'empty_data' => null, 'help' => 'El paciente debe tener 13 años o menos.'])
            ->add('dni', TextType::class, ['label' => 'DNI del Paciente', 'attr' => ['placeholder' => 'Ej: 12345678A']])
            ->add('direccion', TextType::class, ['label' => 'Dirección', 'required' => false, 'attr' => ['placeholder' => 'Ej: Calle Falsa 123']])
            ->add('telefono', TextType::class, ['label' => 'Teléfono', 'required' => false, 'attr' => ['placeholder' => 'Ej: +34 123 456 789']])
            ->add('email', EmailType::class, ['label' => 'Email', 'required' => false, 'attr' => ['placeholder' => 'Ej: juan.perez@example.com']])
            ->add('numeroExpediente', TextType::class, ['label' => 'Número de Expediente', 'attr' => ['placeholder' => 'Ej: EXP-001-2025']])
            ->add('diagnosticoPrincipal', TextareaType::class, ['label' => 'Diagnóstico Principal', 'required' => false, 'attr' => ['rows' => 3, 'placeholder' => 'Breve descripción del diagnóstico...']])
            ->add('observaciones', TextareaType::class, ['label' => 'Observaciones', 'required' => false, 'attr' => ['rows' => 5, 'placeholder' => 'Notas adicionales sobre el paciente...']])

            ->add('familiar_tipo', ChoiceType::class, [
                'label' => 'Tipo de Familiar',
                'choices' => [
                    'No asignar' => 'none',
                    'Seleccionar Mamá existente' => 'existing_mama',
                    'Registrar Nueva Mamá' => 'new_mama',
                    'Seleccionar Papá existente' => 'existing_papa',
                    'Registrar Nuevo Papá' => 'new_papa',
                    'Seleccionar Encargado existente' => 'existing_encargado',
                    'Registrar Nuevo Encargado' => 'new_encargado',
                ],
                'mapped' => false,
                'required' => true,
                'placeholder' => 'Selecciona una opción',
            ]);

        // Estos formularios de "Nuevo Familiar" se añaden SIEMPRE a la estructura del formulario.
        // Su visibilidad será controlada por JavaScript en el frontend.
        $builder->add('newMama', MamaTypeForm::class, [
            'label' => 'Datos de la Nueva Mamá',
            'mapped' => false, // No se mapea directamente a Paciente
            'required' => false, // No es requerido si no se selecciona esta opción
        ]);
        $builder->add('newPapa', PapaTypeForm::class, [
            'label' => 'Datos del Nuevo Papá',
            'mapped' => false,
            'required' => false,
        ]);
        $builder->add('newEncargado', EncargadoTypeForm::class, [
            'label' => 'Datos del Nuevo Encargado',
            'mapped' => false,
            'required' => false,
        ]);

        $addFamiliarFields = function (FormInterface $form, ?string $familiarType, ?Paciente $paciente) {
            // Eliminar todos los campos relacionados con familiares existentes antes de añadir los correctos.
            // Esto es crucial para que solo el campo activo esté presente en el FormView.
            $form->remove('mama'); // Now we remove 'mama' (the embedded form)
            $form->remove('papa'); // And 'papa'
            $form->remove('encargado'); // And 'encargado'
            $form->remove('mama_select'); // Remove previous selects
            $form->remove('papa_select');
            $form->remove('encargado_select');


            switch ($familiarType) {
                case 'existing_mama':
                    if ($paciente && $paciente->getMama()) {
                        // Si el paciente ya tiene una mamá asociada, mostramos los detalles de esa mamá.
                        // El campo se llama 'mama' para que mapee directamente a $paciente->mama.
                        $form->add('mama', MamaTypeForm::class, [
                            'label' => 'Datos de la Mamá asociada',
                            'data' => $paciente->getMama(), // Precarga los datos de la mamá existente
                            'attr' => ['class' => 'existing-details-form'],
                            'disabled' => false, // Cambia a true si quieres solo lectura
                            'required' => false, // Los campos internos de MamaTypeForm pueden tener sus propios required
                            // 'mapped' is true by default and correct here, as 'mama' is a property of Paciente
                        ]);
                        // También añadimos el selector para cambiar la mamá, pero NO lo mapeamos directamente.
                        $form->add('mama_select', EntityType::class, [
                            'class' => Mama::class,
                            'choice_label' => fn(Mama $mama) => $mama->getNombre() . ' ' . $mama->getApellido() . ' (DNI: ' . $mama->getDni() . ')',
                            'placeholder' => 'Cambiar Mamá existente',
                            'required' => false,
                            'empty_data' => null,
                            'query_builder' => fn(EntityRepository $er) => $er->createQueryBuilder('m')->orderBy('m.nombre', 'ASC'),
                            'mapped' => false, // ¡IMPORTANTE! Este campo NO se mapea automáticamente a Paciente
                            'data' => $paciente->getMama(), // Precarga la mamá actual en el selector
                            'attr' => ['class' => 'existing-select-dropdown'],
                        ]);
                    } else {
                        // Si el paciente NO tiene una mamá asociada, mostramos solo el selector para asignar una.
                        $form->add('mama_select', EntityType::class, [
                            'class' => Mama::class,
                            'choice_label' => fn(Mama $mama) => $mama->getNombre() . ' ' . $mama->getApellido() . ' (DNI: ' . $mama->getDni() . ')',
                            'placeholder' => 'Selecciona una Mamá existente',
                            'required' => false,
                            'empty_data' => null,
                            'query_builder' => fn(EntityRepository $er) => $er->createQueryBuilder('m')->orderBy('m.nombre', 'ASC'),
                            'mapped' => false, // ¡IMPORTANTE! No se mapea automáticamente
                            'attr' => ['class' => 'existing-select-dropdown'],
                        ]);
                    }
                    break;
                case 'existing_papa':
                    if ($paciente && $paciente->getPapa()) {
                        $form->add('papa', PapaTypeForm::class, [ // Renamed from papa_details
                            'label' => 'Datos del Papá asociado',
                            'data' => $paciente->getPapa(),
                            'attr' => ['class' => 'existing-details-form'],
                            'disabled' => false,
                            'required' => false,
                        ]);
                        $form->add('papa_select', EntityType::class, [
                            'class' => Papa::class,
                            'choice_label' => fn(Papa $papa) => $papa->getNombre() . ' ' . $papa->getApellido() . ' (DNI: ' . $papa->getDni() . ')',
                            'placeholder' => 'Cambiar Papá existente',
                            'required' => false,
                            'empty_data' => null,
                            'query_builder' => fn(EntityRepository $er) => $er->createQueryBuilder('p')->orderBy('p.nombre', 'ASC'),
                            'mapped' => false,
                            'data' => $paciente->getPapa(),
                            'attr' => ['class' => 'existing-select-dropdown'],
                        ]);
                    } else {
                        $form->add('papa_select', EntityType::class, [
                            'class' => Papa::class,
                            'choice_label' => fn(Papa $papa) => $papa->getNombre() . ' ' . $papa->getApellido() . ' (DNI: ' . $papa->getDni() . ')',
                            'placeholder' => 'Selecciona un Papá existente',
                            'required' => false,
                            'empty_data' => null,
                            'query_builder' => fn(EntityRepository $er) => $er->createQueryBuilder('p')->orderBy('p.nombre', 'ASC'),
                            'mapped' => false,
                            'attr' => ['class' => 'existing-select-dropdown'],
                        ]);
                    }
                    break;
                case 'existing_encargado':
                    if ($paciente && $paciente->getEncargado()) {
                        $form->add('encargado', EncargadoTypeForm::class, [ // Renamed from encargado_details
                            'label' => 'Datos del Encargado asociado',
                            'data' => $paciente->getEncargado(),
                            'attr' => ['class' => 'existing-details-form'],
                            'disabled' => false,
                            'required' => false,
                        ]);
                        $form->add('encargado_select', EntityType::class, [
                            'class' => Encargado::class,
                            'choice_label' => fn(Encargado $encargado) => $encargado->getNombre() . ' ' . $encargado->getApellido() . ' (DNI: ' . $encargado->getDni() . ')',
                            'placeholder' => 'Cambiar Encargado existente',
                            'required' => false,
                            'empty_data' => null,
                            'query_builder' => fn(EntityRepository $er) => $er->createQueryBuilder('e')->orderBy('e.nombre', 'ASC'),
                            'mapped' => false,
                            'data' => $paciente->getEncargado(),
                            'attr' => ['class' => 'existing-select-dropdown'],
                        ]);
                    } else {
                        $form->add('encargado_select', EntityType::class, [
                            'class' => Encargado::class,
                            'choice_label' => fn(Encargado $encargado) => $encargado->getNombre() . ' ' . $encargado->getApellido() . ' (DNI: ' . $encargado->getDni() . ')',
                            'placeholder' => 'Selecciona un Encargado existente',
                            'required' => false,
                            'empty_data' => null,
                            'query_builder' => fn(EntityRepository $er) => $er->createQueryBuilder('e')->orderBy('e.nombre', 'ASC'),
                            'mapped' => false,
                            'attr' => ['class' => 'existing-select-dropdown'],
                        ]);
                    }
                    break;
            }
        };

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) use ($addFamiliarFields) {
                $form = $event->getForm();
                $paciente = $event->getData();
                $initialFamiliarType = $this->getInitialFamiliarType($paciente);
                $form->get('familiar_tipo')->setData($initialFamiliarType);
                $addFamiliarFields($form, $initialFamiliarType, $paciente);
            }
        );

        $builder->get('familiar_tipo')->addEventListener(
            FormEvents::POST_SUBMIT,
            function (FormEvent $event) use ($addFamiliarFields) {
                $form = $event->getForm()->getParent();
                $familiarType = $event->getForm()->getData();
                $paciente = $form->getData();
                $addFamiliarFields($form, $familiarType, $paciente);
            }
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Paciente::class,
            'attr' => ['novalidate' => 'novalidate'],
        ]);
    }

    private function getInitialFamiliarType(?Paciente $paciente): string
    {
        if (!$paciente || !$paciente->getId()) {
            return 'none';
        }
        if ($paciente->getMama()) {
            return 'existing_mama';
        }
        if ($paciente->getPapa()) {
            return 'existing_papa';
        }
        if ($paciente->getEncargado()) {
            return 'existing_encargado';
        }
        return 'none';
    }
}
