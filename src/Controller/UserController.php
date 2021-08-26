<?php

namespace App\Controller;

use App\Entity\User;
use App\Event\UserRegisteredEvent;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type as Type;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/register", name="register")
     */
    public function register(Request $request,
                             EntityManagerInterface $entityManager,
                             UserPasswordHasherInterface $passwordHasher,
                             EventDispatcherInterface $eventDispatcher): Response
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

            $hashedPassword = $passwordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($hashedPassword);
            $entityManager->persist($user);

            $entityManager->flush();

            $eventDispatcher->dispatch(new UserRegisteredEvent($user), 'user_registered');
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

    /**
     * @Route("/login_check", name="login_check")
     */
    public function loginCheck(): Response
    {
        throw new \LogicException('Url needed by the security component');
    }

    /**
     * @Route("/logout", name="logout")
     */
    public function logout(): Response
    {
        throw new \LogicException('Url needed by the security component');
    }
}
