<?php

namespace App\Controller;

use App\Entity\Pays;
use App\Entity\Ville;
use App\Repository\PaysRepository;
use App\Repository\VilleRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(Request $request, PaysRepository $paysRepository, VilleRepository $villeRepository): Response
    {
        $form = $this->createFormBuilder(['Pays' => $paysRepository->find(2)])

            ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($villeRepository) {
                $pays = $event->getData()['Pays'] ?? null;

                $villes = $pays === null ? [] : $villeRepository->findByPays($pays, ['name' => 'ASC']);

                $event->getForm()->add("Ville", EntityType::class, [
                    'placeholder' => "Choissiez une ville",
                    'class' => Ville::class,
                    'disabled' => $pays === null,
                    'choice_label' => 'name',
                    'choices' => $villes,
                    'constraints' => new NotBlank(['message' => 'Veuillez choisir une ville.']),

                ]);
            })


            ->add("Name", TextType::class, [
                'constraints' => [new NotBlank(['message' => 'Please enter your name.']),]
            ])

            ->add(("Age"), IntegerType::class)

            ->add("Pays", EntityType::class, [
                'placeholder' => "Choissiez un pays",
                'class' => Pays::class,
                'choice_label' => fn (Pays $pays) => $pays->getName(),
                'query_builder' => fn (PaysRepository $paysRepository) => $paysRepository->createQueryBuilder("p")->orderBy('p.name', 'ASC'),
                'constraints' => new NotBlank(['message' => 'Please enter your country.']),
            ])


            ->add("Message", TextareaType::class, [
                'constraints' => [
                    new NotBlank(['message' => 'Please enter your message.']),
                    new Length(['min' => 5]),
                ]
            ])

            ->add("Debut_du_pret", DateType::class, [
                'widget' => 'single_text',
                'constraints' => new NotBlank(['message' => 'Please enter datetime.']),
            ])

            ->add("Fin_du_pret", DateType::class, [
                'widget' => 'single_text',
                'constraints' => new NotBlank(['message' => 'Please enter datetime.']),
            ])

            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            dd("Formulaire Valide");
        }

        return $this->renderForm('home.html.twig', compact('form'));
    }
}
