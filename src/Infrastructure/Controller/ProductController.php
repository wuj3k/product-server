<?php declare(strict_types=1);


namespace App\Infrastructure\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ProductController
{
    public function listAction(): Response
    {
        return new JsonResponse(['dupa']);
    }
}