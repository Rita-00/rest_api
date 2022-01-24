<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\HealthService;

class HealthController extends AbstractController
{
    /**
     * @Route("/health", name="health", methods={"GET"})
     */
    public function index(HealthService $healthService): Response
    {
        return $this->json([
            'status' => "200",
            'APP_ENV' => $healthService->getApp_Env(),
        ]);
    }
}
