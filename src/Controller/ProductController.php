<?php 

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Repository\CategoryRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProductController extends AbstractController
{

    #[Route('boutique/categorie/{id}', name: 'boutique_product_show_by_category')]
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
    #[Route('boutique/produit/{id}', name: 'boutique_product_detail')]
    public function detailProduct(int $id, ProductRepository $productRepository)
    {
    
        $product = $productRepository->find($id);

        if(!$product)
        {
            return $this->redirectToRoute("home");
        }

        $category = $product->getCategory();

        $productsCategory = $category->getProducts();

        $suggestedProducts = [];

        foreach($productsCategory as $item)
        {
            if($item !== $product)
            {
                $suggestedProducts[] = $item;
            }
        }

        $suggestedProducts = array_slice($suggestedProducts,0,4);

        return $this->render("customer/product/detail_product.html.twig",[
            'product' => $product,
            'suggestedProducts' => $suggestedProducts
        ]);
    }

}

?>