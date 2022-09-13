<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use App\Entity\Application;
use App\Service\FileUploader;

#[AsController]
final class ApplicationCoverController extends AbstractController
{
    public function __invoke(Request $request, FileUploader $fileUploader): Application
    {
        $uploadedFile = $request->files->get('file');
        if (!$uploadedFile) {
            //throw new BadRequestHttpException('"file" is required');
        }
        // optional fields
        $phoneNumber = $request->get('phoneNumber');
        $notes = $request->get('notes');
        $isApplicationSent = $request->get('isApplicationSent');
        $slug = $request->get('slug');


        // create a new entity and set its values
        $jobApplication = new Application();
        $jobApplication->setCompany($request->get('company'));
        $jobApplication->setPosition($request->get('position'));
        $jobApplication->setLocation($request->get('location'));
        $jobApplication->setLink($request->get('link'));
        $jobApplication->setEmail($request->get('email'));
        $jobApplication->setApplicationDate(new \DateTime($request->get('applicationDate')));
        $jobApplication->setSubject($request->get('subject'));
        $jobApplication->setMessage($request->get('message'));

        if ($phoneNumber) {
            $jobApplication->setPhoneNumber($phoneNumber);
        }
        if ($notes) {
            $jobApplication->setNotes($notes);
        }
        if ($slug) {
            $jobApplication->setSlug($slug);
        }

        if ($isApplicationSent) {
            $jobApplication->setIsApplicationSent($request->get('isApplicationSent'));
        }
        if ($uploadedFile) {
            // upload the file and save its filename
            $jobApplication->setCover($fileUploader->upload($uploadedFile));
        }

        return $jobApplication;
    }
}
