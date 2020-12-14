<?php

namespace App\Controller;

use App\Entity\Product;
use App\Service\ParseHelper;
use App\Service\CriteriaHelper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ProductController extends AbstractController
{
    /**
     * @Route("/products", name="products")
     */
    public function index(Request $request, ParseHelper $parseHelper, CriteriaHelper $criteriaHelper): Response
    {
        $foo = $request->query->all();
        $criteria = $criteriaHelper->createCriteria($foo);
        $products = $this->getDoctrine()
            ->getRepository(Product::class)
            ->matching($criteria);

        return new JsonResponse([
            "status" => "Success",
            "products" => $parseHelper->entitiesToArray($products)
        ], Response::HTTP_OK, ["Access-Control-Allow-Origin" => "*"]);
    }
}
