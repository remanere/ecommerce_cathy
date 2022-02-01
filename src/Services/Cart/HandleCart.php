<?php 

namespace App\Services\Cart;

use App\Repository\ProductRepository;
use App\Services\Cart\CartItem;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class HandleCart
{
    private $session;

    private $productRepository;

    public function __construct(SessionInterface $session,ProductRepository $productRepository)
    {
        $this->session = $session;
        $this->productRepository = $productRepository;
    }

    private function getCart()
    {
        return $this->session->get('cart',[]);
    }

    private function saveCart($cart)
    {
        $this->session->set('cart',$cart);
    }

    public function add($id)
    {
        $cart = $this->getCart();

        foreach($cart as $item)
        {
            if($item->getId() === $id)
            {
                $qtyActual = $item->getQty();
                $newQty = $qtyActual + 1;

                $item->setQty($newQty);

                $this->saveCart($cart);

                return;
            }
        }

        $cartItem = new CartItem();
        $cartItem->setId($id);
        $cartItem->setQty(1);

        $cart[] = $cartItem;

        $this->saveCart($cart);
    }

    public function detailPanier()
    {
       
        $detailProducts = [];

        $cart = $this->getCart();

        foreach($cart as $item)
        {

            $product = $this->productRepository->find($item->getId());

            $cartRealProduct = new CartRealProduct();
            $cartRealProduct->setProduct($product);
            $cartRealProduct->setQty($item->getQty());

            $detailProducts[] = $cartRealProduct;
        }

        return $detailProducts;
    }

    public function getTotalPanier()
    {
        $cart = $this->getCart();

        $total = 0;

        foreach($cart as $item)
        {
            $product = $this->productRepository->find($item->getId());

            $prixItem = $product->getPrice() * $item->getQty();

            $total +=  $prixItem;
        }

        return $total;
    }

    public function removeItem($id)
    {
        $cart = $this->getCart();

        foreach($cart as $key => $item)
        {
            if($item->getId() === $id)
            {
                unset($cart[$key]);
                $this->saveCart($cart);
                return;
            }
        }
    }

    public function decrementItem($id)
    {
             $cart = $this->getCart();

    
        foreach($cart as $key => $item)
        {
            
            if($item->getId() === $id)
            {
         
                $qty = $item->getQty();

                if($qty === 1)
                {
                    unset($cart[$key]);
                    $this->saveCart($cart);
                    return;
                }
                else 
                {
                    $item->setQty($qty - 1);
                    $this->saveCart($cart);
                    return;
                }
            }
        }
    }

    public function emptyCart()
    {
        $this->saveCart([]);
    }
}