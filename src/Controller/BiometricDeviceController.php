<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/biometric-devices')]
final class BiometricDeviceController extends AbstractController
{
    #[Route(name: 'app_biometric_device_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('biometric_device/index.html.twig');
    }
}
