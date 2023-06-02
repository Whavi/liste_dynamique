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
    public function index(Request $request): Response
    {
       $form = $this->createFormBuilder(['Age' => 12])
                                      
        ->add("Name", TextType::class, [
            'constraints' => [new NotBlank(['message' => 'Please enter your name.']),
        ]])

        ->add(("Age"), IntegerType::class)


        ->add("Pays", EntityType::class, [
            'placeholder' => "Choissiez un pays",
            'class' => Pays::class,
            'choice_label' => fn(Pays $pays) => $pays->getName(),   
            'query_builder' => fn(PaysRepository $paysRepository) => $paysRepository->createQueryBuilder("p")->orderBy('p.name', 'ASC'),
            'constraints' => new NotBlank(['message' => 'Please enter your country.']),
        ])


        ->add("Ville", EntityType::class, [
            'placeholder' => "Choissiez une ville",
            'class' => Ville::class,
            //'disabled' => 'true',
            'choice_label' => fn(Ville $villes) => $villes->getName(), 
            'query_builder' => fn(VilleRepository $villeRepository) => $villeRepository->createQueryBuilder("v")->orderBy('v.name', 'ASC'),
            'constraints' => new NotBlank(['message' => 'Please enter your city.']),


        ])
        ->add("Message", TextareaType::class, [
            'constraints' => [new NotBlank (['message' => 'Please enter your message.']),
                             new Length(['min' => 5]),
        ]])

        ->add("Debut_du_pret", DateType::class,[
            'widget' => 'single_text',
            'constraints' => new NotBlank(['message' => 'Please enter datetime.']),
        ])
        ->add("Fin_du_pret", DateType::class,[
            'widget' => 'single_text',
            'constraints' => new NotBlank(['message' => 'Please enter datetime.']),
        ])


        ->addEventListener(FormEvents::POST_SET_DATA, function(FormEvent $event){
            $age = $event->getData()['Age'] ?? null;
            $event->setData(['Age' => $age - 4 ]);

            if ($age == ! null && $age > 18 ){
                $event->getForm()->add("Departement", TextType::class, [
                    'constraints' => new Length(['max' => 5, "min" => 5]),
                ]);
                $event->getForm()->remove("Message");

            }
        })
    
        ->getForm()
        ;
        
        $form->handleRequest($request);

        if($form->isSubmitted()&& $form->isValid()){
            dd("Formulaire Valide");
        }
        
        return $this->renderForm('home.html.twig', compact('form'));
    }
}
