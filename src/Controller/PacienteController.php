<?php

namespace App\Controller;

use App\Entity\Paciente;
use App\Entity\Mama;
use App\Entity\Papa;
use App\Entity\Encargado;
use App\Form\EncargadoTypeForm;
use App\Repository\PacienteRepository;
use App\Form\MamaTypeForm;
use App\Form\PacienteTypeForm;
use App\Form\PapaTypeForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

class PacienteController extends AbstractController
{

    /**
     * Muestra la lista de todos los pacientes.
     */
    #[Route('/paciente', name: 'app_paciente_index', methods: ['GET'])]
    public function index(PacienteRepository $pacienteRepository): Response
    {
        return $this->render('paciente/index.html.twig', [
            'pacientes' => $pacienteRepository->findAll(),
        ]);
    }

    #[Route('/paciente/new', name: 'app_paciente_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $paciente = new Paciente();
        $form = $this->createForm(PacienteTypeForm::class, $paciente);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $familiarType = $form->get('familiar_tipo')->getData();

            // Always reset existing relationships first to ensure only the selected one is set.
            // This prevents old relationships from persisting if a new type is chosen.
            $paciente->setMama(null);
            $paciente->setPapa(null);
            $paciente->setEncargado(null);

            switch ($familiarType) {
                case 'new_mama':
                    $newMama = $form->get('newMama')->getData();
                    if ($newMama instanceof Mama) { // Always check type safety
                        $entityManager->persist($newMama);
                        $paciente->setMama($newMama);
                    }
                    break;
                case 'existing_mama':
                    // In 'new' action, 'mama' (embedded form) will not be present
                    // if there's no initial mama. It will only be 'mama_select'.
                    if ($form->has('mama_select')) { // Check if the select field exists
                        $selectedMama = $form->get('mama_select')->getData();
                        if ($selectedMama instanceof Mama) { // Ensure it's a Mama object
                            $paciente->setMama($selectedMama);
                        }
                    }
                    break;
                case 'new_papa':
                    $newPapa = $form->get('newPapa')->getData();
                    if ($newPapa instanceof Papa) {
                        $entityManager->persist($newPapa);
                        $paciente->setPapa($newPapa);
                    }
                    break;
                case 'existing_papa':
                    if ($form->has('papa_select')) {
                        $selectedPapa = $form->get('papa_select')->getData();
                        if ($selectedPapa instanceof Papa) {
                            $paciente->setPapa($selectedPapa);
                        }
                    }
                    break;
                case 'new_encargado':
                    $newEncargado = $form->get('newEncargado')->getData();
                    if ($newEncargado instanceof Encargado) {
                        $entityManager->persist($newEncargado);
                        $paciente->setEncargado($newEncargado);
                    }
                    break;
                case 'existing_encargado':
                    if ($form->has('encargado_select')) {
                        $selectedEncargado = $form->get('encargado_select')->getData();
                        if ($selectedEncargado instanceof Encargado) {
                            $paciente->setEncargado($selectedEncargado);
                        }
                    }
                    break;
                case 'none':
                default:
                    // Relations are already set to null at the start of the if block.
                    break;
            }

            $entityManager->persist($paciente);
            $entityManager->flush();

            $this->addFlash('success', 'Paciente creado exitosamente.');
            return $this->redirectToRoute('app_paciente_show', ['id' => $paciente->getId()]);
        }

        return $this->render('paciente/new.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * Muestra los detalles de un paciente específico.
     */
    #[Route('/paciente/{id}', name: 'app_paciente_show', methods: ['GET'])]
    public function show(Paciente $paciente): Response
    {
        return $this->render('paciente/show.html.twig', [
            'paciente' => $paciente,
        ]);
    }

    #[Route('/paciente/{id}/edit', name: 'app_paciente_edit')]
    public function edit(Request $request, Paciente $paciente, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PacienteTypeForm::class, $paciente);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $familiarType = $form->get('familiar_tipo')->getData();

            // Capture the current familiar objects BEFORE setting them to null.
            // This is important because the embedded forms (e.g., 'mama') modify
            // these objects directly if they are loaded.
            $currentMama = $paciente->getMama();
            $currentPapa = $paciente->getPapa();
            $currentEncargado = $paciente->getEncargado();

            // Always reset relations to ensure only the chosen one persists.
            $paciente->setMama(null);
            $paciente->setPapa(null);
            $paciente->setEncargado(null);

            switch ($familiarType) {
                case 'new_mama':
                    $newMama = $form->get('newMama')->getData();
                    if ($newMama instanceof Mama) {
                        $entityManager->persist($newMama);
                        $paciente->setMama($newMama);
                    }
                    break;
                case 'existing_mama':
                    // Scenario 1: Patient *had* a mama, and we're editing her details.
                    // The embedded 'mama' form (MamaTypeForm) will be present.
                    if ($form->has('mama')) { // Check if the embedded MamaTypeForm exists
                        // The $currentMama object has already been updated by form->handleRequest().
                        // We just need to re-assign it to the patient.
                        if ($currentMama instanceof Mama) {
                            $paciente->setMama($currentMama);
                        }
                    }

                    // Scenario 2: User might have used the 'mama_select' dropdown to change the mama.
                    // This takes precedence over the embedded details if a new selection is made.
                    if ($form->has('mama_select')) {
                        $selectedMama = $form->get('mama_select')->getData();
                        if ($selectedMama instanceof Mama) { // If a new mama was selected from the dropdown
                            $paciente->setMama($selectedMama);
                        }
                        // If mama_select exists but is null, it means the user might have cleared the selection
                        // or it was previously empty. Our initial nulling handles this.
                    }
                    break;
                case 'new_papa':
                    $newPapa = $form->get('newPapa')->getData();
                    if ($newPapa instanceof Papa) {
                        $entityManager->persist($newPapa);
                        $paciente->setPapa($newPapa);
                    }
                    break;
                case 'existing_papa':
                    if ($form->has('papa')) {
                        if ($currentPapa instanceof Papa) {
                            $paciente->setPapa($currentPapa);
                        }
                    }
                    if ($form->has('papa_select')) {
                        $selectedPapa = $form->get('papa_select')->getData();
                        if ($selectedPapa instanceof Papa) {
                            $paciente->setPapa($selectedPapa);
                        }
                    }
                    break;
                case 'new_encargado':
                    $newEncargado = $form->get('newEncargado')->getData();
                    if ($newEncargado instanceof Encargado) {
                        $entityManager->persist($newEncargado);
                        $paciente->setEncargado($newEncargado);
                    }
                    break;
                case 'existing_encargado':
                    if ($form->has('encargado')) {
                        if ($currentEncargado instanceof Encargado) {
                            $paciente->setEncargado($currentEncargado);
                        }
                    }
                    if ($form->has('encargado_select')) {
                        $selectedEncargado = $form->get('encargado_select')->getData();
                        if ($selectedEncargado instanceof Encargado) {
                            $paciente->setEncargado($selectedEncargado);
                        }
                    }
                    break;
                case 'none':
                default:
                    // Relations are already set to null at the start of the if block.
                    break;
            }

            $entityManager->flush();

            $this->addFlash('success', 'Paciente actualizado exitosamente.');
            return $this->redirectToRoute('app_paciente_show', ['id' => $paciente->getId()]);
        }

        return $this->render('paciente/edit.html.twig', [
            'form' => $form,
            'paciente' => $paciente,
        ]);
    }

    #[Route('/paciente/familiar-fields', name: 'app_paciente_familiar_fields', methods: ['POST'])]
    public function familiarFields(Request $request, EntityManagerInterface $entityManager): Response
    {
        $paciente = new Paciente();
        // Para el caso de edición, si necesitas rehidratar el paciente para el AJAX,
        // asegúrate de que el ID del paciente se envía en el form data del AJAX.
        $pacienteId = $request->request->all('paciente')['id'] ?? null; // Usa all('paciente') para obtener los sub-datos de 'paciente'
        if ($pacienteId) {
            $paciente = $entityManager->getRepository(Paciente::class)->find($pacienteId) ?? new Paciente();
        }

        $form = $this->createForm(PacienteTypeForm::class, $paciente);
        $form->handleRequest($request); // Maneja la request para que los datos unmapped se rehidraten

        // Renderizamos TODAS las partes posibles de los familiares en el AJAX.
        // La visibilidad será controlada por JavaScript.
        return $this->render('paciente/_familiar_fields_ajax.html.twig', [
            'form' => $form->createView(), // Pasamos la vista del formulario completo
        ]);
    }
}
