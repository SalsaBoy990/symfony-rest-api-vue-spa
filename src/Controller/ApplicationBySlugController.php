<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;
use App\Entity\Application;
use Doctrine\Persistence\ManagerRegistry;


#[AsController]
class ApplicationBySlugController extends AbstractController
{
    private $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function __invoke($slug)
    {
        $jobApplication = $this->doctrine
            ->getRepository(Application::class)
            ->findBy(
                ['slug' => $slug],
            );

        if (!$jobApplication) {
            throw $this->createNotFoundException(
                'No job application found for this slug'
            );
        }

        return $jobApplication;
    }
}
