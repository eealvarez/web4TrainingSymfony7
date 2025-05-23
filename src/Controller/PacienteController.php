<?php

namespace App\Controller;

use App\Entity\Paciente;
use App\Entity\Mama; // Para crear datos de ejemplo si es necesario
use App\Entity\Papa; // Para crear datos de ejemplo si es necesario
use App\Entity\Encargado; // Para crear datos de ejemplo si es necesario
use App\Form\PacienteTypeForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PacienteController extends AbstractController
{
    #[Route('/paciente/new', name: 'app_paciente_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $paciente = new Paciente();

        $form = $this->createForm(PacienteTypeForm::class, $paciente);

        $form->handleRequest($request);
        // $data = $request->request->all();
        // dd($form);
        // dump($data);

        // $testVariable = "Hola, Xdebug!";
        // dd($testVariable);
        // // $testArray = ["mensaje" => "Hola, Xdebug!", "numero" => 42];
        // dd($testVariable);

        // $data = $form->get('fechaNacimiento')->getData();
        // dump($data); // persiste los datos de un formulario en la base de datos pero captura o descarga los datos en la barra de depurador de Symfony

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($paciente);
            $entityManager->flush();

            $this->addFlash('success', 'Paciente creado exitosamente.');

            return $this->redirectToRoute('app_paciente_show', ['id' => $paciente->getId()]);
        }

        return $this->render('paciente/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/paciente/{id}/edit', name: 'app_paciente_edit')]
    public function edit(Request $request, Paciente $paciente, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PacienteTypeForm::class, $paciente);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Paciente actualizado exitosamente.');

            return $this->redirectToRoute('app_paciente_show', ['id' => $paciente->getId()]);
        }

        return $this->render('paciente/edit.html.twig', [
            'form' => $form,
            'paciente' => $paciente,
        ]);
    }

    #[Route('/paciente/{id}', name: 'app_paciente_show')]
    public function show(Paciente $paciente): Response
    {
        return $this->render('paciente/show.html.twig', [
            'paciente' => $paciente,
        ]);
    }

    #[Route('/paciente', name: 'app_paciente_index')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $pacientes = $entityManager->getRepository(Paciente::class)->findAll();

        return $this->render('paciente/index.html.twig', [
            'pacientes' => $pacientes,
        ]);
    }
}
