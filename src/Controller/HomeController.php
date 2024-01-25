<?php

namespace App\Controller;

use App\Validator\StronaPassword;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig');
    }

    #[Route('/contact-form', 'app_contact_form')]
    public function contactForm(Request $request): Response
    {
        $defaultData = ['message' => 'Wpisz treść'];

        $form = $this->createFormBuilder($defaultData)
            ->add('name', TextType::class)
            ->add('email', EmailType::class)
            ->add('phone', TelType::class)
            ->add('password', PasswordType::class, [
                'constraints' => [
                    new StronaPassword()
                ]
            ])
            ->add('message', TextareaType::class)
            ->add('send', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // data is an array with "name", "email", and "message" keys
            $data = $form->getData();

            dump($data);

            $this->redirectToRoute('app_home');
        }

        return $this->render('home/contact_form.html.twig', [
            'form' => $form
        ]);
    }
}
