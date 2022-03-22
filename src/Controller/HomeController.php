<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(CategoryRepository $categoryRepository, ProductRepository $productRepository,  PaginatorInterface $paginator, Request $request): Response
    {

      $categories = $categoryRepository->findAll();
      $products = $productRepository->findBy(
              [],
              [
                  'id' => 'DESC'
              ],
              6
          );

 

      return $this->render('customer/home.html.twig',[
          'categories' => $categories,
          'products' => $products
      ]);

      
  }
}
