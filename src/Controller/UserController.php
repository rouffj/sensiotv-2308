<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type as Type;
use Doctrine\ORM\EntityManagerInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/register", name="register")
     */
    public function register(Request $request, EntityManagerInterface $entityManager): Response
    {
        $registerForm = $this->createForm(UserType::class, null, [
            'validation_groups' => ['USER_ADD', 'Default']
        ]);
        //$registerForm->remove('phone');
        $registerForm->add('submit', Type\SubmitType::class, [
            'label' =>'Create your SensioTV account'
        ]);

        $registerForm->handleRequest($request);

        if ($registerForm->isSubmitted() && $registerForm->isValid()) {
            /** @var User $user */
            $user = $registerForm->getData();

            $entityManager->persist($user);


            $entityManager->flush();

            dump($user);
        }

        return $this->render('user/register.html.twig', [
            'register_form' => $registerForm->createView(),
        ]);
    }

    /**
     * @Route("/login", name="login")
     */
    public function login(): Response
    {
        return $this->render('user/login.html.twig');
    }
}
