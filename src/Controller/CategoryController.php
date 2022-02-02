<?php 

namespace App\Controller;

use App\Repository\CategoryRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/customer/category')]
class CategoryController extends AbstractController
{
    #[Route('/', name: 'category_index', methods: ['GET'])]
    public function index(CategoryRepository $categoryRepository): Response
    {
        return $this->render('customer/category/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    #[Route('boutique/categorie/{id}', name: 'boutique_category_show_by_category')]
    public function showProductByCategory(int $id, CategoryRepository $categoryRepository)
    {
        $category = $categoryRepository->find($id);

        if(!$category)
        {
            return $this->redirectToRoute("home");
        }

        return $this->render("customer/product/show_by_category.html.twig",[
            'category' => $category
        ]);
    }
    #[Route('boutique/categories/{id}', name: 'boutique_category_detail')]
    public function detailProduct(int $id, CategoryRepository $CategoryRepository)
    {
    
        $product = $CategoryRepository->find($id);

        if(!$product)
        {
            return $this->redirectToRoute("home");
        }
    }

}

?>