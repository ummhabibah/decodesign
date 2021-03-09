<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
	private $entityManager;

	public function __construct(EntityManagerInterface $entityManager) {
		$this->entityManager = $entityManager;
	}

   /**
    * @Route("/cart", name="cart")
    */
   public function index(Cart $cart): Response
   {
      return $this->render('cart/index.html.twig', [
      	'cart' => $cart->getCompleteCart()
      ]);
   }

   /**
    * @Route("/cart/add/{id}", name="cart_add")
    */
   public function add(Cart $cart, $id): Response
   {
   	$cart->add($id);
      return $this->redirectToRoute('cart');
   }

   /**
    * @Route("/cart/delete/{id}", name="cart_delete")
    */
   public function delete(Cart $cart, $id): Response
   {
   	$cart->delete($id);
      return $this->redirectToRoute('cart');
   }

   /**
    * @Route("/cart/remove", name="cart_remove")
    */
   public function remove(Cart $cart): Response
   {
   	$cart->remove();
      return $this->redirectToRoute('products');
   }

   /**
    * @Route("/cart/decrease/{id}", name="cart_decrease")
    */
   public function decrease(Cart $cart, $id): Response
   {
   	$cart->decrease($id);
      return $this->redirectToRoute('cart');
   }
}
