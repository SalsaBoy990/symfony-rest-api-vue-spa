<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

class RegistrationController extends AbstractController
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }


    #[Route('/register', name: 'app_registration')]
    public function index(ManagerRegistry $doctrine, Request $request): Response
    {
        // set the user
        $user = new User();
        $user
            ->setEmail('gulandras90@gmail.com')
            ->setRoles(['ROLE_USER']);

        // store hased password only (uses the "auto" hash)
        $plaintextPassword = 'password';
        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            $plaintextPassword
        );
        $user->setPassword($hashedPassword);

        $em = $doctrine->getManager();
        $em->persist($user);
        $em->flush();

        return new Response(
            'OK',
            Response::HTTP_OK,
            ['content-type' => 'text/html']
        );
    }
}
