<?php

namespace App\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/user/info', name: 'user_info')]
    public function info(Request $request)
    {
        return $this->json([
            'message' => 'Hello world',
            'user' => $this->getUser()
        ], 200);
    }
}