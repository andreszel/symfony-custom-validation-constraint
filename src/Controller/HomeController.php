<?php

namespace App\Controller;

use App\Validator\MobilePhone;
use App\Validator\StrongPassword;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\NotBlank;

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
        /* $defaultData = [
            'name' => 'Janusz',
            'message' => 'Wpisz treść'
        ]; */

        $form = $this->createFormBuilder()
            ->add('name', TextType::class)
            ->add('email', EmailType::class)
            ->add('phone', TelType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a phone number'
                    ]),
                    new MobilePhone(),
                ]
            ])
            ->add('password', PasswordType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password'
                    ]),
                    new StrongPassword(),
                ]
            ])
            ->add('message', TextareaType::class, [
                'attr' => [
                    'placeholder' => 'Wpisz swoją wiadomość'
                ]
            ])
            ->add('send', SubmitType::class, [
                'label' => '<i class="fa-solid fa-envelopes-bulk"></i> Wyślij formularz',
                'attr' => [
                    'class' => 'btn btn-primary'
                ],
                'label_html' => true,
            ])
            ->add('reset', ResetType::class, [
                'label' => '<i class="fa-solid fa-broom"></i> Reset',
                'attr' => [
                    'class' => 'btn btn-secondary'
                ],
                'label_html' => true,
            ])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // data is an array with "name", "email", and "message" keys
            $data = $form->getData();

            $this->addFlash('success', 'Dane przesłane z formularza przeszły walidację!');

            return $this->redirectToRoute('app_contact_form');
        }

        return $this->render('home/contact_form.html.twig', [
            'form' => $form
        ]);
    }
}
