<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AuthController extends AbstractController
{
    #[Route('/auth/test', name: 'test')]
    public function test(Request $request)
    {
        return $this->json([
            'message' => 'Hello world',
        ], 200);
    }
}