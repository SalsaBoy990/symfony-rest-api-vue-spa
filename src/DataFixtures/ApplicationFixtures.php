<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Application;
use Faker\Factory;

class ApplicationFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < 20; $i++) {
            // set the application
            $jobApplication = new Application();

            $companyName = $faker->company();

            $jobApplication
                ->setCompany($companyName)
                ->setPosition($faker->jobTitle())
                ->setLocation($faker->city())
                ->setLink($faker->url())
                ->setEmail($faker->safeEmail())
                ->setPhoneNumber($faker->e164PhoneNumber())
                ->setSubject($faker->sentence())
                ->setMessage($faker->text(400))
                ->setNotes($faker->words(8, true))
                ->setApplicationDate()
                ->setSlug($this->slugify($companyName))
                ->setIsApplicationSent(random_int(0,1));

            $manager->persist($jobApplication);
        }

        $manager->flush();
    }

    public function slugify(string $string): string
    {
        return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $string), '-'));
    }
}
